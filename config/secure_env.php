<?php

define('FORM_DEBUG', true);
define('FORM_DELIVERY_MODE', 'email');

/**
 * This is only for signing/validating the form token.
 * Do NOT use your ZeptoMail key here.
 */
define('FORM_TOKEN_SECRET', '9f4d8b7f0d9a1c6e8a0d6f3b2c91a7e54c2b4d8e7f3a9b0c6d1e2f8a4b7c9d0e');
define('FORM_TOKEN_MAX_AGE_SECONDS', 7200);

define('FORM_RATE_LIMIT_WINDOW_SECONDS', 60);
define('FORM_RATE_LIMIT_MAX_SUBMISSIONS', 3);

/**
 * Keep this false until Turnstile is configured for the live/demo domain.
 */
define('TURNSTILE_ENABLED', false);
define('TURNSTILE_SITE_KEY', '');
define('TURNSTILE_SECRET_KEY', '');

/**
 * ZeptoMail testing.
 *
 * IMPORTANT:
 * ZEPTO_API_KEY should be the Send Mail Token only.
 * Do NOT include "Zoho-enczapikey" here because the processor adds that prefix.
 */
define('ZEPTO_API_KEY', 'wSsVR61xqEb0Dfx5nDelIr1qn1VSAFzxQUQrjFSlv3b7Hf+Xp8czlRXPAAagSKQaQzRrEmcapb59y0pR1GYMjogly1tWXiiF9mqRe1U4J3x17qnvhDzKV25UlhaOJIsLxAVvnGNoEMkr+g==');
define('ZEPTO_FROM_ADDRESS', 'info@invictaroofs.com');
define('ZEPTO_FROM_NAME', 'Invicta Roofing');
define('ZEPTO_BOUNCE_ADDRESS', 'bounce@bounce-zem.ervotechep.com');

/**
 * Testing recipient.
 */
define('INVICTA_FORM_RECIPIENT', 'miguel@ervotechep.com');

/**
 * Logs.
 */
define('FORM_LOG_DIR', realpath(__DIR__ . '/..') . '/storage/private');
define('FORM_LOG_FILE', FORM_LOG_DIR . '/giveaway-submissions.jsonl');