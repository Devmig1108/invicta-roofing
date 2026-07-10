<?php

define('FORM_DEBUG', true);
define('FORM_DELIVERY_MODE', 'log');

define('FORM_TOKEN_SECRET', 'replace-this-with-a-long-random-demo-secret');
define('FORM_TOKEN_MAX_AGE_SECONDS', 7200);

define('FORM_RATE_LIMIT_WINDOW_SECONDS', 60);
define('FORM_RATE_LIMIT_MAX_SUBMISSIONS', 3);

define('TURNSTILE_ENABLED', false);
define('TURNSTILE_SITE_KEY', '');
define('TURNSTILE_SECRET_KEY', '');

define('ZEPTO_API_KEY', '');
define('ZEPTO_FROM_ADDRESS', '');
define('ZEPTO_FROM_NAME', 'Invicta Roofing');
define('ZEPTO_BOUNCE_ADDRESS', '');
define('INVICTA_FORM_RECIPIENT', '');

define('FORM_LOG_DIR', dirname(__DIR__) . '/storage/private');
define('FORM_LOG_FILE', FORM_LOG_DIR . '/giveaway-submissions.jsonl');