<?php
declare(strict_types=1);

// This page is designed to live at /RubyGuzman/ under the Invicta site root.
// Keep the site's secret configuration outside this public folder.
$siteRoot = dirname(__DIR__);
require_once $siteRoot . '/config/secure_env.php';

$formTokenSecret = defined('FORM_TOKEN_SECRET') ? (string) FORM_TOKEN_SECRET : '';
$formToken = '';
if ($formTokenSecret !== '') {
    $payload = rtrim(strtr(base64_encode(json_encode(['ts' => time()], JSON_THROW_ON_ERROR)), '+/', '-_'), '=');
    $formToken = $payload . '.' . hash_hmac('sha256', $payload, $formTokenSecret);
}

$status = (string) ($_GET['status'] ?? '');
$notice = match ($status) {
    'success' => ['success', 'Request received. Invicta Roofing will be in touch soon.'],
    'error' => ['error', 'We could not send your request. Please call 915-630-1349 or try again.'],
    default => null,
};
$pageUrl = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'invictaroofs.com') . strtok($_SERVER['REQUEST_URI'] ?? '/RubyGuzman/', '?');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#22272d">
  <title>Ruby Guzman | Team Invicta</title>
  <meta name="description" content="Connect with Invicta Roofing through Ruby Guzman's Team Invicta page and request a roof inspection.">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="site-header">
    <a class="brand" href="https://invictaroofs.com/" aria-label="Invicta Roofing home">
      <span class="brand-mark" aria-hidden="true">I</span>
      <span class="brand-name">INVICTA <small>ROOFING</small></span>
    </a>
    <a class="header-call" href="tel:+19156301349"><span>Talk to our team</span><strong>915-630-1349</strong></a>
  </header>

  <main>
    <section class="hero">
      <div class="hero-noise" aria-hidden="true"></div>
      <div class="hero-inner">
        <div class="hero-copy">
          <p class="eyebrow"><span class="eyebrow-line"></span> Team Invicta · Ambassador referral</p>
          <p class="draft-label">YOU’VE BEEN REFERRED</p>
          <h1>Good people.<br><span>Undefeated homes.</span></h1>
          <p class="hero-intro">Ruby Guzman trusts Invicta Roofing. Now her community has a direct line to a local team that puts honest guidance and quality work first.</p>
          <a class="button button-primary" href="#inspection">Request a roof inspection <span aria-hidden="true">→</span></a>
          <div class="hero-proof"><span class="proof-star">✦</span><span>Locally serving El Paso homeowners</span></div>
        </div>

        <aside class="player-card" aria-label="Ruby Guzman ambassador profile">
          <div class="card-topline"><span>TEAM INVICTA</span><span class="card-season">AMBASSADOR · 001</span></div>
          <div class="photo-placeholder" role="img" aria-label="Ruby Guzman's photo placeholder">
            <div class="placeholder-frame"><span class="photo-icon" aria-hidden="true">＋</span><span>RUBY’S PHOTO</span><small>Replace with client-approved image</small></div>
          </div>
          <div class="card-bottom">
            <div><span class="card-kicker">THE UNDEFEATED</span><h2>Ruby Guzman</h2></div>
            <span class="card-number">001</span>
          </div>
          <div class="card-accent"></div>
        </aside>
      </div>
      <div class="hero-footer"><span>INVITED BY RUBY</span><span>BUILT FOR EL PASO</span><span>BUILT TO STAY UNDEFEATED</span></div>
    </section>

    <section class="trust-section">
      <div class="trust-inner">
        <div class="trust-mark" aria-hidden="true">“</div>
        <div class="trust-copy">
          <p class="eyebrow eyebrow-dark">A note from Ruby</p>
          <h2>Why Ruby trusts Invicta Roofing</h2>
          <p class="quote-placeholder">[Client-provided statement goes here. For example: “I trust Invicta because they explain the work clearly, treat people with respect, and stand behind the quality of their roofs.”]</p>
          <span class="signature">Ruby Guzman <small>Team Invicta Ambassador</small></span>
        </div>
        <div class="trust-aside"><span class="aside-number">01</span><span>LOCAL<br>TRUST</span><i></i><span>REAL<br>PEOPLE</span></div>
      </div>
    </section>

    <section class="form-section" id="inspection">
      <div class="form-layout">
        <div class="form-intro">
          <p class="eyebrow"><span class="eyebrow-line"></span> Start with a clear answer</p>
          <h2>Let’s take a look<br>at your roof.</h2>
          <p>Request a free roof inspection. Invicta will review your request and contact you to coordinate a convenient time.</p>
          <ul class="form-benefits">
            <li><span>01</span> No-cost roof inspection</li>
            <li><span>02</span> Clear photos and recommendations</li>
            <li><span>03</span> No-pressure next steps</li>
          </ul>
          <div class="phone-note"><span>Prefer to call?</span><a href="tel:+19156301349">915-630-1349</a></div>
        </div>

        <div class="form-card">
          <div class="form-card-heading"><div><span class="form-step">INSPECTION REQUEST</span><h3>Tell us a little about your home.</h3></div><span class="form-badge">FREE</span></div>

          <?php if ($notice !== null): ?>
            <div class="form-notice form-notice-<?= htmlspecialchars($notice[0], ENT_QUOTES, 'UTF-8') ?>" role="status"><?= htmlspecialchars($notice[1], ENT_QUOTES, 'UTF-8') ?></div>
          <?php endif; ?>

          <?php if ($formToken === ''): ?>
            <div class="form-notice form-notice-error" role="alert">The request form is temporarily unavailable. Please call 915-630-1349.</div>
          <?php else: ?>
          <form action="process-ruby-form.php" method="post" class="lead-form">
            <input type="hidden" name="form_token" value="<?= htmlspecialchars($formToken, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="form_type" value="ruby_ambassador_inspection">
            <input type="hidden" name="ambassador_id" value="AMB001">
            <input type="hidden" name="ambassador_name" value="Ruby Guzman">
            <input type="hidden" name="lead_source" value="Brand Ambassador">
            <input type="hidden" name="referral_url" value="<?= htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8') ?>">
            <div class="hp-wrap" aria-hidden="true"><label>Leave blank<input type="text" name="website_verification_code" tabindex="-1" autocomplete="off"></label></div>

            <div class="field-grid">
              <label>Full name<input type="text" name="full_name" autocomplete="name" maxlength="120" required></label>
              <label>Phone number<input type="tel" name="phone" autocomplete="tel" maxlength="30" required></label>
            </div>
            <label>Email address<input type="email" name="email" autocomplete="email" maxlength="254" required></label>
            <label>Property address<input type="text" name="property_address" autocomplete="street-address" maxlength="220" placeholder="Street address, El Paso, TX" required></label>
            <label>What can we help with?
              <select name="roofing_need" required>
                <option value="">Choose a roofing need</option>
                <option value="Roof Inspections">Roof inspection</option>
                <option value="Roof Replacements">Roof replacement</option>
                <option value="Insurance Support">Insurance support</option>
                <option value="Repairs &amp; Maintenance">Repairs &amp; maintenance</option>
              </select>
            </label>
            <label>Anything else we should know? <span class="optional">Optional</span><textarea name="message" rows="3" maxlength="1500" placeholder="Share any visible damage, leaks, or questions."></textarea></label>
            <button class="button button-submit" type="submit">Request my free inspection <span aria-hidden="true">→</span></button>
            <p class="privacy-note">By submitting, you agree Invicta Roofing may contact you about this inspection request.</p>
          </form>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <a class="brand brand-footer" href="https://invictaroofs.com/" aria-label="Invicta Roofing home"><span class="brand-mark" aria-hidden="true">I</span><span class="brand-name">INVICTA <small>ROOFING</small></span></a>
    <p>Be protected. Be Invicta.</p>
    <a href="tel:+19156301349">915-630-1349</a>
    <small>© <?= date('Y') ?> Invicta Roofing · El Paso, Texas</small>
  </footer>
</body>
</html>
