<?php
$formDebug = defined('FORM_DEBUG') ? (bool) FORM_DEBUG : false;

function loadSecureEnvForGiveawayPage(): void
{
    static $loaded = false;

    if ($loaded) {
        return;
    }

    $possiblePaths = [
        __DIR__ . '/../config/secure_env.php',
        __DIR__ . '/../../config/secure_env.php',
        __DIR__ . '/config/secure_env.php',
        isset($_SERVER['DOCUMENT_ROOT'])
            ? rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/../config/secure_env.php'
            : null,
    ];

    foreach ($possiblePaths as $path) {
        if ($path && file_exists($path)) {
            require_once $path;
            $loaded = true;
            return;
        }
    }
}

loadSecureEnvForGiveawayPage();

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

function formStatusMessage(): array
{
    $status = $_GET['status'] ?? '';
    $debugReason = $_GET['debug_reason'] ?? '';

    if ($status === 'success') {
        return [
            'type' => 'success',
            'message' => 'Thank you. Your inspection request was received. Invicta Roofing will follow up soon.',
        ];
    }

    if ($status === 'mail_error') {
        return [
            'type' => 'error',
            'message' => 'Your request could not be sent right now. Please call Invicta Roofing at 915-630-1349.',
        ];
    }

    if ($status === 'error') {
        $message = match ($debugReason) {
            'missing_required_fields' => 'Please complete all required fields before submitting.',
            'invalid_phone' => 'Please enter a valid phone number.',
            'invalid_email' => 'Please enter a valid email address.',
            'required_confirmations_missing' => 'Please confirm homeowner status, age, and agreement to the Official Rules.',
            'invalid_form_token' => 'This form session expired. Please refresh the page and try again.',
            'turnstile_failed' => 'Verification failed. Please try again.',
            'rate_limited' => 'Too many requests were submitted. Please wait a few minutes and try again.',
            default => 'Please check the form and try again.',
        };

        return [
            'type' => 'error',
            'message' => $message,
        ];
    }

    return ['type' => '', 'message' => ''];
}

$formStatus = formStatusMessage();
$formToken = createSignedFormToken();
$turnstileEnabled = defined('TURNSTILE_ENABLED') && TURNSTILE_ENABLED === true;
$turnstileSiteKey = ($turnstileEnabled && defined('TURNSTILE_SITE_KEY')) ? TURNSTILE_SITE_KEY : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Making Quinceañeras Great Again | Invicta Roofing Giveaway Drawing</title>
  <meta name="description" content="Invicta Roofing LLC is holding the Making Quinceañeras Great Again giveaway drawing. No purchase necessary. Open to Texas residential homeowners 18+. Entry Period: July 10, 2026 through January 10, 2027. Entries follow the Official Rules ticket structure. Full official rules at invictaroofs.com." />
  <link rel="canonical" href="https://invictaroofs.com/quinceanera-giveaway" />

  <meta property="og:title" content="Making Quinceañeras Great Again | Invicta Roofing" />
  <meta property="og:description" content="A free quinceañera package could be yours. No purchase necessary. Open to Texas residential homeowners 18+. Full official rules at invictaroofs.com." />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://invictaroofs.com/quinceanera-giveaway" />
  <meta property="og:image" content="https://invictaroofs.com/images/quinceanera-giveaway-og.jpg" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="quinceanera-giveaway.css" />

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Making Quinceañeras Great Again Giveaway Drawing",
    "url": "https://invictaroofs.com/quinceanera-giveaway",
    "description": "Invicta Roofing LLC is holding the Making Quinceañeras Great Again giveaway drawing. No purchase necessary. Open to Texas residential homeowners 18+. Entry Period: July 10, 2026 through January 10, 2027.",
    "publisher": {
      "@type": "RoofingContractor",
      "name": "Invicta Roofing LLC",
      "telephone": "+1-915-630-1349",
      "email": "support@invictaroofs.com",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "509 Giles Rd",
        "addressLocality": "El Paso",
        "addressRegion": "TX",
        "postalCode": "79915",
        "addressCountry": "US"
      }
    }
  }
  </script>
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>

  <header class="giveaway-header">
    <div class="announcement">
      <span>No purchase necessary</span>
      <span>Open to Texas residential homeowners, 18+</span>
      <span>Full official rules at invictaroofs.com</span>
    </div>

    <nav class="nav container" aria-label="Giveaway navigation">
      <a class="brand" href="/" aria-label="Invicta Roofing home">
        <img class="brand-logo" src="../images/logo_md_dark.svg" alt="Invicta Roofing" width="280" height="84" />
      </a>

      <div class="nav-menu">
        <a href="#ways-to-enter">Ways to Enter</a>
        <a href="#preview">Preview</a>
        <a href="#prize">Prize</a>
        <a href="#rules">Rules</a>
        <a href="#inspection-form">Schedule Inspection</a>
        <a href="#faq">FAQ</a>
      </div>


      <div class="nav-social" aria-label="Social media links">
        <a href="https://www.facebook.com/profile.php?id=61587098842468" target="_blank" rel="noopener" aria-label="Message Invicta Roofing on Facebook">Facebook</a>
        <a href="https://www.instagram.com/invictaroofing915/" target="_blank" rel="noopener" aria-label="Message Invicta Roofing on Instagram">Instagram</a>
      </div>

      <a class="nav-cta" href="tel:+19156301349">Call 915-630-1349</a>
    </nav>
  </header>

  <main id="main">
    <section class="giveaway-hero">
      <video class="hero-video" autoplay muted loop playsinline preload="metadata" aria-hidden="true">
        <source src="media/hero-video.mp4" type="video/mp4" />
      </video>
      <div class="hero-overlay" aria-hidden="true"></div>

      <div class="container hero-grid">
        <div class="hero-copy reveal">
          <p class="eyebrow">Making Quinceañeras Great Again</p>
          <h1>A $12,000 quinceañera package could be yours.</h1>
          <p class="hero-lead">A new yearly Invicta Roofing tradition starts here: one Texas residential homeowner could receive a quinceañera salon/venue package in El Paso for 2027.</p>

          <div class="hero-legal">
            <strong>No purchase necessary.</strong>
            <span>Open to Texas residential homeowners 18+. Entry period: July 10, 2026 – January 10, 2027.</span>
          </div>

          <div class="hero-actions">
            <a class="btn btn-primary" href="#inspection-form">Schedule My Free Inspection</a>
            <a class="btn btn-secondary" href="docs/official-rules.pdf" target="_blank" rel="noopener">Official Rules</a>
          </div>
        </div>

        <div class="hero-feature reveal" aria-label="Quinceañera giveaway campaign image">
          <img src="images/featured.jpeg" alt="Invicta Roofing quinceañera package giveaway campaign" />
          <div class="feature-badge feature-badge-top">2027 Giveaway</div>
          <div class="feature-badge feature-badge-bottom">Drawing date to be confirmed</div>
        </div>
      </div>
    </section>

    <section class="campaign-strip" aria-label="Campaign facts">
      <div class="container strip-grid">
        <div>
          <strong>$12,000</strong>
          <span>Stated retail value</span>
        </div>
        <div>
          <strong>Tickets</strong>
          <span>Earned by qualifying action</span>
        </div>
        <div>
          <strong>Free</strong>
          <span>Mail-in entry available</span>
        </div>
        <div>
          <strong>Live</strong>
          <span>Drawing on social media</span>
        </div>
      </div>
      <div class="container strip-action-row reveal">
        <a class="btn btn-primary" href="#inspection-form">Schedule My Free Inspection</a>
        <a class="btn btn-secondary" href="#mail-entry">Free Mail-In Entry Details</a>
      </div>
    </section>



    <section class="section campaign-preview" id="preview">
      <div class="container preview-grid">
        <div class="preview-copy reveal">
          <p class="eyebrow">A celebration worth sharing</p>
          <h2>Una noche inolvidable starts with one entry.</h2>
          <p>This giveaway drawing is about more than a venue package. It is about giving one Texas family the chance to celebrate a milestone surrounded by the people they love.</p>
          <p>Follow Invicta Roofing for campaign updates, drawing reminders, and official announcements as the Entry Period moves forward.</p>
          <div class="mini-cta-row">
            <a class="btn btn-dark" href="#inspection-form">Schedule My Free Inspection</a>
            <a class="btn btn-outline-dark" href="https://www.instagram.com/invictaroofing915/" target="_blank" rel="noopener">Follow on Instagram</a>
          </div>
        </div>

        <div class="preview-media reveal" aria-label="Quinceañera giveaway campaign media">
          <figure class="preview-image preview-image-large">
            <img src="images/campaign-moment-1.jpeg" alt="Quinceañera celebration detail for the Invicta Roofing giveaway drawing" />
            <figcaption>El sueño de la noche</figcaption>
          </figure>

          <figure class="preview-image preview-image-small">
            <img src="images/campaign-moment-2.jpeg" alt="Family celebration moment for the Invicta Roofing quinceañera giveaway drawing" />
            <figcaption>Make Quinceañeras Great Again!</figcaption>
          </figure>

          <div class="preview-video">
            <video controls preload="metadata" poster="images/featured.jpeg" aria-label="Quinceañera giveaway campaign video">
              <source src="media/campaign-preview2.mov" type="video/mp4" />
            </video>
          </div>
        </div>
      </div>
    </section>

    <section class="section ways" id="ways-to-enter">
      <div class="container">
        <div class="section-heading centered reveal">
          <p class="eyebrow">Ways to enter</p>
          <h2>Entries follow the official ticket structure.</h2>
          <p>No purchase necessary. Enter free by mail, or take any of the actions below — free inspection, insurance claim, maintenance, repair, coating, or replacement — to earn entries. Entries vary by service type; see Official Rules for the full entry structure.</p>
        </div>

        <div class="entry-grid">
          <article class="entry-card reveal" id="qualifying-action">
            <div class="entry-label">Qualifying action entries</div>
            <h3>Complete a qualifying action with Invicta Roofing.</h3>
            <p>During the Entry Period, eligible Texas residential homeowners automatically receive entries once a qualifying action is completed. Scheduling alone does not earn an entry unless otherwise stated in the Official Rules.</p>

            <div class="ticket-table" role="table" aria-label="Qualifying action entry ticket structure">
              <div class="ticket-row ticket-head" role="row">
                <span role="columnheader">Qualifying action</span>
                <span role="columnheader">Entries</span>
              </div>
              <div class="ticket-row" role="row">
                <span role="cell">Free roof inspection performed by Invicta</span>
                <strong role="cell">1</strong>
              </div>
              <div class="ticket-row" role="row">
                <span role="cell">Insurance claim filed with Invicta's assistance</span>
                <strong role="cell">1</strong>
              </div>
              <div class="ticket-row" role="row">
                <span role="cell">Maintenance service completed</span>
                <strong role="cell">1</strong>
              </div>
              <div class="ticket-row" role="row">
                <span role="cell">Repair service completed</span>
                <strong role="cell">5</strong>
              </div>
              <div class="ticket-row" role="row">
                <span role="cell">Roof coating service completed</span>
                <strong role="cell">12</strong>
              </div>
              <div class="ticket-row" role="row">
                <span role="cell">Roof replacement completed</span>
                <strong role="cell">30</strong>
              </div>
            </div>

            <p class="note">Entries are cumulative for qualifying transactions during the Entry Period. Sponsor maintains a log of entries issued by method and ticket count.</p>

            <p class="entry-disclaimer">No purchase necessary. Enter free by mail or schedule a free roof inspection below.</p>
            <div class="cta-row">
              <a class="btn btn-dark" href="#inspection-form">Schedule My Free Inspection</a>
              <a class="btn btn-outline-dark" href="#mail-entry">Free Mail-In Entry Details</a>
            </div>
          </article>

          <article class="entry-card featured reveal" id="mail-entry">
            <div class="entry-label">Alternate Method of Entry</div>
            <h3>Enter by free mail-in postcard.</h3>
            <p>Any eligible Texas residential homeowner may enter without any paid service, inspection, or transaction by mailing a postcard with full name, mailing address, phone number, and email address to:</p>
            <address>
              Invicta Roofing – Making Quinceañeras Great Again<br />
              509 Giles Rd<br />
              El Paso, TX 79915
            </address>
            <p class="note">Each qualifying postcard received during the Entry Period earns one entry, equal in value to the lowest-tier qualifying action entry. It must be postmarked within the Entry Period and received no later than December 3, 2026.</p>
          </article>
        </div>

        <div class="legal-cta-row reveal" aria-label="Giveaway action links">
          <a class="legal-cta" href="#mail-entry">
            <span>Free mail-in option</span>
            <strong>Enter by postcard</strong>
            <small>No purchase, inspection, service, or transaction is required for the mail-in method.</small>
          </a>
          <a class="legal-cta" href="docs/official-rules.pdf" target="_blank" rel="noopener">
            <span>Before entering</span>
            <strong>Read the full official rules</strong>
            <small>Official Rules control all giveaway terms.</small>
          </a>
        </div>

      </div>
    </section>

    <section class="section inspection-form-section" id="inspection-form">
      <div class="container form-section-grid">
        <div class="form-section-copy reveal">
          <p class="eyebrow">Free inspection request</p>
          <h2>Schedule your free roof inspection.</h2>
          <p>No purchase necessary. This form helps Invicta Roofing contact you about a free roof inspection and possible entry options under the Official Rules.</p>
          <div class="form-copy-cards">
            <div>
              <strong>Free roof check</strong>
              <span>Request a pressure-free inspection for your Texas residential property.</span>
            </div>
            <div>
              <strong>Homeowner eligibility</strong>
              <span>Entrants certify they are Texas residential homeowners and may be asked for reasonable proof before prize fulfillment.</span>
            </div>
            <div>
              <strong>Read before entering</strong>
              <span>No purchase, inspection, payment, claim, or paid service is necessary to enter or win.</span>
            </div>
          </div>
        </div>

        <form class="inspection-form reveal" action="./" method="post">
          <input type="hidden" name="form_token" value="<?= htmlspecialchars($formToken, ENT_QUOTES, 'UTF-8') ?>" />
          <input type="hidden" name="form_type" value="quinceanera_free_inspection" />
          <input type="hidden" name="serviceRequested" value="Free Inspection" />

          <div class="website-verification-wrap" aria-hidden="true">
            <label>
              Leave this field blank
              <input type="text" name="website_verification_code" tabindex="-1" autocomplete="off" />
            </label>
          </div>
          <div class="form-header">
            <span>Inspection form</span>
            <h3>Request a free inspection</h3>
            <p>Complete the fields below and Invicta will follow up to schedule your roof check.</p>
          </div>

          <?php if (!empty($formStatus['message'])): ?>
            <div class="form-status form-status-<?= htmlspecialchars($formStatus['type'], ENT_QUOTES, 'UTF-8') ?>">
              <?= htmlspecialchars($formStatus['message'], ENT_QUOTES, 'UTF-8') ?>
            </div>
          <?php endif; ?>

          <div class="form-grid-two">
            <label>
              Full Name
              <input type="text" name="fullName" autocomplete="name" required />
            </label>
            <label>
              Phone Number
              <input type="tel" name="phone" autocomplete="tel" required />
            </label>
          </div>

          <label>
            Property Address
            <input type="text" name="propertyAddress" autocomplete="street-address" placeholder="Street address, city, TX" required />
          </label>

          <div class="form-grid-two">
            <label>
              Email Address
              <input type="email" name="email" autocomplete="email" required />
            </label>
            <label>
              Preferred Date/Time
              <input type="text" name="preferredDateTime" placeholder="Preferred date/time or type: call me to schedule" required />
            </label>
          </div>

          <label>
            How did you hear about us?
            <select name="heardAbout" required>
              <option value="">Select one</option>
              <option>Social Media</option>
              <option>Billboard</option>
              <option>Referral</option>
              <option>Google</option>
              <option>Other</option>
            </select>
          </label>

          <div class="checkbox-group" aria-label="Required confirmations">
            <label class="check-row">
              <input type="checkbox" name="homeownerConfirm" required />
              <span>I am the homeowner of this property.</span>
            </label>
            <label class="check-row">
              <input type="checkbox" name="ageConfirm" required />
              <span>I am 18 or older.</span>
            </label>
            <label class="check-row">
              <input type="checkbox" name="rulesConfirm" required />
              <span>I have read and agree to the <a href="docs/official-rules.pdf" target="_blank" rel="noopener">Official Rules</a>.</span>
            </label>
            <label class="check-row optional-text-opt-in">
              <input type="checkbox" name="textReminders" />
              <span>Text me appointment reminders. This checkbox is optional and is not required to submit this request.</span>
            </label>
          </div>

          <?php if (!empty($turnstileSiteKey)): ?>
            <div class="turnstile-wrap">
              <div class="cf-turnstile" data-sitekey="<?= htmlspecialchars($turnstileSiteKey, ENT_QUOTES, 'UTF-8') ?>"></div>
            </div>
          <?php endif; ?>

          <button class="btn btn-dark btn-full" type="submit">Submit Inspection Request</button>
          <p class="form-disclaimer">No purchase necessary. Enter free by mail, or take any listed qualifying action to earn entries. Entries vary by service type; see Official Rules for the full entry structure.</p>
        </form>
      </div>
    </section>


    <section class="section prize section-dark" id="prize">
      <div class="container prize-grid">
        <div class="prize-copy reveal">
          <p class="eyebrow">The prize</p>
          <h2>El sueño de la noche could become real.</h2>
          <p>The prize is a quinceañera salon/venue package at an El Paso venue selected by Invicta Roofing, with a stated retail value of $12,000.</p>
          <p>The winner selects the event date between February 15, 2027 and December 15, 2027, subject to venue availability and the venue's own terms, availability, and cancellation policies.</p>
          <div class="inline-cta-group">
            <a class="inline-cta cta-light" href="#ways-to-enter">See entry methods and tickets</a>
          </div>
        </div>

        <div class="prize-card reveal">
          <span class="small-label">Important prize details</span>
          <ul>
            <li>Prize is non-transferable.</li>
            <li>No cash difference will be awarded if actual value varies.</li>
            <li>Sponsor may substitute a comparable or greater value prize at Sponsor's sole discretion.</li>
            <li>Winner is responsible for applicable federal, state, and local taxes.</li>
          </ul>
        </div>
      </div>
    </section>

    <section class="section rules" id="rules">
      <div class="container rules-grid">
        <div class="rules-copy reveal">
          <p class="eyebrow">Official rules summary</p>
          <h2>Read this before entering.</h2>
          <p>These highlights are not a substitute for the full Official Rules. The Official Rules control all giveaway terms.</p>
          <div class="rules-action-group">
            <a class="btn btn-dark" href="docs/official-rules.pdf" target="_blank" rel="noopener">Open Full Official Rules</a>
          </div>
        </div>

        <div class="rules-list reveal">
          <div class="rule-item">
            <strong>Eligibility</strong>
            <p>Open only to individuals who are homeowners of a residential property located in Texas and who are 18 years of age or older at the time of entry. By entering, participant certifies that they are the legal homeowner of a residential property in Texas. Sponsor reserves the right to request reasonable proof of homeownership from the selected winner prior to prize fulfillment. Void outside Texas and wherever prohibited by law.</p>
          </div>
          <div class="rule-item">
            <strong>Not eligible</strong>
            <p>Employees, owners, immediate family members, and members of the same household of Invicta Roofing are not eligible to win.</p>
          </div>
          <div class="rule-item">
            <strong>Winner selection</strong>
            <p>The selected recipient will be chosen by random drawing from all eligible entries at 509 Giles Rd, El Paso, TX 79915. Drawing date to be confirmed by Sponsor.</p>
          </div>
          <div class="rule-item">
            <strong>Odds</strong>
            <p>Odds of winning depend on the total number of eligible entries received during the Entry Period.</p>
          </div>
          <div class="rule-item">
            <strong>Entry structure</strong>
            <p>Entries are cumulative for qualifying transactions. Free roof inspections, assisted insurance claims, maintenance, repairs, roof coatings, and roof replacements earn the ticket counts shown in the Official Rules.</p>
          </div>
          <div class="rule-item">
            <strong>Mail-in deadline</strong>
            <p>Free mail-in postcard entries must be postmarked within the Entry Period and received no later than December 3, 2026.</p>
          </div>
          <div class="rule-item">
            <strong>Notification</strong>
            <p>Winner will be notified by phone and/or email within 5–7 business days of the drawing and may be required to return required documents within 7 days.</p>
          </div>
          <div class="rule-item">
            <strong>Taxes</strong>
            <p>Because the prize's stated retail value exceeds $600, Sponsor will issue an IRS Form 1099-MISC or successor form to the winner for the value of the prize.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="section timeline-section section-dark">
      <div class="container">
        <div class="section-heading centered reveal">
          <p class="eyebrow">Campaign timeline</p>
          <h2>Key dates.</h2>
        </div>

        <div class="timeline-grid">
          <article class="timeline-card reveal">
            <span>July 10, 2026</span>
            <h3>Entry Period opens</h3>
            <p>Eligible entries may begin during the official Entry Period.</p>
          </article>
          <article class="timeline-card reveal">
            <span>January 10, 2027</span>
            <h3>Entry Period ends</h3>
            <p>Entries received outside the Entry Period will not be counted. Mail-in postcards must be received no later than December 3, 2026.</p>
          </article>
          <article class="timeline-card reveal">
            <span>Date to be confirmed</span>
            <h3>Random drawing</h3>
            <p>Drawing takes place at Invicta Roofing and will be broadcast live on Facebook, Instagram, and/or TikTok. Final drawing date to be confirmed by Sponsor.</p>
          </article>
          <article class="timeline-card reveal">
            <span>Feb. 15 – Dec. 15, 2027</span>
            <h3>Prize date window</h3>
            <p>The winner chooses a date within this window, subject to venue availability.</p>
          </article>
        </div>
        <div class="section-cta-row reveal">
          <a class="btn btn-primary" href="#inspection-form">Schedule My Free Inspection</a>
        </div>
      </div>
    </section>

    <section class="section faq" id="faq">
      <div class="container faq-grid">
        <div class="faq-intro reveal">
          <p class="eyebrow">Giveaway FAQ</p>
          <h2>Quick answers.</h2>
          <p>No purchase necessary. Full Official Rules are available at invictaroofs.com and in the linked PDF on this page.</p>
        </div>

        <div class="faq-list reveal">
          <details open>
            <summary>Is a purchase required to enter?</summary>
            <p>No. No purchase, inspection, payment, service, claim, or transaction is necessary to enter or win. Qualifying actions earn entries according to the official ticket structure, and the free mail-in postcard method is also available.</p>
          </details>
          <details>
            <summary>Can qualifying actions earn multiple entries?</summary>
            <p>Yes. Under the updated Official Rules, entries are cumulative for qualifying transactions during the Entry Period. Entries are issued once the qualifying action is completed; scheduling alone does not earn an entry unless otherwise stated.</p>
          </details>
          <details>
            <summary>Can I enter without doing business with Invicta?</summary>
            <p>Yes. Eligible Texas residential homeowners may enter without any paid service, inspection, or transaction by using the free mail-in postcard method described above and in the Official Rules. Each qualifying postcard earns one entry, equal in value to the lowest-tier qualifying action entry, and must be received no later than December 3, 2026.</p>
          </details>
          <details>
            <summary>Where will the drawing happen?</summary>
            <p>The random drawing will occur at Invicta Roofing, 509 Giles Rd, El Paso, TX 79915, and will be broadcast live on Facebook, Instagram, and/or TikTok. Final drawing date to be confirmed by Sponsor.</p>
          </details>
          <details>
            <summary>Who sponsors the Giveaway?</summary>
            <p>The Sponsor is Invicta Roofing LLC, located at 509 Giles Rd, El Paso, TX 79915. Sponsor may be reached at support@invictaroofs.com or 915-630-1349.</p>
          </details>
        </div>
        <div class="faq-cta-row reveal">
          <a class="btn btn-dark" href="#inspection-form">Schedule My Free Inspection</a>
        </div>
      </div>
    </section>

    <section class="section final-cta">
      <div class="container final-card reveal">
        <div>
          <p class="eyebrow">A free quinceañera package could be yours</p>
          <h2>Enter during the official Entry Period.</h2>
          <p>No purchase necessary. Open to Texas residential homeowners 18+. Entries follow the official ticket structure, and a free mail-in postcard entry method is available. Odds of winning depend on total entries received.</p>
        </div>
        <div class="final-actions">
          <a class="btn btn-primary" href="#inspection-form">Schedule My Free Inspection</a>
          <a class="btn btn-secondary dark" href="docs/official-rules.pdf" target="_blank" rel="noopener">View Official Rules</a>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer section-dark">
    <div class="container footer-grid">
      <div>
        <a class="brand footer-brand" href="/" aria-label="Invicta Roofing home">
          <img class="brand-logo footer-logo" src="../images/logo_md.png" alt="Invicta Roofing" width="280" height="84" loading="lazy" />
        </a>
        <p>Making Quinceañeras Great Again — Invicta Roofing LLC Giveaway Drawing.</p>
      </div>

      <div>
        <h2>Campaign</h2>
        <a href="#ways-to-enter">Ways to Enter</a>
        <a href="#preview">Preview</a>
        <a href="#prize">Prize</a>
        <a href="#rules">Rules</a>
        <a href="#inspection-form">Schedule Inspection</a>
        <a href="#faq">FAQ</a>
      </div>

      <div>
        <h2>Sponsor Contact</h2>
        <p>509 Giles Rd<br />El Paso, TX 79915</p>
        <a href="tel:+19156301349">915-630-1349</a>
        <a href="mailto:support@invictaroofs.com">support@invictaroofs.com</a>
        <a href="https://www.facebook.com/profile.php?id=61587098842468" target="_blank" rel="noopener">Facebook</a>
        <a href="https://www.instagram.com/invictaroofing915/" target="_blank" rel="noopener">Instagram</a>
      </div>

      <div>
        <h2>Required Disclosure</h2>
        <p>No purchase, inspection, payment, claim, or paid service is necessary to enter or win. Open to Texas residential homeowners 18+. Free mail-in entries must be received by December 3, 2026. Full official rules at invictaroofs.com.</p>
      </div>
    </div>

    <div class="container footer-bottom">
      <span>© 2026 Invicta Roofing LLC. All rights reserved.</span>
      <a href="#main">Back to top</a>
    </div>
  </footer>

  <a class="sticky-mobile-cta" href="#inspection-form">Schedule Free Inspection</a>

  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

  <script>
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));
  </script>
</body>
</html>