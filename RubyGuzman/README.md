# Ruby Guzman ambassador referral page

This folder is intended to be placed directly inside the existing Invicta website document root as `RubyGuzman/`, giving it the path `/RubyGuzman/`.

## Files

- `index.php` — responsive referral landing page and inspection form
- `style.css` — page styling, stored in this same folder
- `process-ruby-form.php` — validates, emails, and logs the referral request

## Existing-site requirements

The folder expects the site's existing `config/secure_env.php` at the website root. It uses the existing `FORM_TOKEN_SECRET`, `ZEPTO_API_KEY`, `ZEPTO_FROM_ADDRESS`, and `ZEPTO_BOUNCE_ADDRESS` settings. Set `AMBASSADOR_FORM_RECIPIENT` in that secure config to control the notification mailbox; if it is not set, the form uses the address entered for this page.

Submissions are emailed through ZeptoMail and also appended to `storage/private/ruby-ambassador-leads.jsonl`. Confirm that this storage path is writable by PHP and that direct web access to `/storage/private/` is denied by the server configuration.

## Client content to replace

Replace the photo placeholder and draft testimonial in `index.php` with Ruby's approved image and statement. The current copy is intentionally marked as placeholder content. The shareable page name is Ruby Guzman and the fixed ambassador ID is `AMB001`.

The homeowner email field is included because the earlier referral requirements listed it. Update the contact email recipient if the supplied address was mistyped.
