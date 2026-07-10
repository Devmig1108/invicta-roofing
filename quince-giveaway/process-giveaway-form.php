<?php

$formDebug = false;

function loadSecureEnvForProcessForm(): void
{
    static $loaded = false;

    if ($loaded) {
        return;
    }

    $possiblePaths = [
        __DIR__ . '/../config/secure_env.php',
        __DIR__ . '/../../config/secure_env.php',
        __DIR__ . '/config/secure_env.php',
        isset($_SERVER['DOCUMENT_ROOT'])
            ? rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/../config/secure_env.php'
            : null,
    ];

    foreach ($possiblePaths as $path) {
        if ($path && file_exists($path)) {
            require_once $path;
            $loaded = true;
            return;
        }
    }

    error_log('secure_env.php not found in process-giveaway-form.php. Checked paths: ' . print_r($possiblePaths, true));
    http_response_code(500);
    exit('Configuration file not found.');
}

loadSecureEnvForProcessForm();

$formDebug = defined('FORM_DEBUG') ? (bool) FORM_DEBUG : false;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: quinceanera-giveaway.php#inspection-form');
    exit;
}

$recipientEmail = defined('INVICTA_FORM_RECIPIENT') ? INVICTA_FORM_RECIPIENT : 'support@invictaroofs.com';
$recipientName = defined('INVICTA_FORM_RECIPIENT_NAME') ? INVICTA_FORM_RECIPIENT_NAME : 'Invicta Roofing';

function getClientIp(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

function getReturnUrl(): string
{
    $fallback = 'quinceanera-giveaway.php';
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    if (empty($referer)) {
        return $fallback;
    }

    $refererParts = parse_url($referer);
    $currentHost = $_SERVER['HTTP_HOST'] ?? '';

    if (
        !$refererParts ||
        empty($refererParts['host']) ||
        strcasecmp($refererParts['host'], $currentHost) !== 0
    ) {
        return $fallback;
    }

    return $refererParts['path'] ?? $fallback;
}

function redirectWithStatus(string $status, ?string $reason = null): void
{
    $returnUrl = getReturnUrl();

    $query = [
        'status' => $status,
    ];

    if ($reason) {
        $query['debug_reason'] = $reason;
    }

    header('Location: ' . $returnUrl . '?' . http_build_query($query) . '#inspection-form');
    exit;
}

function getPostDebugSummary(): array
{
    return [
        'post_keys' => array_keys($_POST),
        'has_fullName' => isset($_POST['fullName']) && trim((string) $_POST['fullName']) !== '',
        'has_phone' => isset($_POST['phone']) && trim((string) $_POST['phone']) !== '',
        'has_email' => isset($_POST['email']) && trim((string) $_POST['email']) !== '',
        'has_propertyAddress' => isset($_POST['propertyAddress']) && trim((string) $_POST['propertyAddress']) !== '',
        'has_preferredDateTime' => isset($_POST['preferredDateTime']) && trim((string) $_POST['preferredDateTime']) !== '',
        'has_heardAbout' => isset($_POST['heardAbout']) && trim((string) $_POST['heardAbout']) !== '',
        'has_homeownerConfirm' => !empty($_POST['homeownerConfirm']),
        'has_ageConfirm' => !empty($_POST['ageConfirm']),
        'has_rulesConfirm' => !empty($_POST['rulesConfirm']),
        'has_form_token' => isset($_POST['form_token']) && trim((string) $_POST['form_token']) !== '',
        'has_turnstile_response' => isset($_POST['cf-turnstile-response']) && trim((string) $_POST['cf-turnstile-response']) !== '',
    ];
}

function getLogFilePath(): string
{
    $logDir = __DIR__ . '/../logs';

    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }

    if (is_dir($logDir) && is_writable($logDir)) {
        return $logDir . '/invicta-giveaway-form.jsonl';
    }

    return __DIR__ . '/form-debug-log.jsonl';
}

function logSubmissionEvent(string $type, string $reason, array $extra = []): void
{
    $entry = [
        'time' => date('c'),
        'type' => $type,
        'reason' => $reason,
        'ip' => getClientIp(),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'post_debug' => getPostDebugSummary(),
        'extra' => $extra,
    ];

    $encoded = json_encode($entry, JSON_UNESCAPED_SLASHES);

    if ($encoded) {
        @file_put_contents(getLogFilePath(), $encoded . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

function blockedExit(string $reason, bool $silentWhenNotDebug = true, array $extra = []): void
{
    global $formDebug;

    logSubmissionEvent('blocked', $reason, $extra);

    if ($formDebug || !$silentWhenNotDebug) {
        redirectWithStatus('error', $reason);
    }

    redirectWithStatus('success');
}

function verifySignedFormToken(string $token, int $maxAgeSeconds = 7200): bool
{
    if (empty($token)) {
        return false;
    }

    if (!defined('FORM_TOKEN_SECRET') || empty(FORM_TOKEN_SECRET)) {
        error_log('Missing FORM_TOKEN_SECRET.');
        return false;
    }

    $decoded = base64_decode($token, true);

    if ($decoded === false) {
        return false;
    }

    $parts = explode('.', $decoded);

    if (count($parts) !== 3) {
        return false;
    }

    [$issuedAt, $nonce, $signature] = $parts;

    if (!ctype_digit($issuedAt)) {
        return false;
    }

    $issuedAt = (int) $issuedAt;

    if ($issuedAt <= 0) {
        return false;
    }

    $age = time() - $issuedAt;

    if ($age < 0 || $age > $maxAgeSeconds) {
        return false;
    }

    if (!preg_match('/^[a-f0-9]{32}$/', $nonce)) {
        return false;
    }

    $payload = $issuedAt . '.' . $nonce;
    $expectedSignature = hash_hmac('sha256', $payload, FORM_TOKEN_SECRET);

    return hash_equals($expectedSignature, $signature);
}

function postValue(string $key, int $maxLength = 1000): string
{
    if (!isset($_POST[$key]) || is_array($_POST[$key])) {
        return '';
    }

    $value = trim((string) $_POST[$key]);
    $value = strip_tags($value);

    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $maxLength);
    }

    return substr($value, 0, $maxLength);
}

function rateLimit(int $limit = 12, int $windowSeconds = 600): bool
{
    $ip = getClientIp();
    $key = hash('sha256', $ip);
    $file = sys_get_temp_dir() . "/invicta_giveaway_rate_{$key}.json";

    $now = time();
    $data = [
        'count' => 0,
        'start' => $now,
    ];

    if (file_exists($file)) {
        $existing = json_decode((string) file_get_contents($file), true);

        if (is_array($existing) && isset($existing['count'], $existing['start'])) {
            $data = $existing;
        }
    }

    if (($now - (int) $data['start']) > $windowSeconds) {
        $data = [
            'count' => 0,
            'start' => $now,
        ];
    }

    $data['count']++;

    @file_put_contents($file, json_encode($data), LOCK_EX);

    return $data['count'] <= $limit;
}

function verifyTurnstile(string $token): bool
{
    if (empty($token)) {
        error_log('Turnstile failed: missing cf-turnstile-response token.');
        return false;
    }

    if (!defined('TURNSTILE_SECRET_KEY') || empty(TURNSTILE_SECRET_KEY)) {
        error_log('Turnstile failed: missing TURNSTILE_SECRET_KEY.');
        return false;
    }

    $ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'secret' => TURNSTILE_SECRET_KEY,
            'response' => $token,
            'remoteip' => getClientIp(),
        ]),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded',
        ],
        CURLOPT_TIMEOUT => 8,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);

    curl_close($ch);

    if ($response === false || !empty($curlError)) {
        error_log('Turnstile cURL Error: ' . $curlError);
        return false;
    }

    $result = json_decode($response, true);

    if ($httpCode !== 200 || !is_array($result)) {
        error_log("Turnstile Verify Error: HTTP {$httpCode}. Response: " . $response);
        return false;
    }

    if (empty($result['success'])) {
        error_log('Turnstile validation failed. Response: ' . $response);
        return false;
    }

    return true;
}

function getSpamReasons(string $name, string $phone, string $propertyAddress, string $email, string $preferredDateTime, string $heardAbout): array
{
    $combined = strtolower($name . ' ' . $phone . ' ' . $propertyAddress . ' ' . $email . ' ' . $preferredDateTime . ' ' . $heardAbout);
    $reasons = [];

    $urlCount = substr_count($combined, 'http://') + substr_count($combined, 'https://') + substr_count($combined, 'www.');

    if ($urlCount >= 1) {
        $reasons[] = 'contains_url';
    }

    if (preg_match('/\b(casino|viagra|cialis|levitra|crypto|bitcoin|forex|loan|payday|backlink|backlinks|guest post|seo services|rank on google|telegram|whatsapp marketing)\b/i', $combined)) {
        $reasons[] = 'spam_keywords';
    }

    if (preg_match('/\[url=|\<a\s+href/i', $combined)) {
        $reasons[] = 'link_markup';
    }

    return $reasons;
}

/**
 * 1. Honeypot
 */
if (!empty($_POST['website_verification_code'] ?? '')) {
    blockedExit('honeypot_filled', true);
}

/**
 * 2. Stateless signed form token
 */
$formToken = $_POST['form_token'] ?? '';

if (!verifySignedFormToken($formToken)) {
    blockedExit('invalid_form_token', false);
}

/**
 * 3. Rate limit
 */
if (!rateLimit(12, 600)) {
    blockedExit('rate_limited', false);
}

/**
 * 4. Grab fields
 */
$name = postValue('fullName', 120);
$phone = postValue('phone', 40);
$propertyAddress = postValue('propertyAddress', 220);
$email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_SANITIZE_EMAIL);
$preferredDateTime = postValue('preferredDateTime', 160);
$heardAbout = postValue('heardAbout', 80);
$serviceRequested = postValue('serviceRequested', 100);
$formType = postValue('form_type', 100);

$homeownerConfirmed = !empty($_POST['homeownerConfirm']);
$ageConfirmed = !empty($_POST['ageConfirm']);
$rulesConfirmed = !empty($_POST['rulesConfirm']);
$textReminders = !empty($_POST['textReminders']);

if (empty($serviceRequested)) {
    $serviceRequested = 'Free Inspection';
}

/**
 * 5. Validate required fields
 */
if (
    empty($name) ||
    empty($phone) ||
    empty($propertyAddress) ||
    empty($email) ||
    empty($preferredDateTime) ||
    empty($heardAbout)
) {
    logSubmissionEvent('validation_error', 'missing_required_fields', [
        'name_empty' => empty($name),
        'phone_empty' => empty($phone),
        'property_address_empty' => empty($propertyAddress),
        'email_empty' => empty($email),
        'preferred_date_time_empty' => empty($preferredDateTime),
        'heard_about_empty' => empty($heardAbout),
    ]);

    redirectWithStatus('error', 'missing_required_fields');
}

$phoneDigits = preg_replace('/\D+/', '', $phone);

if (strlen($phoneDigits) < 10) {
    logSubmissionEvent('validation_error', 'invalid_phone', [
        'phone_digits_count' => strlen($phoneDigits),
    ]);

    redirectWithStatus('error', 'invalid_phone');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    logSubmissionEvent('validation_error', 'invalid_email');
    redirectWithStatus('error', 'invalid_email');
}

if (!$homeownerConfirmed || !$ageConfirmed || !$rulesConfirmed) {
    logSubmissionEvent('validation_error', 'required_confirmations_missing', [
        'homeowner_confirmed' => $homeownerConfirmed,
        'age_confirmed' => $ageConfirmed,
        'rules_confirmed' => $rulesConfirmed,
    ]);

    redirectWithStatus('error', 'required_confirmations_missing');
}

$allowedHeardAbout = [
    'Social Media',
    'Billboard',
    'Referral',
    'Google',
    'Other',
];

if (!in_array($heardAbout, $allowedHeardAbout, true)) {
    logSubmissionEvent('validation_error', 'unexpected_heard_about_value', [
        'heard_about' => $heardAbout,
    ]);

    redirectWithStatus('error', 'missing_required_fields');
}

/**
 * 6. Content spam filter
 */
$spamReasons = getSpamReasons($name, $phone, $propertyAddress, $email, $preferredDateTime, $heardAbout);

if (!empty($spamReasons)) {
    blockedExit('content_filter_' . implode('_', $spamReasons), true, [
        'spam_reasons' => $spamReasons,
    ]);
}

/**
 * 7. Turnstile verification
 */
$turnstileToken = $_POST['cf-turnstile-response'] ?? '';

if (!verifyTurnstile($turnstileToken)) {
    blockedExit('turnstile_failed', false);
}

/**
 * 8. ZeptoMail
 */
if (!defined('ZEPTO_API_KEY') || empty(ZEPTO_API_KEY)) {
    error_log('Missing ZEPTO_API_KEY');
    redirectWithStatus('mail_error', 'missing_zepto_api_key');
}

$apiKey = ZEPTO_API_KEY;
$url = 'https://api.zeptomail.com/v1.1/email';

$fromAddress = defined('ZEPTO_FROM_ADDRESS') ? ZEPTO_FROM_ADDRESS : 'services@invictaroofs.com';
$fromName = defined('ZEPTO_FROM_NAME') ? ZEPTO_FROM_NAME : 'Invicta Roofing';
$bounceAddress = defined('ZEPTO_BOUNCE_ADDRESS') ? ZEPTO_BOUNCE_ADDRESS : '';

$safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$safePhone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$safePropertyAddress = htmlspecialchars($propertyAddress, ENT_QUOTES, 'UTF-8');
$safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$safePreferredDateTime = htmlspecialchars($preferredDateTime, ENT_QUOTES, 'UTF-8');
$safeHeardAbout = htmlspecialchars($heardAbout, ENT_QUOTES, 'UTF-8');
$safeServiceRequested = htmlspecialchars($serviceRequested, ENT_QUOTES, 'UTF-8');
$safeFormType = htmlspecialchars($formType, ENT_QUOTES, 'UTF-8');
$safeIp = htmlspecialchars(getClientIp(), ENT_QUOTES, 'UTF-8');
$safeUserAgent = htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? '', ENT_QUOTES, 'UTF-8');

$homeownerText = $homeownerConfirmed ? 'Yes' : 'No';
$ageText = $ageConfirmed ? 'Yes' : 'No';
$rulesText = $rulesConfirmed ? 'Yes' : 'No';
$textReminderText = $textReminders ? 'Yes' : 'No';

$subject = "Invicta Giveaway Inspection Request - {$name}";

$htmlBody = "
    <h2>New Invicta Giveaway Inspection Request</h2>
    <p><strong>Name:</strong> {$safeName}</p>
    <p><strong>Phone:</strong> {$safePhone}</p>
    <p><strong>Email:</strong> {$safeEmail}</p>
    <p><strong>Property Address:</strong> {$safePropertyAddress}</p>
    <p><strong>Preferred Date/Time:</strong> {$safePreferredDateTime}</p>
    <p><strong>How They Heard About Invicta:</strong> {$safeHeardAbout}</p>
    <p><strong>Service Requested:</strong> {$safeServiceRequested}</p>
    <hr>
    <h3>Required Confirmations</h3>
    <p><strong>Homeowner of this property:</strong> {$homeownerText}</p>
    <p><strong>18 or older:</strong> {$ageText}</p>
    <p><strong>Read and agreed to Official Rules:</strong> {$rulesText}</p>
    <p><strong>Text appointment reminders:</strong> {$textReminderText}</p>
    <hr>
    <p><strong>Important:</strong> No purchase necessary. This submission is a free inspection request. Giveaway eligibility, entries, and prize terms are controlled by the Official Rules.</p>
    <hr>
    <p style=\"font-size:12px;color:#666;\"><strong>Form Type:</strong> {$safeFormType}</p>
    <p style=\"font-size:12px;color:#666;\"><strong>Submitted IP:</strong> {$safeIp}</p>
    <p style=\"font-size:12px;color:#666;\"><strong>User Agent:</strong> {$safeUserAgent}</p>
";

$payloadData = [
    'from' => [
        'address' => $fromAddress,
        'name' => $fromName,
    ],
    'to' => [
        [
            'email_address' => [
                'address' => $recipientEmail,
                'name' => $recipientName,
            ],
        ],
    ],
    'subject' => $subject,
    'htmlbody' => $htmlBody,
];

if (!empty($bounceAddress)) {
    $payloadData['bounce_address'] = $bounceAddress;
}

$payload = json_encode($payloadData);

if (!$payload) {
    error_log('ZeptoMail Payload JSON Error');
    redirectWithStatus('mail_error', 'payload_json_error');
}

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        "Authorization: {$apiKey}",
        'Accept: application/json',
    ],
    CURLOPT_TIMEOUT => 10,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

curl_close($ch);

if ($response === false || !empty($curlError)) {
    error_log('ZeptoMail cURL Error: ' . $curlError);
    redirectWithStatus('mail_error', 'zepto_curl_error');
}

if ($httpCode === 201) {
    logSubmissionEvent('sent', 'email_sent_successfully', [
        'email' => $email,
        'service_requested' => $serviceRequested,
        'heard_about' => $heardAbout,
        'text_reminders' => $textReminders,
    ]);

    redirectWithStatus('success');
}

error_log("ZeptoMail API Error. HTTP {$httpCode}. Response: " . $response);
redirectWithStatus('mail_error', 'zepto_http_' . $httpCode);
