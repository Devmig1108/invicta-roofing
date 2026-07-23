<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/secure_env.php';
require_once dirname(__DIR__) . '/includes/form-page-helpers.php';

$formToken = createSignedFormToken();

$formStatus = homeFormStatusMessage();
$currentFormStatusTarget = $_GET['form'] ?? '';

$detailFormRedirectPath = '/roof-replacement/';
$detailFormRedirectAnchor = 'roof-replacement-form';
$detailFormSelectedService = 'Roof Replacements';

$basePath = '../';
$currentPage = 'roof-replacement';

$pageTitle = 'Roof Replacement in El Paso, TX | Invicta Roofing';
$pageDescription = 'Need roof replacement in El Paso, TX? Invicta Roofing provides free roof inspections, clear replacement recommendations, photo documentation, and roofing work built for long-term protection.';
$pageKeywords = 'roof replacement El Paso, roof replacement El Paso TX, El Paso roofing company, roof replacement contractor El Paso, free roof inspection El Paso';
$canonicalUrl = 'https://invictaroofs.com/roof-replacement/';
$ogTitle = 'Roof Replacement in El Paso, TX | Invicta Roofing';
$ogDescription = 'Schedule a free roof inspection with Invicta Roofing and find out if your El Paso home needs roof replacement, repair, coating, or maintenance.';
$ogUrl = 'https://invictaroofs.com/roof-replacement/';

$schemaJson = <<<'JSON'
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Roof Replacement in El Paso, TX",
  "serviceType": "Roof Replacement",
  "description": "Roof replacement services in El Paso, Texas, including free roof inspections, replacement recommendations, photo documentation, and roofing work built for long-term protection.",
  "areaServed": {
    "@type": "City",
    "name": "El Paso",
    "addressRegion": "TX",
    "addressCountry": "US"
  },
  "provider": {
    "@type": "RoofingContractor",
    "name": "Invicta Roofing",
    "url": "https://invictaroofs.com/",
    "telephone": "+1-915-630-1349",
    "email": "Support@invictaroofs.com",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "509 Giles Rd. Suite A",
      "addressLocality": "El Paso",
      "addressRegion": "TX",
      "postalCode": "79915",
      "addressCountry": "US"
    }
  }
}
JSON;

include __DIR__ . '/../includes/header.php';
?>

    <section class="service-hero roof-replacement-hero section-dark">
      <div class="container service-hero-grid">
        <div class="service-hero-copy reveal">
          <p class="eyebrow">El Trabajo <span>Barato</span> ya se Hizo, ahora toca el <span>bueno</span></p>
          <h1 class="service-title">
            <span>Roof Replacement</span>
            <span>in El Paso, TX</span>
          </h1>
          <p>
            If your roof is aging, leaking, damaged, or no longer protecting your home the way it should,
            Invicta Roofing can inspect it, explain your options, and help you decide whether replacement is the right next step.
          </p>

          <div class="hero-actions">
            <a class="btn btn-primary" href="#roof-replacement-form">Schedule Free Inspection</a>
            <a class="btn btn-secondary" href="tel:+19156301349">Call 915-630-1349</a>
          </div>

          <div class="trust-row" aria-label="Company highlights">
            <span>Woman-led</span>
            <span>Licensed, bonded &amp; insured</span>
            <span>El Paso local</span>
          </div>
        </div>

        <aside class="service-hero-card reveal">
          <h2>Not sure if you need a new roof?</h2>
          <p>
            A free inspection helps you understand whether your roof needs replacement, repair, coating, or maintenance.
          </p>

          <ul class="service-check-list">
            <li>Visible roof condition check</li>
            <li>Photo documentation</li>
            <li>Repair vs. replacement guidance</li>
            <li>Clear next-step recommendation</li>
          </ul>

          <a class="btn btn-dark btn-full" href="#roof-replacement-form">Request My Inspection</a>
        </aside>
      </div>
    </section>

    <section class="proof-strip" aria-label="Roof replacement highlights">
      <div class="container proof-grid">
        <div>
          <strong>Free</strong>
          <span>Roof inspections</span>
        </div>
        <div>
          <strong>Clear</strong>
          <span>Replacement guidance</span>
        </div>
        <div>
          <strong>Local</strong>
          <span>El Paso roofing team</span>
        </div>
        <div>
          <strong>Built</strong>
          <span>For long-term protection</span>
        </div>
      </div>
    </section>

    <section class="section service-intro">
      <div class="container service-intro-grid">
        <div class="service-intro-copy reveal">
          <p class="eyebrow">When replacement makes sense</p>
          <h2>A roof replacement should solve the real problem, not just cover it up.</h2>
          <p>
            Some roof problems can be handled with repairs or maintenance. But when a roof has widespread wear,
            recurring leaks, aging materials, poor installation, or damage that affects long-term protection,
            replacement may be the smarter investment.
          </p>
          <p>
            Invicta Roofing starts with an inspection so homeowners can make an informed decision before committing to a major roofing project.
          </p>
        </div>

        <div class="service-signs-card reveal">
          <h3>Signs your roof may need replacement</h3>
          <ul>
            <li>Recurring leaks or interior water stains</li>
            <li>Large areas of visible wear or material failure</li>
            <li>Roof age becoming a concern for insurance or resale</li>
            <li>Multiple repairs that are no longer solving the issue</li>
            <li>Poor previous installation causing ongoing problems</li>
            <li>Damage that affects the roof’s ability to protect the home</li>
          </ul>
        </div>
      </div>
    </section>

    <section class="section section-dark service-split">
      <div class="container service-split-grid">
        <div class="service-split-media reveal">
          <img src="<?= $basePath ?>images/after_1.png" alt="Completed roof replacement by Invicta Roofing in El Paso" />
        </div>

        <div class="service-split-copy reveal">
          <p class="eyebrow">Built for El Paso homes</p>
          <h2>Roof replacement with long-term protection in mind.</h2>
          <p>
            El Paso homes need roofing work that accounts for heat, wind, rain, drainage, and water intrusion protection.
            A replacement is not just about putting on a new surface. It is about improving how the home is protected.
          </p>

          <ul class="check-list">
            <li>Clear scope of work before the project starts</li>
            <li>Material recommendations based on your roof and home</li>
            <li>Clean, professional installation process</li>
            <li>Final walkthrough and quality check</li>
          </ul>

          <a class="btn btn-primary" href="#roof-replacement-form">Get a Replacement Estimate</a>
        </div>
      </div>
    </section>

    <section class="section replacement-process">
      <div class="container">
        <div class="section-heading centered reveal">
          <p class="eyebrow">Roof replacement process</p>
          <h2>From inspection to a roof you can feel confident about.</h2>
          <p>
            Invicta Roofing keeps the replacement process clear so you know what is happening, why it matters, and what comes next.
          </p>
        </div>

        <div class="process-grid">
          <article class="process-card reveal">
            <span>1</span>
            <h3>Free roof inspection</h3>
            <p>Invicta checks the visible condition of your roof, documents concerns, and listens to what you have noticed.</p>
          </article>

          <article class="process-card reveal">
            <span>2</span>
            <h3>Clear replacement recommendation</h3>
            <p>You get a straightforward explanation of whether replacement, repair, coating, or maintenance makes the most sense.</p>
          </article>

          <article class="process-card reveal">
            <span>3</span>
            <h3>Scope and scheduling</h3>
            <p>If replacement is the right move, Invicta explains the scope of work and helps schedule the project.</p>
          </article>

          <article class="process-card reveal">
            <span>4</span>
            <h3>Installation and walkthrough</h3>
            <p>The roof is completed with clean workmanship, communication, and a final quality review.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="section replacement-proof">
      <div class="container replacement-proof-grid">
        <div class="replacement-proof-copy reveal">
          <p class="eyebrow">Real roofing results</p>
          <h2>Replacing the roof can restore protection, confidence, and long-term value.</h2>
          <p>
            A roof replacement is often one of the biggest home improvement decisions a homeowner will make.
            The right roofing company should make that decision clearer, not more confusing.
          </p>
          <p>
            Invicta Roofing focuses on honest inspections, clear documentation, and replacement work built to protect the home for years to come.
          </p>

          <div class="mini-cta-row">
            <a class="btn btn-dark" href="<?= $basePath ?>contact/">Talk to Invicta</a>
            <a class="btn btn-outline-dark" href="#roof-replacement-faq">Read FAQs</a>
          </div>
        </div>

        <article class="ba-card reveal">
          <div class="ba-comparison" aria-label="Before and after roof replacement project">
            <div class="ba-photo ba-before" style="--photo: url('<?= $basePath ?>images/before_1.png');" role="img"
              aria-label="Before roof replacement project">
              <span>Before</span>
            </div>
            <div class="ba-photo ba-after" style="--photo: url('<?= $basePath ?>images/after_1.png');" role="img"
              aria-label="After roof replacement project">
              <span>After</span>
            </div>
          </div>
          <div class="ba-content">
            <span>Roof replacement</span>
            <h3>From an aging roof to a stronger next step.</h3>
            <p>
              When roof age, wear, or damage starts creating bigger concerns, replacement can give the home renewed protection and peace of mind.
            </p>
          </div>
        </article>
      </div>
    </section>

    <section class="credential-band" aria-label="Invicta Roofing credentials and affiliations">
      <div class="container credential-card reveal">
        <div class="credential-copy">
          <p class="eyebrow"><span>Trusted</span> credentials</p>
          <h2>Recognized roofing standards behind every replacement.</h2>
          <p>
            Invicta Roofing builds trust with manufacturer certification, industry affiliation,
            and public business profiles homeowners can review before they call.
          </p>
        </div>

        <div class="credential-logos" aria-label="Invicta Roofing trust badges">
          <div class="credential-logo credential-logo-gaf" aria-label="GAF Certified Plus Residential Roofing Contractor">
            <img src="<?= $basePath ?>images/Certified_Plus.png" alt="GAF Certified Plus Residential Roofing Contractor" width="320" height="320" />
          </div>

          <div class="credential-logo credential-logo-nwir" aria-label="National Women in Roofing">
            <img src="<?= $basePath ?>images/nwir.png" alt="National Women in Roofing" width="420" height="150" />
          </div>

          <a class="credential-logo credential-logo-bbb"
            href="https://www.bbb.org/us/tx/el-paso/profile/roofing-contractors/invicta-roofing-0895-99174989/#sealclick"
            target="_blank" rel="nofollow noopener" aria-label="Invicta Roofing BBB Business Review">
            <img src="https://seal-elpaso.bbb.org/seals/blue-seal-153-100-bluetxt-bbb-99174989.png" style="border: 0;"
              alt="Invicta Roofing BBB Business Review" width="153" height="100" />
          </a>

          <div class="credential-logo credential-logo-gaf">
            <img src="<?= $basePath ?>images/epcom.png" alt="El Paso business affiliation badge" width="320" height="320" />
          </div>
        </div>
      </div>
    </section>

    <section class="section service-form-section" id="roof-replacement-form">
      <div class="container service-form-grid">
        <div class="service-form-copy reveal">
          <p class="eyebrow">Free roof replacement inspection</p>
          <h2>Find out if your roof needs replacement.</h2>
          <p>
            Submit the form and Invicta Roofing will follow up to schedule a free inspection for your El Paso home.
          </p>

          <div class="form-copy-cards">
            <div>
              <strong>No-pressure inspection</strong>
              <span>You get clear information before making a decision.</span>
            </div>
            <div>
              <strong>Photo documentation</strong>
              <span>Visible roof concerns can be documented and explained.</span>
            </div>
            <div>
              <strong>Replacement guidance</strong>
              <span>Invicta helps you understand whether replacement is the right next step.</span>
            </div>
          </div>
        </div>

        <div class="service-form-panel reveal">
          <?php include dirname(__DIR__) . '/includes/forms/detailed-inspection-form.php'; ?>
        </div>
      </div>
    </section>

    <section class="section faq" id="roof-replacement-faq">
      <div class="container faq-grid">
        <div class="faq-intro reveal">
          <p class="eyebrow">Roof replacement FAQ</p>
          <h2>Questions homeowners ask before replacing a roof.</h2>
        </div>

        <div class="faq-list reveal">
          <details open>
            <summary>How do I know if I need roof replacement?</summary>
            <p>
              The best first step is a roof inspection. If damage or wear is isolated, repair or maintenance may be enough.
              If the roof has widespread issues, recurring leaks, aging materials, or larger protection concerns, replacement may be the better option.
            </p>
          </details>

          <details>
            <summary>Do you inspect the roof before recommending replacement?</summary>
            <p>
              Yes. Invicta Roofing starts with an inspection so the recommendation is based on the actual visible condition of the roof.
            </p>
          </details>

          <details>
            <summary>Can roof replacement help with insurance or home concerns?</summary>
            <p>
              In some situations, roof condition can affect insurance or long-term home protection. Invicta can document visible roof conditions and help homeowners understand roofing-related next steps.
            </p>
          </details>

          <details>
            <summary>What happens during the replacement process?</summary>
            <p>
              Invicta reviews the roof, explains the scope of work, schedules the project, completes the installation, and performs a final walkthrough or quality check.
            </p>
          </details>

          <details>
            <summary>Do you serve homeowners throughout El Paso?</summary>
            <p>
              Yes. Invicta Roofing serves homeowners in El Paso, Texas and nearby surrounding areas.
            </p>
          </details>
        </div>
      </div>
    </section>

    <section class="section final-cta">
      <div class="container final-card reveal">
        <div>
          <p class="eyebrow">Ready for a clearer roofing decision?</p>
          <h2>Schedule your free roof replacement inspection.</h2>
          <p>
            Invicta Roofing will inspect your roof, explain what is visible, and help you understand whether replacement is the right move.
          </p>
        </div>
        <div class="final-actions">
          <a class="btn btn-primary" href="#roof-replacement-form">Schedule Free Inspection</a>
          <a class="btn btn-primary" href="tel:+19156301349">Call 915-630-1349</a>
        </div>
      </div>
    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>