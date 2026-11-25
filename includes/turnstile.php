<?php

declare(strict_types=1);

function verifyInvictaTurnstile(string $expectedAction): bool
{
    $token = $_POST['cf-turnstile-response'] ?? null;

    error_log(
    '[Invicta Turnstile] Secret ' .
    ((defined('TURNSTILE_SECRET_KEY') && TURNSTILE_SECRET_KEY !== '')
        ? 'configured'
        : 'MISSING')
);

    if (!is_string($token) || $token === '' || strlen($token) > 2048
        || TURNSTILE_SECRET_KEY === '' || !function_exists('curl_init')) {
        return false;
    }

    $request = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');

    if ($request === false) {
        return false;
    }

    curl_setopt_array($request, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'secret' => TURNSTILE_SECRET_KEY,
            'response' => $token,
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 3,
        CURLOPT_TIMEOUT => 8,
    ]);

    $body = curl_exec($request);
    $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
    curl_close($request);

    if ($status !== 200 || !is_string($body)) {
        return false;
    }

    $result = json_decode($body, true);

    return ($result['success'] ?? false) === true
        && ($result['action'] ?? '') === $expectedAction
        && in_array($result['hostname'] ?? '', TURNSTILE_ALLOWED_HOSTNAMES, true);
}