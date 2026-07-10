<?php

/**
 * Copy this file to secure_env.php and place it outside public_html when possible.
 * Example preferred location on shared hosting:
 * /home/ACCOUNT/config/secure_env.php
 */

define('FORM_DEBUG', true); // Set to false after testing.

define('FORM_TOKEN_SECRET', 'replace-this-with-a-long-random-string-at-least-32-characters');

define('TURNSTILE_SITE_KEY', 'your-cloudflare-turnstile-site-key');
define('TURNSTILE_SECRET_KEY', 'your-cloudflare-turnstile-secret-key');

define('ZEPTO_API_KEY', 'Zoho-enczapikey your-zeptomail-api-key-here');

define('ZEPTO_FROM_ADDRESS', 'services@invictaroofs.com');
define('ZEPTO_FROM_NAME', 'Invicta Roofing');
define('ZEPTO_BOUNCE_ADDRESS', 'bounce@bounce-zem.invictaroofs.com');

define('INVICTA_FORM_RECIPIENT', 'support@invictaroofs.com');
define('INVICTA_FORM_RECIPIENT_NAME', 'Invicta Roofing');
