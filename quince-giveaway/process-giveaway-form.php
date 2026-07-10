<?php

declare(strict_types=1);

/**
 * Invicta Quinceañera Giveaway Form Processor
 *
 * Current demo/staging behavior:
 * - Validates required fields
 * - Validates homeowner / 18+ / rules agreement checkboxes
 * - Checks honeypot
 * - Checks signed form token
 * - Optionally checks Turnstile if enabled
 * - Saves submission to private JSONL log when FORM_DELIVERY_MODE = 'log'
 * - Sends email later when FORM_DELIVERY_MODE = 'email'
 */

require_once dirname(__DIR__) . '/config/secure_env.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectBack('error', 'invalid_request');
}

/**
 * ------------------------------------------------------------
 * Basic helpers
 * ------------------------------------------------------------
 */

function postValue(string $key, string $default = ''): string
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
}

function checkboxValue(string $key): bool
{
    return isset($_POST[$key]) && in_array((string) $_POST[$key], ['1', 'yes', 'on', 'true'], true);
}

function cleanText(string $value): string
{
    $value = trim($value);
    $value = strip_tags($value);
    $value = preg_replace('/\s+/', ' ', $value) ?? $value;

    return $value;
}

function redirectBack(string $status, string $reason = ''): void
{
    $debug = defined('FORM_DEBUG') && FORM_DEBUG === true;
    $query = ['status' => $status];

    if ($debug && $reason !== '') {
        $query['debug_reason'] = $reason;
    }

    header('Location: quinceanera-giveaway.php?' . http_build_query($query));
    exit;
}

function logDebug(string $event, array $context = []): void
{
    if (!defined('FORM_DEBUG') || FORM_DEBUG !== true) {
        return;
    }

    $logDir = defined('FORM_LOG_DIR')
        ? FORM_LOG_DIR
        : dirname(__DIR__) . '/storage/private';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $payload = [
        'logged_at' => date('c'),
        'event' => $event,
        'context' => $context,
    ];

    file_put_contents(
        $logDir . '/giveaway-debug.jsonl',
        json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

/**
 * ------------------------------------------------------------
 * Security checks
 * ------------------------------------------------------------
 */

// Honeypot field. This should be hidden with CSS.
// If a bot fills it out, reject the submission.
$honeypot = postValue('website_verification_code');

if ($honeypot !== '') {
    logDebug('honeypot_triggered', [
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        'honeypot' => $honeypot,
    ]);

    redirectBack('error', 'spam_detected');
}

// Signed form token.
$formToken = postValue('form_token');

if (!validateSignedFormToken($formToken)) {
    logDebug('invalid_form_token', [
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    redirectBack('error', 'invalid_form_token');
}

// Simple rate limiting.
if (!passesRateLimit()) {
    logDebug('rate_limited', [
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    redirectBack('error', 'rate_limited');
}

// Turnstile verification.
// Disabled in staging if TURNSTILE_ENABLED is false.
if (defined('TURNSTILE_ENABLED') && TURNSTILE_ENABLED === true) {
    $turnstileToken = postValue('cf-turnstile-response');

    if (!verifyTurnstile($turnstileToken)) {
        logDebug('turnstile_failed', [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);

        redirectBack('error', 'turnstile_failed');
    }
}

/**
 * ------------------------------------------------------------
 * Collect and validate fields
 * ------------------------------------------------------------
 */

$fullName = cleanText(postValue('fullName'));
$propertyAddress = cleanText(postValue('propertyAddress'));
$phone = cleanText(postValue('phone'));
$email = cleanText(postValue('email'));
$preferredDateTime = cleanText(postValue('preferredDateTime'));
$hearAboutUs = cleanText(postValue('hearAboutUs'));
$service = cleanText(postValue('service', 'Free Roof Inspection'));
$message = cleanText(postValue('message'));

$isHomeowner = checkboxValue('homeowner_confirm');
$isAdult = checkboxValue('age_confirm');
$acceptedRules = checkboxValue('rules_confirm');
$textReminders = checkboxValue('text_reminders');

$allowedHearAbout = [
    'Social Media',
    'Billboard',
    'Referral',
    'Google',
    'Other',
];

$errors = [];

if ($fullName === '') {
    $errors[] = 'missing_name';
}

if ($propertyAddress === '') {
    $errors[] = 'missing_property_address';
}

if ($phone === '') {
    $errors[] = 'missing_phone';
}

if ($email === '') {
    $errors[] = 'missing_email';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'invalid_email';
}

if ($preferredDateTime === '') {
    $errors[] = 'missing_preferred_date_time';
}

if ($hearAboutUs === '') {
    $errors[] = 'missing_hear_about_us';
} elseif (!in_array($hearAboutUs, $allowedHearAbout, true)) {
    $errors[] = 'invalid_hear_about_us';
}

if (!$isHomeowner) {
    $errors[] = 'homeowner_not_confirmed';
}

if (!$isAdult) {
    $errors[] = 'age_not_confirmed';
}

if (!$acceptedRules) {
    $errors[] = 'rules_not_accepted';
}

if ($phone !== '' && !preg_match('/^[0-9+\-\s().]{7,30}$/', $phone)) {
    $errors[] = 'invalid_phone';
}

if (mb_strlen($fullName) > 120) {
    $errors[] = 'name_too_long';
}

if (mb_strlen($propertyAddress) > 220) {
    $errors[] = 'property_address_too_long';
}

if (mb_strlen($preferredDateTime) > 120) {
    $errors[] = 'preferred_date_time_too_long';
}

if (mb_strlen($message) > 3000) {
    $errors[] = 'message_too_long';
}

if (!empty($errors)) {
    logDebug('validation_failed', [
        'errors' => $errors,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    redirectBack('error', implode(',', $errors));
}

/**
 * ------------------------------------------------------------
 * Build submission payload
 * ------------------------------------------------------------
 */

$submission = [
    'submitted_at' => date('c'),
    'source' => 'quinceanera-giveaway',
    'environment' => defined('FORM_DELIVERY_MODE') ? FORM_DELIVERY_MODE : 'log',

    'full_name' => $fullName,
    'property_address' => $propertyAddress,
    'phone' => $phone,
    'email' => $email,
    'preferred_date_time' => $preferredDateTime,
    'service' => $service,
    'how_did_you_hear_about_us' => $hearAboutUs,
    'message' => $message,

    'homeowner_confirmed' => $isHomeowner ? 'Yes' : 'No',
    'age_confirmed' => $isAdult ? 'Yes' : 'No',
    'official_rules_accepted' => $acceptedRules ? 'Yes' : 'No',
    'text_reminders_opt_in' => $textReminders ? 'Yes' : 'No',

    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
];

/**
 * ------------------------------------------------------------
 * Delivery mode
 * ------------------------------------------------------------
 */

$deliveryMode = defined('FORM_DELIVERY_MODE') ? FORM_DELIVERY_MODE : 'log';

if ($deliveryMode === 'log') {
    saveGiveawaySubmission($submission);
    redirectBack('success');
}

if ($deliveryMode === 'email') {
    $sent = sendSubmissionEmail($submission);

    if (!$sent) {
        logDebug('email_send_failed', [
            'email' => $email,
            'phone' => $phone,
        ]);

        redirectBack('error', 'email_send_failed');
    }

    saveGiveawaySubmission($submission);
    redirectBack('success');
}

// Fallback if config has an unsupported mode.
logDebug('invalid_delivery_mode', [
    'delivery_mode' => $deliveryMode,
]);

redirectBack('error', 'invalid_delivery_mode');


/**
 * ------------------------------------------------------------
 * Function definitions
 * ------------------------------------------------------------
 */

function validateSignedFormToken(string $token): bool
{
    if ($token === '') {
        return false;
    }

    if (!defined('FORM_TOKEN_SECRET') || FORM_TOKEN_SECRET === '') {
        return false;
    }

    $parts = explode('.', $token);

    if (count($parts) !== 2) {
        return false;
    }

    [$payloadEncoded, $providedSignature] = $parts;

    $expectedSignature = hash_hmac('sha256', $payloadEncoded, FORM_TOKEN_SECRET);

    if (!hash_equals($expectedSignature, $providedSignature)) {
        return false;
    }

    $payloadJson = base64_decode($payloadEncoded, true);

    if ($payloadJson === false) {
        return false;
    }

    $payload = json_decode($payloadJson, true);

    if (!is_array($payload) || empty($payload['ts'])) {
        return false;
    }

    $issuedAt = (int) $payload['ts'];
    $maxAgeSeconds = defined('FORM_TOKEN_MAX_AGE_SECONDS')
        ? (int) FORM_TOKEN_MAX_AGE_SECONDS
        : 7200;

    if ($issuedAt <= 0) {
        return false;
    }

    if (time() - $issuedAt > $maxAgeSeconds) {
        return false;
    }

    return true;
}

function passesRateLimit(): bool
{
    $limitWindowSeconds = defined('FORM_RATE_LIMIT_WINDOW_SECONDS')
        ? (int) FORM_RATE_LIMIT_WINDOW_SECONDS
        : 60;

    $maxSubmissions = defined('FORM_RATE_LIMIT_MAX_SUBMISSIONS')
        ? (int) FORM_RATE_LIMIT_MAX_SUBMISSIONS
        : 3;

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    $logDir = defined('FORM_LOG_DIR')
        ? FORM_LOG_DIR
        : dirname(__DIR__) . '/storage/private';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $rateFile = $logDir . '/giveaway-rate-limit.json';

    $data = [];

    if (file_exists($rateFile)) {
        $decoded = json_decode((string) file_get_contents($rateFile), true);
        $data = is_array($decoded) ? $decoded : [];
    }

    $now = time();

    foreach ($data as $storedIp => $timestamps) {
        if (!is_array($timestamps)) {
            unset($data[$storedIp]);
            continue;
        }

        $data[$storedIp] = array_values(array_filter(
            $timestamps,
            fn ($timestamp) => is_int($timestamp) && ($now - $timestamp) <= $limitWindowSeconds
        ));

        if (empty($data[$storedIp])) {
            unset($data[$storedIp]);
        }
    }

    $data[$ip] = $data[$ip] ?? [];

    if (count($data[$ip]) >= $maxSubmissions) {
        file_put_contents($rateFile, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
        return false;
    }

    $data[$ip][] = $now;

    file_put_contents($rateFile, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);

    return true;
}

function verifyTurnstile(string $token): bool
{
    if ($token === '') {
        return false;
    }

    if (!defined('TURNSTILE_SECRET_KEY') || TURNSTILE_SECRET_KEY === '') {
        return false;
    }

    $postData = http_build_query([
        'secret' => TURNSTILE_SECRET_KEY,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $postData,
            'timeout' => 8,
        ],
    ]);

    $response = file_get_contents(
        'https://challenges.cloudflare.com/turnstile/v0/siteverify',
        false,
        $context
    );

    if ($response === false) {
        return false;
    }

    $result = json_decode($response, true);

    return is_array($result) && !empty($result['success']);
}

function saveGiveawaySubmission(array $data): void
{
    $logDir = defined('FORM_LOG_DIR')
        ? FORM_LOG_DIR
        : dirname(__DIR__) . '/storage/private';

    $logFile = defined('FORM_LOG_FILE')
        ? FORM_LOG_FILE
        : $logDir . '/giveaway-submissions.jsonl';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $htaccessPath = $logDir . '/.htaccess';

    if (!file_exists($htaccessPath)) {
        file_put_contents(
            $htaccessPath,
            "Require all denied\nDeny from all\n",
            LOCK_EX
        );
    }

    file_put_contents(
        $logFile,
        json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

function sendSubmissionEmail(array $submission): bool
{
    if (
        !defined('ZEPTO_API_KEY') || ZEPTO_API_KEY === '' ||
        !defined('ZEPTO_FROM_ADDRESS') || ZEPTO_FROM_ADDRESS === '' ||
        !defined('ZEPTO_BOUNCE_ADDRESS') || ZEPTO_BOUNCE_ADDRESS === '' ||
        !defined('INVICTA_FORM_RECIPIENT') || INVICTA_FORM_RECIPIENT === ''
    ) {
        return false;
    }

    $subject = 'Invicta Giveaway Inspection Request - ' . $submission['full_name'];

    $body = buildEmailBody($submission);

    $payload = [
        'from' => [
            'address' => ZEPTO_FROM_ADDRESS,
            'name' => defined('ZEPTO_FROM_NAME') ? ZEPTO_FROM_NAME : 'Invicta Roofing',
        ],
        'to' => [
            [
                'email_address' => [
                    'address' => INVICTA_FORM_RECIPIENT,
                    'name' => 'Invicta Roofing',
                ],
            ],
        ],
        'reply_to' => [
            [
                'address' => $submission['email'],
                'name' => $submission['full_name'],
            ],
        ],
        'subject' => $subject,
        'htmlbody' => nl2br(htmlspecialchars($body, ENT_QUOTES, 'UTF-8')),
        'textbody' => $body,
        'bounce_address' => ZEPTO_BOUNCE_ADDRESS,
    ];

    $ch = curl_init('https://api.zeptomail.com/v1.1/email');

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Zoho-enczapikey ' . ZEPTO_API_KEY,
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 12,
    ]);

    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);

    curl_close($ch);

    if ($response === false || $httpCode < 200 || $httpCode >= 300) {
        logDebug('zeptomail_error', [
            'http_code' => $httpCode,
            'curl_error' => $curlError,
            'response' => $response,
        ]);

        return false;
    }

    return true;
}

function buildEmailBody(array $submission): string
{
    return <<<TEXT
New Invicta Giveaway Inspection Request

Submitted At:
{$submission['submitted_at']}

Contact Information:
Name: {$submission['full_name']}
Phone: {$submission['phone']}
Email: {$submission['email']}
Property Address: {$submission['property_address']}

Appointment / Service:
Preferred Date or Time: {$submission['preferred_date_time']}
Requested Service: {$submission['service']}
How They Heard About Invicta: {$submission['how_did_you_hear_about_us']}

Legal / Consent Confirmations:
Homeowner Confirmed: {$submission['homeowner_confirmed']}
18 or Older Confirmed: {$submission['age_confirmed']}
Official Rules Accepted: {$submission['official_rules_accepted']}
Text Reminders Opt-In: {$submission['text_reminders_opt_in']}

Message:
{$submission['message']}

Technical:
IP Address: {$submission['ip_address']}
User Agent: {$submission['user_agent']}
TEXT;
}