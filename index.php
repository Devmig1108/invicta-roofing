<?php

declare(strict_types=1);

require_once __DIR__ . '/config/secure_env.php';
require_once __DIR__ . '/includes/form-page-helpers.php';

$formToken = createSignedFormToken();

$formStatus = homeFormStatusMessage();
$currentFormStatusTarget = $_GET['form'] ?? '';

$basePath = '';
$currentPage = 'home';

$pageTitle = 'Invicta Roofing | El Paso Roof Inspections, Replacements & Repairs';
$pageDescription = 'Invicta Roofing is a woman-led roofing company in El Paso, Texas offering roof inspections, roof replacements, insurance support, and repairs and maintenance.';
$pageKeywords = 'roofing El Paso, roof inspection El Paso, roof replacement El Paso, roof repair El Paso, roofing contractor El Paso, insurance support roofing';
$canonicalUrl = 'https://invictaroofs.com/';
$ogTitle = 'Invicta Roofing | Undefeated Roofing For El Paso Homes';
$ogDescription = 'Roofs built to last, not just to pass. Schedule your free roof inspection with Invicta Roofing in El Paso.';
$ogUrl = 'https://invictaroofs.com/';

$detailFormRedirectPath = '/';
$detailFormRedirectAnchor = 'inspection';

$schemaJson = <<<'JSON'
{
  "@context": "https://schema.org",
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
  ],
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "name": "Invicta Roofing Services",
    "itemListElement": [
      { "@type": "Offer", "itemOffered": { "@type": "Service", "name": "Roof Inspections" } },
      { "@type": "Offer", "itemOffered": { "@type": "Service", "name": "Roof Replacements" } },
      { "@type": "Offer", "itemOffered": { "@type": "Service", "name": "Insurance Support" } },
      { "@type": "Offer", "itemOffered": { "@type": "Service", "name": "Repairs & Maintenance" } }
    ]
  },
  "sameAs": [
    "https://www.instagram.com/invictaroofing915/",
    "https://www.facebook.com/profile.php?id=61587098842468"
  ]
}
JSON;

include $basePath . 'includes/header.php';
?>
    <section class="hero section-dark">
      <video class="hero-video" autoplay muted loop playsinline preload="auto" aria-hidden="true">
        <source
          src="https://bc-getty-media.brandcrowd.com/public/production/getty-videos/3fb80454-7e05-4b5d-8c43-60c73cf4deea/3fb80454-7e05-4b5d-8c43-60c73cf4deea_optimised"
          type="video/mp4" />
      </video>

      <div class="container hero-grid">
        <div class="hero-copy reveal">
          <p class="eyebrow">El Trabajo <span>Barato</span> ya se Hizo, ahora toca el <span>bueno</span></p>
          <h1>Undefeated Roofing For El Paso Homes</h1>

          <div class="hero-actions">
            <a class="btn btn-primary" href="#inspection">Schedule Free Inspection</a>
            <a class="btn btn-secondary" href="tel:+19156301349">Call 915-630-1349</a>
          </div>

          <div class="trust-row" aria-label="Company highlights">
            <span>Woman-led</span>
            <span>Licensed, bonded & insured</span>
            <span>El Paso local</span>
          </div>
        </div>

        <aside class="hero-card reveal" aria-label="Free roof inspection request">
          <div class="card-badge">Free Inspection</div>
          <h2>Know what your roof needs before it becomes expensive.</h2>
          <p>Request a pressure-free roof evaluation. Invicta checks visible damage, wear, problem areas, and the best
            next step for your home.</p>

          <?php include __DIR__ . '/includes/forms/quick-inspection-form.php'; ?>
        </aside>
      </div>
    </section>

    <section class="proof-strip" aria-label="Service highlights">
      <div class="container proof-grid">
        <div>
          <strong>Free</strong>
          <span>Roof inspections</span>
        </div>
        <div>
          <strong>Clear</strong>
          <span>Photo documentation</span>
        </div>
        <div>
          <strong>Claims</strong>
          <span>Insurance support</span>
        </div>
        <div>
          <strong>Local</strong>
          <span>Built for El Paso homes</span>
        </div>
      </div>
    </section>

    <section class="credential-band" aria-label="Invicta Roofing credentials and affiliations">
      <div class="container credential-card reveal">
        <div class="credential-copy">
          <p class="eyebrow"><span>Trusted</span> credentials</p>
          <h2>Recognized roofing standards behind every inspection.</h2>
          <p>Invicta Roofing builds trust with manufacturer certification, industry affiliation, and a public BBB
            business profile homeowners can review before they call.</p>
        </div>

        <div class="credential-logos" aria-label="Invicta Roofing trust badges">
          <div class="credential-logo credential-logo-gaf"
            aria-label="GAF Certified Plus Residential Roofing Contractor">
            <img src="images/Certified_Plus.png" alt="GAF Certified Plus Residential Roofing Contractor" width="320"
              height="320" />
          </div>

          <div class="credential-logo credential-logo-nwir" aria-label="National Women in Roofing">
            <img src="images/nwir.png" alt="National Women in Roofing" width="420" height="150" />
          </div>

          <a class="credential-logo credential-logo-bbb"
            href="https://www.bbb.org/us/tx/el-paso/profile/roofing-contractors/invicta-roofing-0895-99174989/#sealclick"
            target="_blank" rel="nofollow noopener" aria-label="Invicta Roofing BBB Business Review">
            <img src="https://seal-elpaso.bbb.org/seals/blue-seal-153-100-bluetxt-bbb-99174989.png" style="border: 0;"
              alt="Invicta Roofing BBB Business Review" width="153" height="100" />
          </a>
          <div class="credential-logo credential-logo-gaf"
            aria-label="GAF Certified Plus Residential Roofing Contractor">
            <img src="images/epcom.png" alt="GAF Certified Plus Residential Roofing Contractor" width="320"
              height="320" />
          </div>
        </div>
      </div>
    </section>

    <section class="section services" id="services">
      <div class="container">
        <div class="section-heading reveal">
          <p class="eyebrow">Roofing services. <span>Unconquered</span> Standard</p>
          <h2>Choose the roof service that fits your situation.</h2>
          <p>Every homeowner does not need the same roofing solution. Invicta’s job is to inspect honestly, explain
            clearly, and recommend the right next step.</p>
        </div>

        <div class="service-grid">
          <article class="service-card reveal">
            <div class="service-icon" aria-hidden="true">01</div>
            <h3>Roof Inspections</h3>
            <p>Honest, thorough, and pressure-free inspections that assess roof condition, identify possible storm or
              wear damage, and explain your options clearly.</p>
            <ul>
              <li>Full roof and exterior inspection</li>
              <li>Photo documentation</li>
              <li>Straightforward recommendations</li>
            </ul>
            <a href="#inspection">Book free inspection</a>
          </article>

          <article class="service-card reveal">
            <div class="service-icon" aria-hidden="true">02</div>
            <h3>Roof Replacements</h3>
            <p>When replacement is necessary, Invicta uses appropriate materials for El Paso's weather, along with
              advanced installation techniquesthat protect against water intrusion and provide long-term protection.</p>
            <ul>
              <li>Review Scope of Work</li>
              <li>Clean, professional installation</li>
              <li>Final walkthrough and quality check</li>
            </ul>
            <a href="#inspection">Get replacement quote</a>
          </article>

          <article class="service-card reveal">
            <div class="service-icon" aria-hidden="true">03</div>
            <h3>Insurance Support</h3>
            <p>Insurance claims can be confusing. Invicta Roofing simplifies the process by helping homeowners
              understand when insurance applies and providing experienced support throughout the claims process.</p>
            <ul>
              <li>Insurance claims support</li>
              <li>Photo and report support</li>
              <li>Clear next-step guidance</li>
            </ul>
            <a href="#insurance">Learn about support</a>
          </article>

          <article class="service-card reveal">
            <div class="service-icon" aria-hidden="true">04</div>
            <h3>Repairs & Maintenance</h3>
            <p>Not every roof needs replacement. Targeted repairs and preventative maintenance can extend roof life and
              prevent future issues.</p>
            <ul>
              <li>Leak detection</li>
              <li>Minor repairs</li>
              <li>Preventative maintenance</li>
            </ul>
            <a href="#inspection">Request repair inspection</a>
          </article>
        </div>
      </div>
    </section>

    <section class="section before-after" id="results">
      <div class="container">
        <div class="section-heading reveal">
          <p class="eyebrow"><span>Before </span> &amp; <span>after</span> roof work</p>
          <h2>See the difference choosing Invicta Roofing makes.</h2>
          <p>Every roof replacement has its own story, its own challenges, and its own sense of urgency. But at Invicta,
            our approach never changes: we listen, assess, document, provide options, and use efficient processes to get
            homeowners to peace of mind as quickly as possible. <span style="font-weight: bold;">Because no matter the
              challenge, every home deserves to
              stay Invicta—unconquered.</span></p>
        </div>

        <div class="ba-grid">
          <article class="ba-card reveal">
            <div class="ba-comparison" aria-label="Before and after roof replacement project">
              <div class="ba-photo ba-before" style="--photo: url('images/before_1.png');" role="img"
                aria-label="Before roof replacement project">
                <span>Before</span>
              </div>
              <div class="ba-photo ba-after" style="--photo: url('images/after_1.png');" role="img"
                aria-label="After roof replacement project">
                <span>After</span>
              </div>
            </div>
            <div class="ba-content">
              <span>Roof replacements</span>
              <h3>From an Aging Roof to an Unconquered Home.</h3>
              <p>This 20-year-old roof was suffering from severe wear and tear, along with extensive damage from a
                previous evaporative cooler.
                Her son works in the roofing industry and understood the importance of choosing a reputable company
                known for quality craftsmanship. After receiving three quotes, she chose Invicta Roofing for our
                customer service, workmanship, trust, product quality, and competitive pricing. The quote was clear, the
                deliverables were well defined, the project was scheduled within two weeks, and the roof was completed
                in just two days.</p>
            </div>
          </article>

          <article class="ba-card reveal">
            <div class="ba-comparison" aria-label="Before and after roof repair and maintenance project">
              <div class="ba-photo ba-before" style="--photo: url('images/before_2.png');" role="img"
                aria-label="Before roof repair and maintenance project">
                <span>Before</span>
              </div>
              <div class="ba-photo ba-after" style="--photo: url('images/after_2.png');" role="img"
                aria-label="After roof repair and maintenance project">
                <span>After</span>
              </div>
            </div>
            <div class="ba-content">
              <span>Repairs &amp; maintenance</span>
              <h3>From Cheap Labor to Undefeated Quality</h3>
              <p>This 5-year-old patio extension was installed by a previous contractor without proper flashing or
                proper installation techniques. Over the years, the porch retained water, eventually causing cracks in
                the drywall.
                This family chose Invicta Roofing to correct the issue. It's never too late to recover from cheap labor.
              </p>
            </div>
          </article>

          <article class="ba-card reveal">
            <div class="ba-comparison" aria-label="Before and after roof inspection and insurance support project">
              <div class="ba-photo ba-before" style="--photo: url('images/before_3.png');" role="img"
                aria-label="Before roof inspection and insurance support project">
                <span>Before</span>
              </div>
              <div class="ba-photo ba-after" style="--photo: url('images/after_3.png');" role="img"
                aria-label="After roof inspection and insurance support project">
                <span>After</span>
              </div>
            </div>
            <div class="ba-content">
              <span>Inspection documentation</span>
              <h3>From Uninsurable to Invicta</h3>
              <p>This 20-year-old roof no longer qualified for insurance coverage. The homeowner was having difficulty
                obtaining a policy, and the mortgage company warned that the loan could go into default if insurance was
                not secured within 30 days. </p>
              <p>Invicta Roofing stepped in and provided an affordable financing option with
                payments of just **$180 per month**, allowing this retired family to replace their roof. Their home is
                now Invicta, they avoided default, and they may now qualify for better insurance coverage and more
                favorable rates.</p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section class="split section-dark" id="insurance">
      <div class="container split-grid">
        <div class="split-visual reveal" aria-hidden="true">
          <div class="roof-panel">
            <img src="images/rooftop.jpg" alt="Roofing panel with visible damage" />
          </div>
          <div class="inspection-ticket">
            <span>Inspection notes</span>
            <strong>Damage documented</strong>
            <small>Photos • report • next steps</small>
          </div>
        </div>

        <div class="split-copy reveal">
          <p class="eyebrow">Insurance support</p>
          <h2>Roofing and insurance questions should come with clear answers.</h2>
          <p>If your roof may have storm or wear damage, Invicta can inspect it, document what is visible, and help you
            understand the next steps before you make a decision.</p>

          <ul class="check-list">
            <li>Detailed roof assessment</li>
            <li>Photo and report support</li>
            <li>Repair vs. replacement guidance</li>
            <li>Clear next-step recommendations</li>
          </ul>

          <a class="btn btn-primary" href="#inspection">Start With an Honest Evaluation</a>
        </div>
      </div>
    </section>

    <section class="section why">
      <div class="container why-grid">
        <div class="why-copy reveal">
          <p class="eyebrow">Built <span>For El Paso</span>. Build for <span>What's Next</span>.</p>
          <h2>Why homeowners choose Invicta Roofing.</h2>
          <p>This border is our home—and our home is changing.
            The mountains are greener. The rains are heavier. El Paso is evolving, and many roofs here weren't built for
            what's next.
            At Invicta, we install with advanced water intrusion protection because we know this city. We build for
            today's climate, not yesterday's.
            <span style="font-weight:bold;">Homeowners choose Invicta because they want more than a new roof—they want one built to stay unconquered.</span>
            Built for El Paso. Built for what's next.
          </p>
        </div>

        <div class="why-list reveal">
          <div class="why-item">
            <strong>Woman-led, detail-driven leadership</strong>
            <span>Clear communication, organized projects, and a homeowner-first experience.</span>
          </div>
          <div class="why-item">
            <strong>Honest inspections — no pressure</strong>
            <span>You get the truth first, then options that make sense for the condition of your roof.</span>
          </div>
          <div class="why-item">
            <strong>Clean installs and quality checks</strong>
            <span>Replacement work includes professional installation, final walkthroughs, and quality checks.</span>
          </div>
          <div class="why-item">
            <strong>Local El Paso team</strong>
            <span>Built for homeowners who need durability, clarity, and protection in this region.</span>
          </div>
        </div>
      </div>
    </section>

    <section class="section reviews section-dark" id="reviews">
      <div class="container reviews-layout">
        <div class="reviews-intro reveal">
          <p class="eyebrow">Google reviews</p>
          <h2>Homeowners call out what matters most: knowing they made the right decision for decades to come.</h2>
          <p>The strongest roofing proof is not just the finished roof. It is how the homeowner feels during the
            process: fast response, clear communication, respectful crews, quality materials, and a clean property when
            the work is done.</p>

          <div class="rating-card" aria-label="Five star Google review summary">
            <div class="stars" aria-hidden="true">★★★★★</div>
            <strong>5-star homeowner feedback</strong>
            <span>Great quality. Fast response. Clean, professional work.</span>
          </div>
        </div>

        <div class="review-grid">
          <article class="review-card reveal">
            <div class="review-topline">
              <div>
                <strong>Andrea Blea</strong>
                <span>Google review • 3 months ago</span>
              </div>
              <div class="stars" aria-label="5 out of 5 stars">★★★★★</div>
            </div>
            <p>“This team is very friendly and professional. They are open and honest and will provide every possible
              option to fit your need and your budget. Inspection/Evaluation is free. They keep their word and show up
              when promised. Their work is the best quality and they take the time to follow up on their work. We are
              very happy with the services they provided. I highly recommend them!! They fixed all the errors of our
              previous contractor. My roof is beautiful! Thank you for everything.r”</p>
          </article>

          <article class="review-card reveal">
            <div class="review-topline">
              <div>
                <strong>Hdez Studio</strong>
                <span>Google review • 3 months ago</span>
              </div>
              <div class="stars" aria-label="5 out of 5 stars">★★★★★</div>
            </div>
            <p>“Excellent service, fast response clear and made me feel great working with them.”</p>
          </article>

          <article class="review-card reveal">
            <div class="review-topline">
              <div>
                <strong>Andrea Gonzales</strong>
                <span>Google review • 4 months ago</span>
              </div>
              <div class="stars" aria-label="5 out of 5 stars">★★★★★</div>
            </div>
            <p>“A great company with great people and materials ! They do a clean job &amp; very respectful . Highly
              recommend!”</p>
          </article>

          <article class="review-card reveal">
            <div class="review-topline">
              <div>
                <strong>L T</strong>
                <span>Google review • 4 months ago</span>
              </div>
              <div class="stars" aria-label="5 out of 5 stars">★★★★★</div>
            </div>
            <p>“Wow!! Excellent Customer Service. My roof looks beautiful and very clean. All staff were professional.
              Left everything clean as if no work was completed.”</p>
          </article>
        </div>

        <div class="reviews-actions reveal">
          <a class="btn btn-primary" href="PASTE_GOOGLE_REVIEWS_LINK_HERE" target="_blank" rel="noopener">
            Read All Google Reviews
          </a>

          <a class="btn btn-secondary reviews-leave-link" href="PASTE_GOOGLE_REVIEW_FORM_LINK_HERE" target="_blank"
            rel="noopener">
            Leave a Review
          </a>
        </div>
      </div>
    </section>


    <section class="quote-band section-dark">
      <div class="container quote-grid reveal">
        <p>“El trabajo <span class="lowlight">barato</span> ya se hizo. Ahora toca el <span class="highlight">bueno</span>.”</p>
        <div>
          <strong>Be protected. Be Invicta.</strong>
          <span>Sin techo no es casa.</span>
        </div>
      </div>
    </section>

    <section class="section process" id="process">
      <div class="container">
        <div class="section-heading reveal">
          <p class="eyebrow">Simple homeowner process</p>
          <h2>From inspection to long term value.</h2>
        </div>

        <div class="process-grid">
          <article class="process-card reveal">
            <span>1</span>
            <h3>Schedule your free inspection</h3>
            <p>Call, message, or submit the form. Invicta collects the basics and finds a time that works.</p>
          </article>
          <article class="process-card reveal">
            <span>2</span>
            <h3>Get a clear roof evaluation</h3>
            <p>Your roof and exterior are checked, photos are taken, and the findings are explained clearly.</p>
          </article>
          <article class="process-card reveal">
            <span>3</span>
            <h3>Choose your best next step</h3>
            <p>Inspection, replacement, insurance support, repairs, or maintenance — you get options without pressure.
            </p>
          </article>
          <article class="process-card reveal">
            <span>4</span>
            <h3>Make your home Invicta</h3>
            <p>Your roof is repaired, maintained, or replaced with clean workmanship and respect for your property.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="section cta-card" id="inspection">
      <div class="container cta-grid">
        <div class="cta-copy reveal">
          <p class="eyebrow">Free roof inspection</p>
          <h2>The people who make the best long-term decisions don't guess- they verify.</h2>
          <p>Let us inspect your roof at no cost, so you can know exactly where your home stands.</p>

          <div class="contact-cards">
            <a href="tel:+19156301349">
              <span>Call now</span>
              <strong>915-630-1349</strong>
            </a>
            <a href="mailto:Support@invictaroofs.com">
              <span>Email</span>
              <strong>Support@invictaroofs.com</strong>
            </a>
          </div>
        </div>

        <?php include __DIR__ . '/includes/forms/detailed-inspection-form.php'; ?>
      </div>
    </section>

    <section class="section faq" id="faq">
      <div class="container faq-grid">
        <div class="faq-intro reveal">
          <p class="eyebrow">Questions homeowners ask</p>
          <h2>Roofing questions, answered clearly.</h2>
        </div>

        <div class="faq-list reveal">
          <details open>
            <summary>How do I know if I need roof repair or replacement?</summary>
            <p>The best first step is an inspection. If the issue is isolated, repairs or maintenance may solve it. If
              there is widespread aging, recurring leaks, or material failure, replacement may be the better long-term
              option.</p>
          </details>
          <details>
            <summary>What happens during a roof inspection?</summary>
            <p>Invicta checks the roof and exterior, looks for visible wear or damage, documents findings with photos,
              and gives straightforward recommendations.</p>
          </details>
          <details>
            <summary>Do you help with insurance questions?</summary>
            <p>Yes. Invicta provides insurance support by documenting roof findings and helping homeowners understand
              the next steps related to their roofing situation.</p>
          </details>
          <details>
            <summary>Do you offer repairs and maintenance?</summary>
            <p>Yes. Invicta offers leak detection, minor repairs, and preventative maintenance when full replacement is
              not the right move.</p>
          </details>
          <details>
            <summary>What areas do you serve?</summary>
            <p>Invicta Roofing serves homeowners in El Paso, Texas and nearby surrounding areas.</p>
          </details>
        </div>
      </div>
    </section>


<?php include $basePath . 'includes/footer.php'; ?>