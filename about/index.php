<?php

declare(strict_types=1);

$basePath = '../';
$currentPage = 'about';

$pageTitle = 'About Invicta Roofing | El Paso Roofing Company';
$pageDescription = 'Learn about Invicta Roofing, a woman-led El Paso roofing company providing roof inspections, replacements, repairs, maintenance, coatings, and insurance support.';
$pageKeywords = 'about Invicta Roofing, El Paso roofing company, woman-led roofing company El Paso, roof inspections El Paso, roof replacement El Paso';
$canonicalUrl = 'https://invictaroofs.com/about/';
$ogTitle = 'About Invicta Roofing | El Paso Roofing Company';
$ogDescription = 'Invicta Roofing is a woman-led roofing company serving El Paso homeowners with honest inspections, clear recommendations, and roofing work built to last.';
$ogUrl = 'https://invictaroofs.com/about/';

$schemaJson = <<<'JSON'
{
  "@context": "https://schema.org",
  "@type": "AboutPage",
  "name": "About Invicta Roofing",
  "url": "https://invictaroofs.com/about/",
  "description": "Learn about Invicta Roofing, a woman-led roofing company serving El Paso homeowners.",
  "mainEntity": {
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
    "areaServed": "El Paso, Texas"
  }
}
JSON;

include __DIR__ . '/../includes/header.php';
?>
        <section class="about-hero section-dark">
            <div class="container about-hero-grid">
                <div class="about-hero-copy reveal">
                    <p class="eyebrow">About Invicta Roofing</p>
                    <h1>El Paso Roofing Company Built for Local Homes</h1>
                    <p>
                        Invicta Roofing is a woman-led roofing company in El Paso, Texas, helping homeowners with roof
                        inspections, roof replacements, repairs, coatings, and insurance-related roofing support.
                    </p>

                    <div class="hero-actions">
                        <a class="btn btn-primary" href="/contact/">Schedule Free Inspection</a>
                        <a class="btn btn-secondary" href="tel:+19156301349">Call 915-630-1349</a>
                    </div>

                    <div class="trust-row" aria-label="Company highlights">
                        <span>Woman-led</span>
                        <span>Licensed, bonded & insured</span>
                        <span>El Paso local</span>
                    </div>
                </div>

                <div class="about-hero-media reveal">
                    <img src="<?= $basePath ?>images/rooftop.jpg" alt="Invicta Roofing roof inspection work in El Paso" />
                    <div class="about-media-card">
                        <span>Invicta Standard</span>
                        <strong>Clear answers before big decisions.</strong>
                    </div>
                </div>
            </div>
        </section>

        <section class="proof-strip" aria-label="Invicta Roofing values">
            <div class="container proof-grid">
                <div>
                    <strong>Honest</strong>
                    <span>Roof evaluations</span>
                </div>
                <div>
                    <strong>Clear</strong>
                    <span>Photo documentation</span>
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

        <section class="section about-story">
            <div class="container about-story-grid">
                <div class="about-story-copy reveal">
                    <p class="eyebrow">Who we are</p>
                    <h2>A roofing company built around trust, clarity, and follow-through.</h2>
                    <p>
                        Roofing decisions can feel stressful because most homeowners do not look at their roof every
                        day.
                        They only know something may be wrong when they see a leak, notice damage, receive insurance
                        concerns,
                        or realize the roof has aged beyond what they expected.
                    </p>
                    <p>
                        Invicta Roofing exists to make that process clearer. The first step is not pressure. The first
                        step is
                        understanding what is happening, documenting what is visible, and explaining the best next
                        option.
                    </p>
                    <p>
                        Whether the answer is maintenance, repair, coating, replacement, or insurance-related
                        documentation,
                        the goal stays the same: help homeowners protect the place they call home.
                    </p>
                </div>

                <div class="about-principles reveal">
                    <div class="principle-card">
                        <span>01</span>
                        <h3>We inspect before we recommend.</h3>
                        <p>Every roof has its own condition, age, history, and urgency. The right recommendation starts
                            with seeing what is actually there.</p>
                    </div>

                    <div class="principle-card">
                        <span>02</span>
                        <h3>We explain without pressure.</h3>
                        <p>Homeowners deserve clear roofing options, not scare tactics or rushed decisions.</p>
                    </div>

                    <div class="principle-card">
                        <span>03</span>
                        <h3>We build for what comes next.</h3>
                        <p>El Paso weather is changing. Roofing work should be planned for durability, drainage, water
                            intrusion protection, and long-term value.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section section-dark about-belief">
            <div class="container about-belief-grid">
                <div class="about-belief-card reveal">
                    <p>“El trabajo <span class="lowlight">barato</span> ya se hizo. Ahora toca el <span
                            class="highlight">bueno</span>.”</p>
                </div>

                <div class="about-belief-copy reveal">
                    <p class="eyebrow">What Invicta means</p>
                    <h2>Built to protect El Paso homes.</h2>
                    <p>
                        Invicta means undefeated. For homeowners, that idea is simple: your roof should protect the home,
                        support long-term value, and give your family confidence through El Paso weather.
                    </p>
                    <p>
                        That standard shows up in the way Invicta inspects, communicates, documents, and completes
                        roofing work.
                        The goal is not just to finish a project. The goal is to leave the homeowner confident in the
                        decision they made.
                    </p>
                </div>
            </div>
        </section>

        <section class="credential-band about-credentials" aria-label="Invicta Roofing credentials and affiliations">
            <div class="container credential-card reveal">
                <div class="credential-copy">
                    <p class="eyebrow"><span>Trusted</span> credentials</p>
                    <h2>Recognized roofing standards behind the work.</h2>
                    <p>
                        Invicta Roofing builds homeowner confidence with manufacturer certification, industry
                        affiliation,
                        and public business profiles that customers can review before they call.
                    </p>
                </div>

                <div class="credential-logos" aria-label="Invicta Roofing trust badges">
                    <div class="credential-logo credential-logo-gaf"
                        aria-label="GAF Certified Plus Residential Roofing Contractor">
                        <img src="<?= $basePath ?>images/Certified_Plus.png" alt="GAF Certified Plus Residential Roofing Contractor"
                            width="320" height="320" />
                    </div>

                    <div class="credential-logo credential-logo-nwir" aria-label="National Women in Roofing">
                        <img src="<?= $basePath ?>images/nwir.png" alt="National Women in Roofing" width="420" height="150" />
                    </div>

                    <a class="credential-logo credential-logo-bbb"
                        href="https://www.bbb.org/us/tx/el-paso/profile/roofing-contractors/invicta-roofing-0895-99174989/#sealclick"
                        target="_blank" rel="nofollow noopener" aria-label="Invicta Roofing BBB Business Review">
                        <img src="https://seal-elpaso.bbb.org/seals/blue-seal-153-100-bluetxt-bbb-99174989.png"
                            style="border: 0;" alt="Invicta Roofing BBB Business Review" width="153" height="100" />
                    </a>

                    <div class="credential-logo credential-logo-gaf">
                        <img src="<?= $basePath ?>images/epcom.png" alt="El Paso Chamber or business affiliation badge" width="320"
                            height="320" />
                    </div>
                </div>
            </div>
        </section>

        <section class="section about-process">
            <div class="container">
                <div class="section-heading centered reveal">
                    <p class="eyebrow">How we work</p>
                    <h2>A clearer process for homeowners.</h2>
                    <p>
                        The Invicta process is designed to help homeowners move from uncertainty to a clear roofing
                        decision.
                    </p>
                </div>

                <div class="process-grid">
                    <article class="process-card reveal">
                        <span>1</span>
                        <h3>Start with a free inspection</h3>
                        <p>Invicta checks the condition of the roof and listens to what the homeowner is seeing or
                            experiencing.</p>
                    </article>

                    <article class="process-card reveal">
                        <span>2</span>
                        <h3>Document what is visible</h3>
                        <p>Photos and notes help make the roofing conversation clearer and easier to understand.</p>
                    </article>

                    <article class="process-card reveal">
                        <span>3</span>
                        <h3>Explain the right options</h3>
                        <p>Repair, maintenance, coating, replacement, or insurance-related next steps are explained
                            without pressure.</p>
                    </article>

                    <article class="process-card reveal">
                        <span>4</span>
                        <h3>Complete the work with care</h3>
                        <p>When work is needed, Invicta focuses on clean communication, quality installation, and
                            respect for the home.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section about-values section-dark">
            <div class="container about-values-grid">
                <div class="about-values-copy reveal">
                    <p class="eyebrow">What homeowners should expect</p>
                    <h2>Roofing help without confusion.</h2>
                    <p>
                        Invicta Roofing is built for homeowners who want straight answers, a clear process, and a roof
                        they can feel confident about.
                    </p>
                </div>

                <div class="value-list reveal">
                    <div class="value-item">
                        <strong>Respect for the homeowner</strong>
                        <span>Clear communication and no-pressure recommendations.</span>
                    </div>

                    <div class="value-item">
                        <strong>Respect for the property</strong>
                        <span>Clean work, careful project handling, and attention to the details that matter.</span>
                    </div>

                    <div class="value-item">
                        <strong>Respect for the decision</strong>
                        <span>Homeowners get information first, then options that make sense.</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="section final-cta">
            <div class="container final-card reveal">
                <div>
                    <p class="eyebrow">Ready for clear roofing answers?</p>
                    <h2>Start with a free roof inspection.</h2>
                    <p>
                        Invicta Roofing will inspect your roof, explain what is visible, and help you understand your
                        best next step.
                    </p>
                </div>
                <div class="final-actions">
                    <a class="btn btn-primary" href="/contact/">Schedule Free Inspection</a>
                    <a class="btn btn-secondary dark" href="tel:+19156301349">Call 915-630-1349</a>
                </div>
            </div>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>