<?php

define('FORM_DEBUG', true);
define('FORM_DELIVERY_MODE', 'email');

define('FORM_TOKEN_SECRET', 'your-long-random-secret-here');
define('FORM_TOKEN_MAX_AGE_SECONDS', 7200);

define('FORM_RATE_LIMIT_WINDOW_SECONDS', 60);
define('FORM_RATE_LIMIT_MAX_SUBMISSIONS', 3);

/**
 * Keep this false unless Turnstile is already configured
 * for the demo domain.
 */
define('TURNSTILE_ENABLED', false);
define('TURNSTILE_SITE_KEY', '');
define('TURNSTILE_SECRET_KEY', '');

/**
 * ZeptoMail testing.
 */
define('ZEPTO_API_KEY', 'YOUR_ZEPTOMAIL_API_KEY');
define('ZEPTO_FROM_ADDRESS', 'services@ervotechep.com');
define('ZEPTO_FROM_NAME', 'Invicta Roofing');
define('ZEPTO_BOUNCE_ADDRESS', 'bounce@bounce-zem.ervotechep.com');

/**
 * Testing recipient.
 */
define('INVICTA_FORM_RECIPIENT', 'miguel@ervotechep.com');

define('FORM_LOG_DIR', dirname(__DIR__) . '/storage/private');
define('FORM_LOG_FILE', FORM_LOG_DIR . '/giveaway-submissions.jsonl');