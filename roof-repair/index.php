<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/secure_env.php';
require_once dirname(__DIR__) . '/includes/form-page-helpers.php';

$formToken = createSignedFormToken();

$formStatus = homeFormStatusMessage();
$currentFormStatusTarget = $_GET['form'] ?? '';

$detailFormRedirectPath = '/roof-repair/';
$detailFormRedirectAnchor = 'roof-repair-form';
$detailFormSelectedService = 'Repairs & Maintenance';

$basePath = '../';
$currentPage = 'roof-repair';

$pageTitle = 'Roof Repair in El Paso, TX | Invicta Roofing';
$pageDescription = 'Need roof repair in El Paso, TX? Invicta Roofing provides free roof inspections, leak repair guidance, repair recommendations, photo documentation, and honest next steps.';
$pageKeywords = 'roof repair El Paso, roof leak repair El Paso, roof repair El Paso TX, El Paso roofing company, roof inspection El Paso, roofing contractor El Paso';
$canonicalUrl = 'https://invictaroofs.com/roof-repair/';
$ogTitle = 'Roof Repair in El Paso, TX | Invicta Roofing';
$ogDescription = 'Schedule a free roof inspection with Invicta Roofing and find out whether your El Paso roof needs repair, maintenance, coating, or replacement.';
$ogUrl = 'https://invictaroofs.com/roof-repair/';

$schemaJson = <<<'JSON'
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Service",
      "name": "Roof Repair in El Paso, TX",
      "serviceType": "Roof Repair",
      "description": "Roof repair services in El Paso, Texas, including free roof inspections, leak repair guidance, repair recommendations, photo documentation, and maintenance guidance.",
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
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "How do I know if my roof can be repaired instead of replaced?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The best first step is a roof inspection. If the issue is isolated, repair or maintenance may be enough. If there is widespread aging, recurring leaks, or material failure, replacement may be the better long-term option."
          }
        },
        {
          "@type": "Question",
          "name": "Do you repair roof leaks in El Paso?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. Invicta Roofing can inspect visible roof conditions, document problem areas, and recommend the right next step for leaks, wear, damage, or maintenance concerns."
          }
        },
        {
          "@type": "Question",
          "name": "Is the roof repair inspection free?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. Invicta Roofing offers free roof inspections so homeowners can understand what is happening before making a repair or replacement decision."
          }
        }
      ]
    }
  ]
}
JSON;

include __DIR__ . '/../includes/header.php';
?>

    <section class="service-hero roof-repair-hero section-dark">
      <div class="container service-hero-grid">
        <div class="service-hero-copy reveal">
          <p class="eyebrow">El Trabajo <span>Barato</span> ya se Hizo, ahora toca el <span>bueno</span></p>
          <h1 class="service-title">
            <span>Roof Repair</span>
            <span>in El Paso, TX</span>
          </h1>
          <p>
            If you are dealing with a leak, visible roof damage, problem areas, or signs of poor previous work,
            Invicta Roofing can inspect your roof and help you understand whether repair is the right next step.
          </p>

          <div class="hero-actions">
            <a class="btn btn-primary" href="#roof-repair-form">Schedule Free Inspection</a>
            <a class="btn btn-secondary" href="tel:+19156301349">Call 915-630-1349</a>
          </div>

          <div class="trust-row" aria-label="Company highlights">
            <span>Woman-led</span>
            <span>Licensed, bonded &amp; insured</span>
            <span>El Paso local</span>
          </div>
        </div>

        <aside class="service-hero-card reveal">
          <h2>Need your roof repaired?</h2>
          <p>
            A free inspection helps determine whether your roof needs a targeted repair, preventative maintenance,
            coating, or a larger roofing solution.
          </p>

          <ul class="service-check-list">
            <li>Leak and problem-area review</li>
            <li>Photo documentation</li>
            <li>Repair vs. replacement guidance</li>
            <li>Clear next-step recommendation</li>
          </ul>

          <a class="btn btn-dark btn-full" href="#roof-repair-form">Request My Inspection</a>
        </aside>
      </div>
    </section>

    <section class="proof-strip" aria-label="Roof repair highlights">
      <div class="container proof-grid">
        <div>
          <strong>Free</strong>
          <span>Roof inspections</span>
        </div>
        <div>
          <strong>Targeted</strong>
          <span>Repair guidance</span>
        </div>
        <div>
          <strong>Clear</strong>
          <span>Photo documentation</span>
        </div>
        <div>
          <strong>Local</strong>
          <span>El Paso roofing team</span>
        </div>
      </div>
    </section>

    <section class="section service-intro">
      <div class="container service-intro-grid">
        <div class="service-intro-copy reveal">
          <p class="eyebrow">When repair makes sense</p>
          <h2>Roof repair should fix the source of the problem, not just hide the symptom.</h2>
          <p>
            A small leak or damaged area can turn into a bigger issue when it is ignored or patched without understanding
            why it happened. Invicta Roofing starts with an inspection so the repair recommendation is based on the actual roof condition.
          </p>
          <p>
            If the damage is isolated, a targeted repair or maintenance plan may help extend the life of the roof.
            If the roof has wider problems, Invicta will explain that clearly before you spend money on repairs that may not last.
          </p>
        </div>

        <div class="service-signs-card reveal">
          <h3>Signs your roof may need repair</h3>
          <ul>
            <li>Interior water stains or signs of a roof leak</li>
            <li>Cracked, lifted, missing, or damaged roofing materials</li>
            <li>Problem areas around vents, flashing, skylights, or roof transitions</li>
            <li>Ponding water or drainage issues on low-slope areas</li>
            <li>Damage from wind, weather, age, or previous installation mistakes</li>
            <li>Small problems you want checked before they become expensive</li>
          </ul>
        </div>
      </div>
    </section>

    <section class="section section-dark service-split">
      <div class="container service-split-grid">
        <div class="service-split-media reveal">
          <img src="<?= $basePath ?>images/after_2.png" alt="Completed roof repair and maintenance work by Invicta Roofing in El Paso" />
        </div>

        <div class="service-split-copy reveal">
          <p class="eyebrow">Repair before it gets worse</p>
          <h2>Targeted roof repairs for leaks, wear, and problem areas.</h2>
          <p>
            The right roof repair starts with finding the source of the issue. Invicta Roofing looks at visible damage,
            weak points, drainage concerns, and areas where previous work may not have protected the home properly.
          </p>

          <ul class="check-list">
            <li>Leak and moisture concern review</li>
            <li>Flashing, transition, and penetration checks</li>
            <li>Repair recommendations explained clearly</li>
            <li>Maintenance guidance when full replacement is not needed</li>
          </ul>

          <a class="btn btn-primary" href="#roof-repair-form">Get Roof Repair Help</a>
        </div>
      </div>
    </section>

    <section class="section repair-process">
      <div class="container">
        <div class="section-heading centered reveal">
          <p class="eyebrow">Roof repair process</p>
          <h2>A clear path from roof concern to repair recommendation.</h2>
          <p>
            Invicta Roofing keeps the repair process straightforward so you know what was found, what it means,
            and what should happen next.
          </p>
        </div>

        <div class="process-grid">
          <article class="process-card reveal">
            <span>1</span>
            <h3>Schedule your free inspection</h3>
            <p>Tell Invicta what you are seeing, whether it is a leak, visible damage, wear, or an area that concerns you.</p>
          </article>

          <article class="process-card reveal">
            <span>2</span>
            <h3>Identify the problem area</h3>
            <p>The roof is checked for visible issues that may explain the leak, damage, drainage problem, or repair need.</p>
          </article>

          <article class="process-card reveal">
            <span>3</span>
            <h3>Review your options</h3>
            <p>Invicta explains whether a repair, maintenance plan, coating, or larger roofing solution makes the most sense.</p>
          </article>

          <article class="process-card reveal">
            <span>4</span>
            <h3>Complete the repair with care</h3>
            <p>When repair is the right move, the work is handled with clear communication and respect for your property.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="section replacement-proof repair-proof">
      <div class="container replacement-proof-grid">
        <div class="replacement-proof-copy reveal">
          <p class="eyebrow">Repair and maintenance results</p>
          <h2>A good repair can protect the home and prevent bigger issues later.</h2>
          <p>
            Roof repairs are not just about patching what is visible. They should address why the issue happened and help
            the homeowner avoid repeat problems.
          </p>
          <p>
            Invicta Roofing helps homeowners understand when repair is enough and when it may be smarter to consider another solution.
          </p>

          <div class="mini-cta-row">
            <a class="btn btn-dark" href="<?= $basePath ?>contact/">Talk to Invicta</a>
            <a class="btn btn-outline-dark" href="#roof-repair-faq">Read FAQs</a>
          </div>
        </div>

        <article class="ba-card reveal">
          <div class="ba-comparison" aria-label="Before and after roof repair and maintenance project">
            <div class="ba-photo ba-before" style="--photo: url('<?= $basePath ?>images/before_2.png');" role="img"
              aria-label="Before roof repair and maintenance project">
              <span>Before</span>
            </div>
            <div class="ba-photo ba-after" style="--photo: url('<?= $basePath ?>images/after_2.png');" role="img"
              aria-label="After roof repair and maintenance project">
              <span>After</span>
            </div>
          </div>
          <div class="ba-content">
            <span>Roof repair</span>
            <h3>From problem area to a stronger repair plan.</h3>
            <p>
              When previous work or isolated damage causes roofing issues, the right repair starts with understanding the source of the problem.
            </p>
          </div>
        </article>
      </div>
    </section>

    <section class="credential-band" aria-label="Invicta Roofing credentials and affiliations">
      <div class="container credential-card reveal">
        <div class="credential-copy">
          <p class="eyebrow"><span>Trusted</span> credentials</p>
          <h2>Recognized roofing standards behind every repair recommendation.</h2>
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

    <section class="section service-form-section" id="roof-repair-form">
      <div class="container service-form-grid">
        <div class="service-form-copy reveal">
          <p class="eyebrow">Free roof repair inspection</p>
          <h2>Find out what your roof repair really needs.</h2>
          <p>
            Submit the form and Invicta Roofing will follow up to schedule a free inspection for your El Paso home.
          </p>

          <div class="form-copy-cards">
            <div>
              <strong>No-pressure inspection</strong>
              <span>You get clear information before making a repair decision.</span>
            </div>
            <div>
              <strong>Photo documentation</strong>
              <span>Visible roof concerns can be documented and explained.</span>
            </div>
            <div>
              <strong>Repair guidance</strong>
              <span>Invicta helps you understand whether repair, maintenance, coating, or replacement is the right next step.</span>
            </div>
          </div>
        </div>

        <div class="service-form-panel reveal">
          <?php include dirname(__DIR__) . '/includes/forms/detailed-inspection-form.php'; ?>
        </div>
      </div>
    </section>

    <section class="section faq" id="roof-repair-faq">
      <div class="container faq-grid">
        <div class="faq-intro reveal">
          <p class="eyebrow">Roof repair FAQ</p>
          <h2>Questions homeowners ask before repairing a roof.</h2>
        </div>

        <div class="faq-list reveal">
          <details open>
            <summary>How do I know if my roof can be repaired instead of replaced?</summary>
            <p>
              The best first step is a roof inspection. If the issue is isolated, repair or maintenance may be enough.
              If there is widespread aging, recurring leaks, or material failure, replacement may be the better long-term option.
            </p>
          </details>

          <details>
            <summary>Do you repair roof leaks in El Paso?</summary>
            <p>
              Yes. Invicta Roofing can inspect visible roof conditions, document problem areas, and recommend the right next step for leaks,
              wear, damage, or maintenance concerns.
            </p>
          </details>

          <details>
            <summary>Is the roof repair inspection free?</summary>
            <p>
              Yes. Invicta Roofing offers free roof inspections so homeowners can understand what is happening before making a repair or replacement decision.
            </p>
          </details>

          <details>
            <summary>What roof problems can usually be repaired?</summary>
            <p>
              Isolated leaks, minor damage, flashing issues, small problem areas, and some maintenance concerns may be repairable.
              The inspection helps determine whether repair is appropriate for the roof’s actual condition.
            </p>
          </details>

          <details>
            <summary>What if my roof needs more than a repair?</summary>
            <p>
              If the issue is more widespread, Invicta Roofing will explain the visible concerns and help you understand whether replacement,
              coating, or another roofing solution makes more sense.
            </p>
          </details>
        </div>
      </div>
    </section>

    <section class="section final-cta">
      <div class="container final-card reveal">
        <div>
          <p class="eyebrow">Need roof repair help?</p>
          <h2>Schedule your free roof repair inspection.</h2>
          <p>
            Invicta Roofing will inspect your roof, explain what is visible, and help you understand whether repair is the right move.
          </p>
        </div>
        <div class="final-actions">
          <a class="btn btn-primary" href="#roof-repair-form">Schedule Free Inspection</a>
          <a class="btn btn-primary" href="tel:+19156301349">Call 915-630-1349</a>
        </div>
      </div>
    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>