<?php

declare(strict_types=1);

function createSignedFormToken(): string
{
    if (!defined('FORM_TOKEN_SECRET') || FORM_TOKEN_SECRET === '') {
        return '';
    }

    $payload = [
        'ts' => time(),
        'nonce' => bin2hex(random_bytes(16)),
    ];

    $payloadEncoded = base64_encode(json_encode($payload, JSON_UNESCAPED_SLASHES));
    $signature = hash_hmac('sha256', $payloadEncoded, FORM_TOKEN_SECRET);

    return $payloadEncoded . '.' . $signature;
}

function homeFormStatusMessage(): array
{
    $status = $_GET['status'] ?? '';
    $debugReason = $_GET['debug_reason'] ?? '';

    if ($status === 'success') {
        return [
            'type' => 'success',
            'message' => 'Thank you. Your inspection request was received. Invicta Roofing will follow up soon.',
        ];
    }

    if ($status === 'error') {
        $message = match ($debugReason) {
            'missing_name' => 'Please enter your name.',
            'missing_phone' => 'Please enter your phone number.',
            'invalid_phone' => 'Please enter a valid phone number.',
            'missing_service' => 'Please select what you need help with.',
            'missing_address' => 'Please enter your property address.',
            'invalid_form_token' => 'This form session expired. Please refresh the page and try again.',
            'rate_limited' => 'Too many requests were submitted. Please wait a few minutes and try again.',
            'email_send_failed' => 'Your request could not be sent right now. Please call Invicta Roofing at 915-630-1349.',
            default => 'Please check the form and try again.',
        };

        return [
            'type' => 'error',
            'message' => $message,
        ];
    }

    return [
        'type' => '',
        'message' => '',
    ];
}