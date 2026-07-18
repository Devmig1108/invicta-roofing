<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/secure_env.php';
require_once dirname(__DIR__) . '/includes/form-page-helpers.php';

$formToken = createSignedFormToken();

$formStatus = homeFormStatusMessage();
$currentFormStatusTarget = $_GET['form'] ?? '';

$detailFormRedirectPath = '/contact/';
$detailFormRedirectAnchor = 'detailed-inspection-form';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Contact Invicta Roofing | Free Roof Inspections in El Paso, TX</title>
  <meta name="description" content="Contact Invicta Roofing in El Paso, Texas to schedule a free roof inspection, ask about roof replacement, roof repair, coatings, or insurance claim support." />
  <link rel="canonical" href="https://invictaroofs.com/contact/" />
  <link rel="icon" type="image/png" href="/images/logo.png">

  <meta property="og:title" content="Contact Invicta Roofing | El Paso Roofing Company" />
  <meta property="og:description" content="Schedule a free roof inspection with Invicta Roofing in El Paso, Texas." />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://invictaroofs.com/contact/" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800;900&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="../styles.css" />

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "RoofingContractor",
    "name": "Invicta Roofing",
    "url": "https://invictaroofs.com/contact/",
    "telephone": "+1-915-630-1349",
    "email": "Support@invictaroofs.com",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "509 Giles Rd. Suite A",
      "addressLocality": "El Paso",
      "addressRegion": "TX",
      "postalCode": "79915",
      "addressCountry": "US"
    },
    "areaServed": "El Paso, Texas",
    "openingHoursSpecification": [
      {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
        "opens": "08:00",
        "closes": "17:00"
      },
      {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Saturday", "Sunday"],
        "opens": "08:00",
        "closes": "12:00"
      }
    ]
  }
  </script>
</head>

<body>
  <a class="skip-link" href="#main">Skip to content</a>

  <header class="site-header" id="top">
    <div class="announcement">
      <span>Free roof inspections</span>
      <span>Photo documentation</span>
      <span>Insurance support</span>
    </div>

    <nav class="nav container" aria-label="Primary navigation">
      <a class="brand" href="/" aria-label="Invicta Roofing home">
        <img class="brand-logo" src="/images/logo_md_dark.svg" alt="Invicta Roofing" width="280" height="84" />
      </a>

      <button class="nav-toggle" type="button" aria-label="Open menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <div class="nav-menu" id="nav-menu">
        <a href="/">Home</a>
        <a href="/about/">About</a>
        <a href="/#services">Services</a>
        <a href="/#results">Results</a>
        <a href="/#reviews">Reviews</a>
        <a href="/contact/" class="is-active">Contact</a>
      </div>

      <a class="nav-call" href="tel:+19156301349">915-630-1349</a>
    </nav>
  </header>

  <main id="main">
    <section class="contact-hero section-dark">
      <div class="container contact-hero-grid">
        <div class="contact-hero-copy reveal">
          <p class="eyebrow">Contact Invicta Roofing</p>
          <h1>Schedule your free roof inspection in El Paso.</h1>
          <p>
            Whether you need a roof inspection, repair, replacement, coating, or help understanding your next step,
            Invicta Roofing is ready to give you clear answers without pressure.
          </p>

          <div class="hero-actions">
            <a class="btn btn-primary" href="#detailed-inspection-form">Schedule Free Inspection</a>
            <a class="btn btn-secondary" href="tel:+19156301349">Call 915-630-1349</a>
          </div>

          <div class="trust-row" aria-label="Company highlights">
            <span>Woman-led</span>
            <span>Licensed, bonded & insured</span>
            <span>El Paso local</span>
          </div>
        </div>

        <div class="contact-hero-card reveal">
          <span class="small-label">Fastest way to start</span>
          <h2>Call or submit the form.</h2>
          <p>
            A team member will follow up to learn what is happening with your roof and help schedule the right next step.
          </p>

          <div class="contact-method-list">
            <a href="tel:+19156301349">
              <span>Call now</span>
              <strong>915-630-1349</strong>
            </a>

            <a href="mailto:Support@invictaroofs.com">
              <span>Email</span>
              <strong>Support@invictaroofs.com</strong>
            </a>

            <a href="#map">
              <span>Visit</span>
              <strong>509 Giles Rd. Suite A</strong>
            </a>
          </div>
        </div>
      </div>
    </section>

    <section class="proof-strip" aria-label="Contact highlights">
      <div class="container proof-grid">
        <div>
          <strong>Free</strong>
          <span>Roof inspections</span>
        </div>
        <div>
          <strong>Local</strong>
          <span>El Paso roofing team</span>
        </div>
        <div>
          <strong>Clear</strong>
          <span>Photo documentation</span>
        </div>
        <div>
          <strong>Support</strong>
          <span>Insurance questions</span>
        </div>
      </div>
    </section>

    <section class="section contact-main">
      <div class="container contact-main-grid">
        <div class="contact-info-panel reveal">
          <p class="eyebrow">How to reach us</p>
          <h2>Talk to a roofing team that gives you straight answers.</h2>
          <p>
            Use the form to request a free inspection, or contact Invicta Roofing directly by phone or email.
          </p>

          <div class="contact-card-grid">
            <a class="contact-info-card" href="tel:+19156301349">
              <span>Phone</span>
              <strong>915-630-1349</strong>
              <small>Call to request an inspection or ask a roofing question.</small>
            </a>

            <a class="contact-info-card" href="mailto:Support@invictaroofs.com">
              <span>Email</span>
              <strong>Support@invictaroofs.com</strong>
              <small>Send photos, questions, or project details.</small>
            </a>

            <!-- <div class="contact-info-card">
              <span>Office</span>
              <strong>509 Giles Rd. Suite A</strong>
              <small>El Paso, TX 79915</small>
            </div>

            <div class="contact-info-card">
              <span>Hours</span>
              <strong>Mon–Fri: 8 AM–5 PM</strong>
              <small>Saturday & Sunday: 8 AM–12 PM</small>
            </div> -->
          </div>
        </div>

        <div class="contact-form-panel reveal">
          <?php include dirname(__DIR__) . '/includes/forms/detailed-inspection-form.php'; ?>
        </div>
      </div>
    </section>

    <section class="section section-dark contact-services">
      <div class="container">
        <div class="section-heading centered reveal">
          <p class="eyebrow">What can we help with?</p>
          <h2>Start with the roofing service that fits your situation.</h2>
          <p class="eyebrow">Not every roof needs the same solution. Invicta Roofing helps homeowners understand the condition of their roof and choose the right next step.</p>
        </div>

        <div class="contact-service-grid">
          <a class="contact-service-card reveal" href="/roof-inspections/">
            <span>01</span>
            <h3>Roof Inspections</h3>
            <p>Pressure-free roof evaluations with clear recommendations.</p>
          </a>

          <a class="contact-service-card reveal" href="/roof-replacement/">
            <span>02</span>
            <h3>Roof Replacement</h3>
            <p>Roof replacement built for long-term protection in El Paso.</p>
          </a>

          <a class="contact-service-card reveal" href="/roof-repair/">
            <span>03</span>
            <h3>Roof Repair</h3>
            <p>Targeted repair options for leaks, wear, and problem areas.</p>
          </a>

          <a class="contact-service-card reveal" href="/roof-insurance-claims-assistance/">
            <span>04</span>
            <h3>Insurance Claim Support</h3>
            <p>Documentation and guidance for roofing-related insurance questions.</p>
          </a>
        </div>
      </div>
    </section>

    <section class="section contact-map-section" id="map">
      <div class="container contact-map-grid">
        <div class="contact-map-copy reveal">
          <p class="eyebrow">Location</p>
          <h2>Invicta Roofing in El Paso, Texas.</h2>
          <p>
            Invicta Roofing serves homeowners in El Paso and nearby surrounding areas.
          </p>

          <address>
            Invicta Roofing<br>
            509 Giles Rd. Suite A<br>
            El Paso, TX 79915
          </address>

          <div class="mini-cta-row">
            <a class="btn btn-dark" href="tel:+19156301349">Call 915-630-1349</a>
            <a class="btn btn-outline-dark" href="https://www.google.com/maps/search/?api=1&query=509%20Giles%20Rd%20Suite%20A%20El%20Paso%20TX%2079915" target="_blank" rel="noopener">Open in Maps</a>
          </div>
        </div>

        <div class="contact-map-card reveal">
          <iframe
            title="Map to Invicta Roofing"
            src="https://www.google.com/maps?q=509%20Giles%20Rd%20Suite%20A%20El%20Paso%20TX%2079915&output=embed"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </section>

    <section class="section faq">
      <div class="container faq-grid">
        <div class="faq-intro reveal">
          <p class="eyebrow">Contact FAQ</p>
          <h2>Before you reach out.</h2>
        </div>

        <div class="faq-list reveal">
          <details open>
            <summary>Is the roof inspection really free?</summary>
            <p>Yes. Invicta Roofing offers free roof inspections so homeowners can understand what their roof needs before making a decision.</p>
          </details>

          <details>
            <summary>Do I need to know what service I need before calling?</summary>
            <p>No. If you are unsure whether you need a repair, replacement, coating, or inspection, Invicta can start with an evaluation and explain your options.</p>
          </details>

          <details>
            <summary>Can I send photos of my roof?</summary>
            <p>Yes. You can email photos and details to Support@invictaroofs.com, or submit the form and explain what you are seeing.</p>
          </details>

          <details>
            <summary>Do you help with insurance questions?</summary>
            <p>Yes. Invicta Roofing can document visible roof conditions and help homeowners understand possible next steps related to roofing and insurance questions.</p>
          </details>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer section-dark">
    <div class="container footer-grid">
      <div>
        <a class="brand footer-brand" href="/" aria-label="Invicta Roofing home">
          <img class="brand-logo footer-logo" src="/images/logo_md.png" alt="Invicta Roofing" width="280" height="84" loading="lazy" />
        </a>
        <p>Undefeated roofing for El Paso homes. Family-approved. Built to be undefeated.</p>
      </div>

      <div>
        <h2>Services</h2>
        <a href="/roof-inspections/">Roof Inspections</a>
        <a href="/roof-replacement/">Roof Replacements</a>
        <a href="/roof-repair/">Roof Repairs</a>
        <a href="/roof-coatings/">Roof Coatings</a>
      </div>

      <div>
        <h2>Contact</h2>
        <p>509 Giles Rd. Suite A<br />El Paso, TX 79915</p>
        <a href="tel:+19156301349">915-630-1349</a>
        <a href="mailto:Support@invictaroofs.com">Support@invictaroofs.com</a>
      </div>

      <div>
        <h2>Hours</h2>
        <p>Monday - Friday<br />8:00 AM - 5:00 PM</p>
        <p>Saturday & Sunday<br />8:00 AM - 12:00 PM</p>
      </div>
    </div>

    <div class="container footer-bottom">
      <span>© 2026 Invicta Roofing. All rights reserved.</span>
      <a href="#main">Back to top</a>
    </div>
  </footer>

  <script>
    const toggle = document.querySelector('.nav-toggle');
    const menu = document.querySelector('.nav-menu');

    if (toggle && menu) {
      toggle.addEventListener('click', () => {
        const isOpen = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', String(!isOpen));
        menu.classList.toggle('is-open');
      });

      document.querySelectorAll('.nav-menu a').forEach((link) => {
        link.addEventListener('click', () => {
          toggle.setAttribute('aria-expanded', 'false');
          menu.classList.remove('is-open');
        });
      });
    }

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