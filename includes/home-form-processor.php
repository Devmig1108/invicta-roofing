<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/secure_env.php';

function processHomeForm(string $formKind): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirectHomeForm($formKind, 'error', 'invalid_request');
    }

    $honeypot = postValue('website_verification_code');

    if ($honeypot !== '') {
        logHomeFormDebug('honeypot_triggered', [
            'form' => $formKind,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);

        redirectHomeForm($formKind, 'error', 'spam_detected');
    }

    $formToken = postValue('form_token');

    if (!validateSignedHomeFormToken($formToken)) {
        logHomeFormDebug('invalid_form_token', [
            'form' => $formKind,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);

        redirectHomeForm($formKind, 'error', 'invalid_form_token');
    }

    if (!passesHomeFormRateLimit($formKind)) {
        logHomeFormDebug('rate_limited', [
            'form' => $formKind,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);

        redirectHomeForm($formKind, 'error', 'rate_limited');
    }

    $submission = $formKind === 'quick'
        ? collectQuickHomeSubmission()
        : collectDetailedHomeSubmission();

    if (!empty($submission['errors'])) {
        logHomeFormDebug('validation_failed', [
            'form' => $formKind,
            'errors' => $submission['errors'],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);

        redirectHomeForm($formKind, 'error', $submission['errors'][0]);
    }

    $deliveryMode = defined('FORM_DELIVERY_MODE') ? FORM_DELIVERY_MODE : 'log';

    if ($deliveryMode === 'log') {
        saveHomeFormSubmission($submission);
        redirectHomeForm($formKind, 'success');
    }

    if ($deliveryMode === 'email') {
        $sent = sendHomeSubmissionEmail($submission);

        if (!$sent) {
            logHomeFormDebug('email_send_failed', [
                'form' => $formKind,
                'name' => $submission['full_name'] ?? '',
                'phone' => $submission['phone'] ?? '',
            ]);

            redirectHomeForm($formKind, 'error', 'email_send_failed');
        }

        saveHomeFormSubmission($submission);
        redirectHomeForm($formKind, 'success');
    }

    logHomeFormDebug('invalid_delivery_mode', [
        'delivery_mode' => $deliveryMode,
    ]);

    redirectHomeForm($formKind, 'error', 'invalid_delivery_mode');
}

function collectQuickHomeSubmission(): array
{
    $formType = cleanText(postValue('form_type'));
    $name = cleanText(postValue('name'));
    $phone = cleanText(postValue('phone'));
    $service = cleanText(postValue('service'));

    $errors = [];

    if ($formType !== 'home_quick_inspection') {
        $errors[] = 'invalid_form_type';
    }

    if ($name === '') {
        $errors[] = 'missing_name';
    }

    if ($phone === '') {
        $errors[] = 'missing_phone';
    } elseif (!isValidPhone($phone)) {
        $errors[] = 'invalid_phone';
    }

    if ($service === '') {
        $errors[] = 'missing_service';
    } elseif (!isAllowedRoofingService($service)) {
        $errors[] = 'invalid_service';
    }

    if (textLength($name) > 120) {
        $errors[] = 'name_too_long';
    }

    if (textLength($service) > 120) {
        $errors[] = 'service_too_long';
    }

    return [
        'errors' => $errors,
        'submitted_at' => date('c'),
        'source' => 'invicta-homepage',
        'form_kind' => 'quick',
        'form_type' => $formType,
        'form_label' => 'Homepage Quick Inspection Form',

        'full_name' => $name,
        'phone' => $phone,
        'email' => '',
        'property_address' => '',
        'roofing_need' => $service,
        'heard_about' => '',
        'message' => '',

        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    ];
}

function collectDetailedHomeSubmission(): array
{
    $formType = cleanText(postValue('form_type'));
    $name = cleanText(postValue('fullName'));
    $phone = cleanText(postValue('phone'));
    $address = cleanText(postValue('address'));
    $roofingNeed = cleanText(postValue('roofingNeed'));
    $heardAbout = cleanText(postValue('heardAbout'));
    $message = cleanText(postValue('message'));

    $errors = [];

    if ($formType !== 'home_detailed_inspection') {
        $errors[] = 'invalid_form_type';
    }

    if ($name === '') {
        $errors[] = 'missing_name';
    }

    if ($phone === '') {
        $errors[] = 'missing_phone';
    } elseif (!isValidPhone($phone)) {
        $errors[] = 'invalid_phone';
    }

    if ($address === '') {
        $errors[] = 'missing_address';
    }

    if ($roofingNeed === '') {
        $errors[] = 'missing_service';
    } elseif (!isAllowedRoofingService($roofingNeed)) {
        $errors[] = 'invalid_service';
    }

    if (textLength($name) > 120) {
        $errors[] = 'name_too_long';
    }

    if (textLength($address) > 220) {
        $errors[] = 'address_too_long';
    }

    if (textLength($heardAbout) > 1000) {
        $errors[] = 'heard_about_too_long';
    }

    if (textLength($message) > 3000) {
        $errors[] = 'message_too_long';
    }

    return [
        'errors' => $errors,
        'submitted_at' => date('c'),
        'source' => 'invicta-homepage',
        'form_kind' => 'detailed',
        'form_type' => $formType,
        'form_label' => 'Homepage Detailed Inspection Form',

        'full_name' => $name,
        'phone' => $phone,
        'email' => '',
        'property_address' => $address,
        'roofing_need' => $roofingNeed,
        'heard_about' => $heardAbout,
        'message' => $message,

        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    ];
}

function sendHomeSubmissionEmail(array $submission): bool
{
    if (
        !defined('ZEPTO_API_KEY') || ZEPTO_API_KEY === '' ||
        !defined('ZEPTO_FROM_ADDRESS') || ZEPTO_FROM_ADDRESS === '' ||
        !defined('ZEPTO_BOUNCE_ADDRESS') || ZEPTO_BOUNCE_ADDRESS === '' ||
        !defined('INVICTA_FORM_RECIPIENT') || INVICTA_FORM_RECIPIENT === ''
    ) {
        logHomeFormDebug('missing_email_config', [
            'has_api_key' => defined('ZEPTO_API_KEY') && ZEPTO_API_KEY !== '',
            'has_from_address' => defined('ZEPTO_FROM_ADDRESS') && ZEPTO_FROM_ADDRESS !== '',
            'has_bounce_address' => defined('ZEPTO_BOUNCE_ADDRESS') && ZEPTO_BOUNCE_ADDRESS !== '',
            'has_recipient' => defined('INVICTA_FORM_RECIPIENT') && INVICTA_FORM_RECIPIENT !== '',
        ]);

        return false;
    }

    if (!function_exists('curl_init')) {
        logHomeFormDebug('curl_missing');
        return false;
    }

    $subjectPrefix = $submission['form_kind'] === 'quick'
        ? 'New Invicta Quick Inspection Request'
        : 'New Invicta Roof Inspection Request';

    $subject = $subjectPrefix . ' - ' . $submission['full_name'];

    $payload = [
        'from' => [
            'address' => ZEPTO_FROM_ADDRESS,
            'name' => defined('ZEPTO_FROM_NAME') ? ZEPTO_FROM_NAME : 'Invicta Roofing Website',
        ],
        'to' => [
            [
                'email_address' => [
                    'address' => INVICTA_FORM_RECIPIENT,
                    'name' => defined('INVICTA_FORM_RECIPIENT_NAME') ? INVICTA_FORM_RECIPIENT_NAME : 'Invicta Roofing',
                ],
            ],
        ],
        'subject' => $subject,
        'htmlbody' => buildHomeEmailHtml($submission),
        'textbody' => buildHomeEmailText($submission),
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
        logHomeFormDebug('zeptomail_error', [
            'http_code' => $httpCode,
            'curl_error' => $curlError,
            'response' => $response,
        ]);

        return false;
    }

    logHomeFormDebug('email_sent', [
        'http_code' => $httpCode,
        'recipient' => INVICTA_FORM_RECIPIENT,
        'form_kind' => $submission['form_kind'],
    ]);

    return true;
}

function buildHomeEmailHtml(array $submission): string
{
    $name = emailEscape($submission['full_name']);
    $phone = emailEscape($submission['phone']);
    $address = emailEscape($submission['property_address']);
    $need = emailEscape($submission['roofing_need']);
    $submittedAt = emailEscape($submission['submitted_at']);
    $formLabel = emailEscape($submission['form_label']);

    $addressDisplay = $address !== '' ? $address : 'Not provided';

    $rows =
        homeEmailFieldRow('Full Name', $submission['full_name']) .
        homeEmailFieldRow('Phone Number', $submission['phone']) .
        homeEmailFieldRow('Property Address', $submission['property_address']) .
        homeEmailFieldRow('Roofing Need', $submission['roofing_need']) .
        homeEmailFieldRow('How They Heard About Invicta', $submission['heard_about']) .
        homeEmailFieldRow('Notes', $submission['message']);

    return <<<HTML
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Invicta Roofing Lead</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6; font-family:Arial, Helvetica, sans-serif; color:#111827;">
    <div style="display:none; max-height:0; overflow:hidden; opacity:0;">
        New roofing lead from {$name}. Phone: {$phone}. Need: {$need}.
    </div>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6; margin:0; padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px; background:#ffffff; border-radius:18px; overflow:hidden; border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:#4f5962; padding:26px 30px;">
                            <div style="font-size:13px; line-height:18px; color:#f9fafb; text-transform:uppercase; letter-spacing:.08em; font-weight:700;">
                                Invicta Roofing Website
                            </div>
                            <h1 style="margin:8px 0 0; font-size:26px; line-height:32px; color:#ffffff; font-weight:800;">
                                New Roofing Lead
                            </h1>
                            <p style="margin:8px 0 0; font-size:15px; line-height:22px; color:#e5e7eb;">
                                A visitor submitted the {$formLabel}.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:26px 30px 12px;">
                            <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:14px; padding:18px;">
                                <div style="font-size:12px; line-height:16px; color:#6b7280; text-transform:uppercase; letter-spacing:.04em; font-weight:700;">
                                    Quick Contact
                                </div>
                                <div style="font-size:22px; line-height:28px; color:#111827; font-weight:800; margin-top:4px;">
                                    {$name}
                                </div>
                                <div style="font-size:15px; line-height:24px; color:#374151; margin-top:8px;">
                                    <strong>Phone:</strong> {$phone}<br>
                                    <strong>Need:</strong> {$need}<br>
                                    <strong>Address:</strong> {$addressDisplay}
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:12px 30px 26px;">
                            <h2 style="margin:0 0 8px; font-size:18px; line-height:24px; color:#111827;">
                                Submission Details
                            </h2>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                {$rows}
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f9fafb; padding:18px 30px; border-top:1px solid #e5e7eb;">
                            <p style="margin:0; font-size:13px; line-height:20px; color:#6b7280;">
                                Submitted at {$submittedAt}<br>
                                Source: Invicta Roofing homepage
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
}

function buildHomeEmailText(array $submission): string
{
    return <<<TEXT
New Invicta Roofing Lead

Form:
{$submission['form_label']}

Contact:
Name: {$submission['full_name']}
Phone: {$submission['phone']}

Request:
Roofing Need: {$submission['roofing_need']}
Property Address: {$submission['property_address']}
How They Heard About Invicta: {$submission['heard_about']}
Notes: {$submission['message']}

Submitted At:
{$submission['submitted_at']}

Technical:
Source: {$submission['source']}
Form Type: {$submission['form_type']}
IP Address: {$submission['ip_address']}
User Agent: {$submission['user_agent']}
TEXT;
}

function homeEmailFieldRow(string $label, string $value): string
{
    $label = emailEscape($label);
    $value = $value !== '' ? emailEscape($value) : 'Not provided';

    return <<<HTML
<tr>
    <td style="padding:12px 0; border-bottom:1px solid #e5e7eb;">
        <div style="font-size:12px; line-height:16px; color:#6b7280; text-transform:uppercase; letter-spacing:.04em; font-weight:700;">{$label}</div>
        <div style="font-size:16px; line-height:24px; color:#111827; font-weight:600; margin-top:3px;">{$value}</div>
    </td>
</tr>
HTML;
}

function validateSignedHomeFormToken(string $token): bool
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

    return $issuedAt > 0 && (time() - $issuedAt) <= $maxAgeSeconds;
}

function passesHomeFormRateLimit(string $formKind): bool
{
    $limitWindowSeconds = defined('FORM_RATE_LIMIT_WINDOW_SECONDS')
        ? (int) FORM_RATE_LIMIT_WINDOW_SECONDS
        : 60;

    $maxSubmissions = defined('FORM_RATE_LIMIT_MAX_SUBMISSIONS')
        ? (int) FORM_RATE_LIMIT_MAX_SUBMISSIONS
        : 3;

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    $logDir = homeFormLogDir();

    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    protectHomeLogDirectory($logDir);

    $rateFile = $logDir . '/home-form-rate-limit.json';

    $data = [];

    if (file_exists($rateFile)) {
        $decoded = json_decode((string) file_get_contents($rateFile), true);
        $data = is_array($decoded) ? $decoded : [];
    }

    $rateKey = $formKind . ':' . $ip;
    $now = time();

    foreach ($data as $storedKey => $timestamps) {
        if (!is_array($timestamps)) {
            unset($data[$storedKey]);
            continue;
        }

        $data[$storedKey] = array_values(array_filter(
            $timestamps,
            fn ($timestamp) => is_int($timestamp) && ($now - $timestamp) <= $limitWindowSeconds
        ));

        if (empty($data[$storedKey])) {
            unset($data[$storedKey]);
        }
    }

    $data[$rateKey] = $data[$rateKey] ?? [];

    if (count($data[$rateKey]) >= $maxSubmissions) {
        file_put_contents($rateFile, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
        return false;
    }

    $data[$rateKey][] = $now;

    file_put_contents($rateFile, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);

    return true;
}

function saveHomeFormSubmission(array $data): void
{
    $logDir = homeFormLogDir();

    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    protectHomeLogDirectory($logDir);

    file_put_contents(
        $logDir . '/home-form-submissions.jsonl',
        json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

function logHomeFormDebug(string $event, array $context = []): void
{
    if (!defined('FORM_DEBUG') || FORM_DEBUG !== true) {
        return;
    }

    $logDir = homeFormLogDir();

    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    protectHomeLogDirectory($logDir);

    $payload = [
        'logged_at' => date('c'),
        'event' => $event,
        'context' => $context,
    ];

    file_put_contents(
        $logDir . '/home-form-debug.jsonl',
        json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

function protectHomeLogDirectory(string $logDir): void
{
    $htaccessPath = rtrim($logDir, '/\\') . '/.htaccess';

    if (!file_exists($htaccessPath)) {
        file_put_contents(
            $htaccessPath,
            "Require all denied\nDeny from all\n",
            LOCK_EX
        );
    }
}

function homeFormLogDir(): string
{
    return defined('FORM_LOG_DIR')
        ? FORM_LOG_DIR
        : dirname(__DIR__) . '/storage/private';
}

function redirectHomeForm(string $formKind, string $status, string $reason = ''): void
{
    $debug = defined('FORM_DEBUG') && FORM_DEBUG === true;

    $query = [
        'form' => $formKind,
        'status' => $status,
    ];

    if ($debug && $reason !== '') {
        $query['debug_reason'] = $reason;
    }

    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $scriptDir = str_replace('\\', '/', dirname($scriptName));

    if ($scriptDir === '/' || $scriptDir === '.') {
        $scriptDir = '';
    }

    $anchor = $formKind === 'detailed' ? 'detailed-inspection-form' : 'quick-inspection-form';
    $redirectPath = rtrim($scriptDir, '/') . '/index.php';

    header('Location: ' . $redirectPath . '?' . http_build_query($query) . '#' . $anchor, true, 303);
    exit;
}

function postValue(string $key, string $default = ''): string
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
}

function cleanText(string $value): string
{
    $value = trim($value);
    $value = strip_tags($value);
    $value = preg_replace('/\s+/', ' ', $value) ?? $value;

    return $value;
}

function textLength(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
}

function isValidPhone(string $phone): bool
{
    return preg_match('/^[0-9+\-\s().]{7,30}$/', $phone) === 1;
}

function isAllowedRoofingService(string $service): bool
{
    return in_array($service, [
        'Roof Inspections',
        'Roof Replacements',
        'Insurance Support',
        'Repairs & Maintenance',
    ], true);
}

function emailEscape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}