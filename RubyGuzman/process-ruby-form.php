<?php
declare(strict_types=1);

// This folder is intended to sit directly inside the existing website root.
// Secrets stay in the site's existing secure configuration file.
$siteRoot = dirname(__DIR__);
require_once $siteRoot . '/config/secure_env.php';

function rubyPost(string $key): string
{
    return trim((string) ($_POST[$key] ?? ''));
}

function rubyClean(string $value): string
{
    $value = strip_tags(trim($value));
    return preg_replace('/\s+/', ' ', $value) ?? $value;
}

function rubyRedirect(string $status): never
{
    $folder = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/RubyGuzman/process-ruby-form.php')), '/');
    if ($folder === '') {
        $folder = '/';
    }
    header('Location: ' . $folder . '/?status=' . rawurlencode($status) . '#inspection', true, 303);
    exit;
}

function rubyTokenIsValid(string $token): bool
{
    if ($token === '' || !defined('FORM_TOKEN_SECRET') || FORM_TOKEN_SECRET === '') {
        return false;
    }
    $parts = explode('.', $token, 2);
    if (count($parts) !== 2) {
        return false;
    }
    [$encoded, $signature] = $parts;
    $expected = hash_hmac('sha256', $encoded, FORM_TOKEN_SECRET);
    if (!hash_equals($expected, $signature)) {
        return false;
    }
    $base64 = strtr($encoded, '-_', '+/');
    $base64 .= str_repeat('=', (4 - strlen($base64) % 4) % 4);
    $json = base64_decode($base64, true);
    $payload = $json === false ? null : json_decode($json, true);
    if (!is_array($payload) || empty($payload['ts'])) {
        return false;
    }
    $issuedAt = (int) $payload['ts'];
    return $issuedAt > 0 && $issuedAt <= time() && (time() - $issuedAt) <= 7200;
}

function rubyRateLimitPassed(): bool
{
    $directory = dirname(__DIR__) . '/storage/private';
    if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
        return false;
    }
    $file = $directory . '/ruby-form-rate-limit.json';
    $key = hash('sha256', (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
    $now = time();
    $handle = fopen($file, 'c+');
    if ($handle === false) {
        return false;
    }
    flock($handle, LOCK_EX);
    $contents = stream_get_contents($handle);
    $data = json_decode($contents ?: '{}', true);
    $data = is_array($data) ? $data : [];
    $previous = $data[$key] ?? [];
    $timestamps = array_values(array_filter(is_array($previous) ? $previous : [], static fn ($stamp) => is_int($stamp) && $now - $stamp < 60));
    if (count($timestamps) >= 3) {
        ftruncate($handle, 0);
        rewind($handle);
        fwrite($handle, json_encode($data, JSON_UNESCAPED_SLASHES));
        flock($handle, LOCK_UN);
        fclose($handle);
        return false;
    }
    $timestamps[] = $now;
    $data[$key] = $timestamps;
    ftruncate($handle, 0);
    rewind($handle);
    fwrite($handle, json_encode($data, JSON_UNESCAPED_SLASHES));
    fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);
    return true;
}

function rubyEmailEscape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function rubySendEmail(array $lead): bool
{
    if (!defined('ZEPTO_API_KEY') || ZEPTO_API_KEY === '' ||
        !defined('ZEPTO_FROM_ADDRESS') || ZEPTO_FROM_ADDRESS === '' ||
        !defined('ZEPTO_BOUNCE_ADDRESS') || ZEPTO_BOUNCE_ADDRESS === '' ||
        !function_exists('curl_init')) {
        return false;
    }

    // Set AMBASSADOR_FORM_RECIPIENT in secure_env.php to override this address.
    // The default below matches the address supplied for this page.
    $recipient = defined('AMBASSADOR_FORM_RECIPIENT')
        ? (string) AMBASSADOR_FORM_RECIPIENT
        : 'ervotech.ep@gmail.com';

    $rows = '';
    foreach ([
        'Homeowner' => $lead['full_name'],
        'Email' => $lead['email'],
        'Phone' => $lead['phone'],
        'Property address' => $lead['property_address'],
        'Requested service' => $lead['roofing_need'],
        'Notes' => $lead['message'] ?: 'None provided',
        'Lead source' => $lead['lead_source'],
        'Ambassador' => $lead['ambassador_name'],
        'Ambassador ID' => $lead['ambassador_id'],
        'Referral URL' => $lead['referral_url'],
        'Submitted at' => $lead['submitted_at'],
    ] as $label => $value) {
        $rows .= '<tr><td style="padding:11px 0;border-bottom:1px solid #e5e7eb">'
            . '<div style="font:700 11px Arial;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">'
            . rubyEmailEscape($label) . '</div><div style="font:15px/1.5 Arial;color:#111827;margin-top:4px">'
            . nl2br(rubyEmailEscape((string) $value)) . '</div></td></tr>';
    }

    $payload = [
        'from' => [
            'address' => ZEPTO_FROM_ADDRESS,
            'name' => defined('ZEPTO_FROM_NAME') ? ZEPTO_FROM_NAME : 'Invicta Roofing',
        ],
        'to' => [[ 'email_address' => [
            'address' => $recipient,
            'name' => defined('INVICTA_FORM_RECIPIENT_NAME') ? INVICTA_FORM_RECIPIENT_NAME : 'Invicta Roofing',
        ]]],
        'subject' => 'Ruby Guzman referral - roof inspection request from ' . $lead['full_name'],
        'htmlbody' => '<!doctype html><html><body style="margin:0;background:#f3f4f6;font-family:Arial,sans-serif;color:#111827;padding:24px">'
            . '<div style="max-width:680px;margin:auto;background:#fff;border-radius:14px;overflow:hidden;border:1px solid #e5e7eb">'
            . '<div style="padding:22px 26px;background:#252b31;color:#fff"><div style="font-size:12px;letter-spacing:.12em;color:#f4a4c2">INVICTA ROOFING · BRAND AMBASSADOR</div>'
            . '<h1 style="font-size:24px;margin:8px 0 0">New referral from Ruby Guzman</h1></div>'
            . '<div style="padding:10px 26px 24px"><table width="100%" cellspacing="0" cellpadding="0">' . $rows . '</table></div></div></body></html>',
        'textbody' => "New Invicta Roofing referral from Ruby Guzman\n\n"
            . "Homeowner: {$lead['full_name']}\nEmail: {$lead['email']}\nPhone: {$lead['phone']}\n"
            . "Property address: {$lead['property_address']}\nRequested service: {$lead['roofing_need']}\n"
            . "Notes: {$lead['message']}\nLead source: {$lead['lead_source']}\n"
            . "Ambassador: {$lead['ambassador_name']}\nAmbassador ID: {$lead['ambassador_id']}\n"
            . "Referral URL: {$lead['referral_url']}\nSubmitted at: {$lead['submitted_at']}",
        'bounce_address' => ZEPTO_BOUNCE_ADDRESS,
    ];

    $curl = curl_init('https://api.zeptomail.com/v1.1/email');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Zoho-enczapikey ' . ZEPTO_API_KEY,
        ],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        CURLOPT_TIMEOUT => 15,
    ]);
    $response = curl_exec($curl);
    $code = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return $response !== false && $code >= 200 && $code < 300;
}

function rubySaveLead(array $lead): void
{
    $directory = dirname(__DIR__) . '/storage/private';
    if (!is_dir($directory)) {
        mkdir($directory, 0750, true);
    }
    $denyFile = $directory . '/.htaccess';
    if (!file_exists($denyFile)) {
        file_put_contents($denyFile, "Require all denied\nDeny from all\n", LOCK_EX);
    }
    file_put_contents(
        $directory . '/ruby-ambassador-leads.jsonl',
        json_encode($lead, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    rubyRedirect('error');
}
if (rubyPost('website_verification_code') !== '' || !rubyTokenIsValid(rubyPost('form_token')) || !rubyRateLimitPassed()) {
    rubyRedirect('error');
}

$name = rubyClean(rubyPost('full_name'));
$email = strtolower(rubyClean(rubyPost('email')));
$phone = rubyClean(rubyPost('phone'));
$address = rubyClean(rubyPost('property_address'));
$need = rubyClean(rubyPost('roofing_need'));
$message = rubyClean(rubyPost('message'));
$allowedNeeds = ['Roof Inspections', 'Roof Replacements', 'Insurance Support', 'Repairs & Maintenance'];
$nameLength = function_exists('mb_strlen') ? mb_strlen($name) : strlen($name);
$addressLength = function_exists('mb_strlen') ? mb_strlen($address) : strlen($address);
$messageLength = function_exists('mb_strlen') ? mb_strlen($message) : strlen($message);

if ($name === '' || $nameLength > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254 ||
    !preg_match('/^[0-9+\-\s().]{7,30}$/', $phone) || $address === '' || $addressLength > 220 ||
    !in_array($need, $allowedNeeds, true) || $messageLength > 1500) {
    rubyRedirect('error');
}

$lead = [
    'submitted_at' => date('c'),
    'form_type' => 'ruby_ambassador_inspection',
    'source' => 'Brand Ambassador',
    'lead_source' => 'Brand Ambassador',
    'ambassador_name' => 'Ruby Guzman',
    'ambassador_id' => 'AMB001',
    'referral_url' => (isset($_SERVER['HTTP_HOST']) ? 'https://' . $_SERVER['HTTP_HOST'] : 'https://invictaroofs.com') . '/RubyGuzman/',
    'full_name' => $name,
    'email' => $email,
    'phone' => $phone,
    'property_address' => $address,
    'roofing_need' => $need,
    'message' => $message,
    'notification_status' => 'pending',
];

$sent = rubySendEmail($lead);
$lead['notification_status'] = $sent ? 'sent' : 'failed';
rubySaveLead($lead);
rubyRedirect($sent ? 'success' : 'error');
