<?php

$basePath = $basePath ?? '';
$pageTitle = $pageTitle ?? 'Invicta Roofing | El Paso Roofing Company';
$pageDescription = $pageDescription ?? 'Invicta Roofing is an El Paso roofing company offering roof inspections, roof replacements, roof repairs, roof coatings, and insurance support.';
$pageKeywords = $pageKeywords ?? 'roofing El Paso, roof inspection El Paso, roof replacement El Paso, roof repair El Paso';
$canonicalUrl = $canonicalUrl ?? 'https://invictaroofs.com/';
$ogTitle = $ogTitle ?? $pageTitle;
$ogDescription = $ogDescription ?? $pageDescription;
$ogUrl = $ogUrl ?? $canonicalUrl;
$ogImage = $ogImage ?? '';
$currentPage = $currentPage ?? '';
$schemaJson = $schemaJson ?? '';

function navActive(string $page, string $currentPage): string
{
    return $page === $currentPage ? ' class="is-active"' : '';
}

$isServicePage = in_array($currentPage, [
    'roof-replacement',
    'roof-repair',
    'roof-coatings',
    'roof-inspections',
    'roof-insurance-claims-assistance',
], true);

$serviceClass = $isServicePage ? 'nav-dropdown is-active' : 'nav-dropdown';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>" />
  <meta name="keywords" content="<?= htmlspecialchars($pageKeywords, ENT_QUOTES, 'UTF-8') ?>" />
  <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>" />
  <link rel="icon" type="image/png" href="<?= $basePath ?>images/logo.png">

  <meta property="og:title" content="<?= htmlspecialchars($ogTitle, ENT_QUOTES, 'UTF-8') ?>" />
  <meta property="og:description" content="<?= htmlspecialchars($ogDescription, ENT_QUOTES, 'UTF-8') ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?= htmlspecialchars($ogUrl, ENT_QUOTES, 'UTF-8') ?>" />

  <?php if ($ogImage !== ''): ?>
    <meta property="og:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>" />
  <?php endif; ?>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800;900&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="<?= $basePath ?>styles.css" />

  <?php if ($schemaJson !== ''): ?>
  <script type="application/ld+json">
<?= $schemaJson . PHP_EOL ?>
  </script>
  <?php endif; ?>
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
      <a class="brand" href="<?= $basePath ?>" aria-label="Invicta Roofing home">
        <img class="brand-logo" src="<?= $basePath ?>images/logo_md_dark.svg" alt="Invicta Roofing" width="280" height="84" />
      </a>

      <button class="nav-toggle" type="button" aria-label="Open menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <div class="nav-menu" id="nav-menu">
        <a href="<?= $basePath ?>"<?= navActive('home', $currentPage) ?>>Home</a>
        <a href="<?= $basePath ?>about/"<?= navActive('about', $currentPage) ?>>About</a>

        <div class="<?= $serviceClass ?>">
          <button class="nav-dropdown-toggle" type="button" aria-expanded="false">
            Services
          </button>

          <div class="nav-dropdown-menu">
            <a href="<?= $basePath ?>roof-replacement/"<?= navActive('roof-replacement', $currentPage) ?>>Roof Replacement</a>
            <a href="<?= $basePath ?>roof-repair/"<?= navActive('roof-repair', $currentPage) ?>>Roof Repair</a>
            <a href="<?= $basePath ?>roof-coatings/"<?= navActive('roof-coatings', $currentPage) ?>>Roof Coatings</a>
            <a href="<?= $basePath ?>roof-inspections/"<?= navActive('roof-inspections', $currentPage) ?>>Roof Inspections</a>
            <a href="<?= $basePath ?>roof-insurance-claims-assistance/"<?= navActive('roof-insurance-claims-assistance', $currentPage) ?>>Insurance Claims Assistance</a>
          </div>
        </div>

        <a href="<?= $basePath ?>roofingquincepromo/quinceanera-giveaway.php"<?= navActive('giveaway', $currentPage) ?>>Giveaway</a>
        <a href="<?= $basePath ?>contact/"<?= navActive('contact', $currentPage) ?>>Contact</a>
      </div>

      <a class="nav-call" href="tel:+19156301349">915-630-1349</a>
    </nav>
  </header>

  <main id="main">