<?php

declare(strict_types=1);

$rootPath = dirname(__DIR__);

$basePath = '../';
$currentPage = 'blog';

$pageTitle = 'Invicta Roofing Blog | Roofing Tips for El Paso Homeowners';
$pageDescription = 'Helpful roofing tips for El Paso homeowners from Invicta Roofing. Learn about roof inspections, roof repair, roof replacement, and insurance-related roofing questions.';
$pageKeywords = 'roofing blog El Paso, roof replacement tips, roof repair El Paso, roof inspection El Paso';
$canonicalUrl = 'https://invictaroofs.com/blog/';
$ogTitle = $pageTitle;
$ogDescription = $pageDescription;
$ogUrl = $canonicalUrl;

$posts = require $rootPath . '/includes/data/blog-posts.php';

$posts = array_values(array_filter($posts, function ($post) {
    return ($post['published'] ?? true) === true;
}));

usort($posts, function ($a, $b) {
    return strtotime($b['date'] ?? 'now') <=> strtotime($a['date'] ?? 'now');
});

$schemaJson = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Blog',
    'name' => 'Invicta Roofing Blog',
    'url' => 'https://invictaroofs.com/blog/',
    'description' => $pageDescription,
    'publisher' => [
        '@type' => 'RoofingContractor',
        'name' => 'Invicta Roofing',
        'url' => 'https://invictaroofs.com/',
        'telephone' => '+1-915-630-1349',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => '509 Giles Rd. Suite A',
            'addressLocality' => 'El Paso',
            'addressRegion' => 'TX',
            'postalCode' => '79915',
            'addressCountry' => 'US',
        ],
    ],
    'blogPost' => array_map(function ($post) {
        return [
            '@type' => 'BlogPosting',
            'headline' => $post['title'] ?? '',
            'description' => $post['description'] ?? '',
            'url' => 'https://invictaroofs.com/blog/' . ($post['slug'] ?? '') . '/',
            'datePublished' => $post['date'] ?? '',
        ];
    }, $posts),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

include $rootPath . '/includes/header.php';
?>

<section class="blog-hero section-dark">
  <div class="container blog-hero-grid">
    <div class="blog-hero-copy reveal">
      <p class="eyebrow">Invicta Roofing Blog</p>
      <h1>Roofing Tips for El Paso Homeowners</h1>
      <p>
        Simple, helpful roofing guidance for homeowners who want clearer answers before making a decision.
      </p>

      <div class="hero-actions">
        <a class="btn btn-primary" href="<?= $basePath ?>contact/">Schedule Free Inspection</a>
        <a class="btn btn-secondary" href="tel:+19156301349">Call 915-630-1349</a>
      </div>
    </div>

    <aside class="blog-hero-card reveal">
      <span class="small-label">Start here</span>
      <h2>Not sure what your roof needs?</h2>
      <p>
        A free roof inspection can help you understand whether your roof needs repair, replacement, maintenance, or further documentation.
      </p>
      <a class="btn btn-dark btn-full" href="<?= $basePath ?>contact/">Request Free Inspection</a>
    </aside>
  </div>
</section>

<section class="proof-strip" aria-label="Roofing blog topics">
  <div class="container proof-grid">
    <div>
      <strong>Tips</strong>
      <span>Roof replacement</span>
    </div>
    <div>
      <strong>Guides</strong>
      <span>Roof repair</span>
    </div>
    <div>
      <strong>Info</strong>
      <span>Roof inspections</span>
    </div>
    <div>
      <strong>Local</strong>
      <span>El Paso roofing</span>
    </div>
  </div>
</section>

<section class="section blog-index-section">
  <div class="container">
    <div class="blog-index-heading reveal">
      <p class="eyebrow">Latest roofing articles</p>
      <h2>Roofing Tips for El Paso Homeowners</h2>
      <p>
        Learn what to look for, when to call for help, and how to make a more confident roofing decision.
      </p>
    </div>

    <?php if (!empty($posts)): ?>
      <div class="blog-grid">
        <?php foreach ($posts as $post): ?>
          <?php
            $title = $post['title'] ?? '';
            $slug = $post['slug'] ?? '';
            $date = $post['date'] ?? '';
            $category = $post['category'] ?? 'Roofing';
            $image = $post['image'] ?? '';
            $url = $basePath . 'blog/' . $slug . '/';

            $formattedDate = $date !== ''
                ? date('F j, Y', strtotime($date))
                : '';
          ?>

          <article class="blog-card reveal">
            <?php if ($image !== ''): ?>
              <a class="blog-card-image" href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>">
                <img
                  src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
                  alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
                  loading="lazy"
                />
              </a>
            <?php endif; ?>

            <div class="blog-card-content">
              <div class="blog-card-meta">
                <span><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></span>

                <?php if ($formattedDate !== ''): ?>
                  <time datetime="<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8') ?>
                  </time>
                <?php endif; ?>
              </div>

              <h3>
                <a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>">
                  <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
                </a>
              </h3>

              <a class="blog-read-more" href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>">
                Read Article
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-blog-message reveal">
        <h3>No blog posts yet.</h3>
        <p>Check back soon for roofing tips from Invicta Roofing.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="section blog-service-links section-dark">
  <div class="container">
    <div class="section-heading centered reveal">
      <p class="eyebrow">Need roofing help now?</p>
      <h2>Explore Invicta Roofing services.</h2>
      <p>
        Start with the service that best fits your situation.
      </p>
    </div>

    <div class="contact-service-grid">
      <a class="contact-service-card reveal" href="<?= $basePath ?>roof-inspections/">
        <span>01</span>
        <h3>Roof Inspections</h3>
        <p>Start with a pressure-free inspection and clear roof evaluation.</p>
      </a>

      <a class="contact-service-card reveal" href="<?= $basePath ?>roof-replacement/">
        <span>02</span>
        <h3>Roof Replacement</h3>
        <p>Roof replacement built for long-term protection in El Paso.</p>
      </a>

      <a class="contact-service-card reveal" href="<?= $basePath ?>roof-repair/">
        <span>03</span>
        <h3>Roof Repair</h3>
        <p>Targeted repair options for leaks, wear, and problem areas.</p>
      </a>

      <a class="contact-service-card reveal" href="<?= $basePath ?>roof-insurance-claims-assistance/">
        <span>04</span>
        <h3>Insurance Claim Support</h3>
        <p>Documentation and guidance for roofing-related insurance questions.</p>
      </a>
    </div>
  </div>
</section>

<section class="blog-cta-clean">
  <div class="container">
    <div class="blog-cta-card reveal">
      <div class="blog-cta-copy">
        <p class="eyebrow">Have a roofing question?</p>
        <h2>Start with a free roof inspection.</h2>
        <p>
          Invicta Roofing will inspect your roof, explain what is visible, and help you understand your best next step.
        </p>
      </div>

      <div class="blog-cta-actions">
        <a class="btn btn-primary" href="<?= $basePath ?>contact/">Schedule Free Inspection</a>
        <a class="btn btn-secondary" href="tel:+19156301349">Call 915-630-1349</a>
      </div>
    </div>
  </div>
</section>

<?php include $rootPath . '/includes/footer.php'; ?>