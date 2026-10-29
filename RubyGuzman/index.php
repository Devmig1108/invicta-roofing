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
    'success' => ['success', 'notice_success', 'Request received. Invicta Roofing will be in touch soon.'],
    'error' => ['error', 'notice_error', 'We could not send your request. Please call 915-630-1349 or try again.'],
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
    <a class="brand" href="https://invictaroofs.com/" aria-label="Invicta Roofing home" data-i18n-aria-label="brand_home">
      <img class="brand-logo" src="/images/logo.png" alt="Invicta Roofing">
    </a>
    <div class="header-actions">
      <button class="language-toggle" type="button" id="language-toggle" aria-label="Cambiar a español" aria-pressed="false">ESPAÑOL</button>
      <a class="header-call" href="tel:+19156301349"><span data-i18n="talk_to_team">Talk to our team</span><strong>915-630-1349</strong></a>
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="hero-noise" aria-hidden="true"></div>
      <div class="hero-inner">
        <div class="hero-copy">
          <p class="eyebrow"><span class="eyebrow-line"></span> <span data-i18n="hero_eyebrow">Team Invicta · Ambassador referral</span></p>
          <p class="draft-label" data-i18n="draft_label">YOU’VE BEEN REFERRED</p>
          <h1><span data-i18n="hero_title_top">Good people.</span><br><span data-i18n="hero_title_bottom">Undefeated homes.</span></h1>
          <p class="hero-intro" data-i18n="hero_intro">Ruby Guzman trusts Invicta Roofing. Now her community has a direct line to a local team that puts honest guidance and quality work first.</p>
          <a class="button button-primary" href="#inspection"><span data-i18n="hero_cta">Request a roof inspection</span><span aria-hidden="true">→</span></a>
          <div class="hero-proof"><span class="proof-star">✦</span><span data-i18n="hero_proof">Locally serving El Paso homeowners</span></div>
        </div>

        <aside class="player-card" aria-label="Ruby Guzman ambassador profile" data-i18n-aria-label="player_profile">
          <div class="card-topline"><span data-i18n="team_invicta">TEAM INVICTA</span><span class="card-season" data-i18n="ambassador_001">AMBASSADOR · 001</span></div>
          <div class="photo-placeholder"><img class="ambassador-photo" src="images/Ruby.jpg" alt="Ruby Guzman, Team Invicta ambassador" data-i18n-alt="ruby_photo_alt"></div>
          <div class="card-bottom">
            <div><span class="card-kicker" data-i18n="the_undefeated">THE UNDEFEATED</span><h2>Ruby Guzman</h2></div>
            <span class="card-number">001</span>
          </div>
          <div class="card-accent"></div>
        </aside>
      </div>
      <div class="hero-footer"><span data-i18n="invited_by_ruby">INVITED BY RUBY</span><span data-i18n="built_for_el_paso">BUILT FOR EL PASO</span><span data-i18n="built_to_stay">BUILT TO STAY UNDEFEATED</span></div>
    </section>

    <section class="trust-section">
      <div class="trust-inner">
        <div class="trust-mark" aria-hidden="true">“</div>
        <div class="trust-copy">
          <p class="eyebrow eyebrow-dark" data-i18n="ruby_note">A note from Ruby</p>
          <h2 data-i18n="trust_heading">Why Ruby trusts Invicta Roofing</h2>
          <p class="quote-placeholder" data-i18n="quote_placeholder">[Client-provided statement goes here. For example: “I trust Invicta because they explain the work clearly, treat people with respect, and stand behind the quality of their roofs.”]</p>
          <span class="signature">Ruby Guzman <small data-i18n="ambassador_title">Team Invicta Ambassador</small></span>
        </div>
        <div class="trust-aside"><span class="aside-number">01</span><span data-i18n="local_trust">LOCAL<br>TRUST</span><i></i><span data-i18n="real_people">REAL<br>PEOPLE</span></div>
      </div>
    </section>

    <section class="form-section" id="inspection">
      <div class="form-layout">
        <div class="form-intro">
          <p class="eyebrow"><span class="eyebrow-line"></span><span data-i18n="form_eyebrow">Start with a clear answer</span></p>
          <h2><span data-i18n="form_title_top">Let’s take a look</span><br><span data-i18n="form_title_bottom">at your roof.</span></h2>
          <p data-i18n="form_intro">Request a free roof inspection. Invicta will review your request and contact you to coordinate a convenient time.</p>
          <ul class="form-benefits">
            <li><span>01</span><span data-i18n="benefit_one">No-cost roof inspection</span></li>
            <li><span>02</span><span data-i18n="benefit_two">Clear photos and recommendations</span></li>
            <li><span>03</span><span data-i18n="benefit_three">No-pressure next steps</span></li>
          </ul>
          <div class="phone-note"><span data-i18n="prefer_call">Prefer to call?</span><a href="tel:+19156301349">915-630-1349</a></div>
        </div>

        <div class="form-card">
          <div class="form-card-heading"><div><span class="form-step" data-i18n="inspection_request">INSPECTION REQUEST</span><h3 data-i18n="form_card_title">Tell us a little about your home.</h3></div><span class="form-badge" data-i18n="free">FREE</span></div>

          <?php if ($notice !== null): ?>
            <div class="form-notice form-notice-<?= htmlspecialchars($notice[0], ENT_QUOTES, 'UTF-8') ?>" data-i18n="<?= htmlspecialchars($notice[1], ENT_QUOTES, 'UTF-8') ?>" role="status"><?= htmlspecialchars($notice[2], ENT_QUOTES, 'UTF-8') ?></div>
          <?php endif; ?>

          <?php if ($formToken === ''): ?>
            <div class="form-notice form-notice-error" data-i18n="form_unavailable" role="alert">The request form is temporarily unavailable. Please call 915-630-1349.</div>
          <?php else: ?>
          <form action="process-ruby-form.php" method="post" class="lead-form">
            <input type="hidden" name="form_token" value="<?= htmlspecialchars($formToken, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="form_type" value="ruby_ambassador_inspection">
            <input type="hidden" name="ambassador_id" value="AMB001">
            <input type="hidden" name="ambassador_name" value="Ruby Guzman">
            <input type="hidden" name="lead_source" value="Brand Ambassador">
            <input type="hidden" name="referral_url" value="<?= htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8') ?>">
            <div class="hp-wrap" aria-hidden="true"><label><span data-i18n="leave_blank">Leave blank</span><input type="text" name="website_verification_code" tabindex="-1" autocomplete="off"></label></div>

            <div class="field-grid">
              <label><span data-i18n="full_name">Full name</span><input type="text" name="full_name" autocomplete="name" maxlength="120" required></label>
              <label><span data-i18n="phone_number">Phone number</span><input type="tel" name="phone" autocomplete="tel" maxlength="30" required></label>
            </div>
            <label><span data-i18n="email_address">Email address</span><input type="email" name="email" autocomplete="email" maxlength="254" required></label>
            <label><span data-i18n="property_address">Property address</span><input type="text" name="property_address" autocomplete="street-address" maxlength="220" placeholder="Street address, El Paso, TX" data-i18n-placeholder="address_placeholder" required></label>
            <label><span data-i18n="service_question">What can we help with?</span>
              <select name="roofing_need" required>
                <option value="" data-i18n="choose_need">Choose a roofing need</option>
                <option value="Roof Inspections" data-i18n="roof_inspection">Roof inspection</option>
                <option value="Roof Replacements" data-i18n="roof_replacement">Roof replacement</option>
                <option value="Insurance Support" data-i18n="insurance_support">Insurance support</option>
                <option value="Repairs &amp; Maintenance" data-i18n="repairs_maintenance">Repairs &amp; maintenance</option>
              </select>
            </label>
            <label><span><span data-i18n="anything_else">Anything else we should know?</span> <span class="optional" data-i18n="optional">Optional</span></span><textarea name="message" rows="3" maxlength="1500" placeholder="Share any visible damage, leaks, or questions." data-i18n-placeholder="notes_placeholder"></textarea></label>
            <button class="button button-submit" type="submit"><span data-i18n="submit_request">Request my free inspection</span><span aria-hidden="true">→</span></button>
            <p class="privacy-note" data-i18n="privacy_note">By submitting, you agree Invicta Roofing may contact you about this inspection request.</p>
          </form>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <a class="brand brand-footer" href="https://invictaroofs.com/" aria-label="Invicta Roofing home" data-i18n-aria-label="brand_home"><img class="brand-logo" src="/images/logo.png" alt="Invicta Roofing"></a>
    <p data-i18n="footer_tagline">Be protected. Be Invicta.</p>
    <a href="tel:+19156301349">915-630-1349</a>
    <small>© <?= date('Y') ?> Invicta Roofing · El Paso, Texas</small>
  </footer>
  <script>
    const pageCopy = {
      en: {
        brand_home: 'Invicta Roofing home', player_profile: 'Ruby Guzman ambassador profile', ruby_photo_alt: 'Ruby Guzman, Team Invicta ambassador',
        talk_to_team: 'Talk to our team', hero_eyebrow: 'Team Invicta · Ambassador referral', draft_label: 'YOU’VE BEEN REFERRED',
        hero_title_top: 'Good people.', hero_title_bottom: 'Undefeated homes.',
        hero_intro: 'Ruby Guzman trusts Invicta Roofing. Now her community has a direct line to a local team that puts honest guidance and quality work first.',
        hero_cta: 'Request a roof inspection', hero_proof: 'Locally serving El Paso homeowners', team_invicta: 'TEAM INVICTA',
        ambassador_001: 'AMBASSADOR · 001', the_undefeated: 'THE UNDEFEATED', invited_by_ruby: 'INVITED BY RUBY',
        built_for_el_paso: 'BUILT FOR EL PASO', built_to_stay: 'BUILT TO STAY UNDEFEATED', ruby_note: 'A note from Ruby',
        trust_heading: 'Why Ruby trusts Invicta Roofing',
        quote_placeholder: '[Client-provided statement goes here. For example: “I trust Invicta because they explain the work clearly, treat people with respect, and stand behind the quality of their roofs.”]',
        ambassador_title: 'Team Invicta Ambassador', local_trust: 'LOCAL<br>TRUST', real_people: 'REAL<br>PEOPLE',
        form_eyebrow: 'Start with a clear answer', form_title_top: 'Let’s take a look', form_title_bottom: 'at your roof.',
        form_intro: 'Request a free roof inspection. Invicta will review your request and contact you to coordinate a convenient time.',
        benefit_one: 'No-cost roof inspection', benefit_two: 'Clear photos and recommendations', benefit_three: 'No-pressure next steps',
        prefer_call: 'Prefer to call?', inspection_request: 'INSPECTION REQUEST', form_card_title: 'Tell us a little about your home.', free: 'FREE',
        notice_success: 'Request received. Invicta Roofing will be in touch soon.',
        notice_error: 'We could not send your request. Please call 915-630-1349 or try again.',
        form_unavailable: 'The request form is temporarily unavailable. Please call 915-630-1349.', leave_blank: 'Leave blank',
        full_name: 'Full name', phone_number: 'Phone number', email_address: 'Email address', property_address: 'Property address',
        address_placeholder: 'Street address, El Paso, TX', service_question: 'What can we help with?', choose_need: 'Choose a roofing need',
        roof_inspection: 'Roof inspection', roof_replacement: 'Roof replacement', insurance_support: 'Insurance support',
        repairs_maintenance: 'Repairs & maintenance', anything_else: 'Anything else we should know?', optional: 'Optional',
        notes_placeholder: 'Share any visible damage, leaks, or questions.', submit_request: 'Request my free inspection',
        privacy_note: 'By submitting, you agree Invicta Roofing may contact you about this inspection request.',
        footer_tagline: 'Be protected. Be Invicta.'
      },
      es: {
        brand_home: 'Inicio de Invicta Roofing', player_profile: 'Perfil de Ruby Guzman, embajadora', ruby_photo_alt: 'Ruby Guzman, embajadora del Equipo Invicta',
        talk_to_team: 'Comunícate con nuestro equipo', hero_eyebrow: 'Equipo Invicta · Referido por embajadora', draft_label: 'RUBY TE RECOMIENDA',
        hero_title_top: 'Gente de confianza.', hero_title_bottom: 'Hogares protegidos.',
        hero_intro: 'Ruby Guzman confía en Invicta Roofing. Ahora su comunidad tiene acceso directo a un equipo local que prioriza la honestidad y el trabajo de calidad.',
        hero_cta: 'Solicita una inspección', hero_proof: 'Al servicio de los propietarios de El Paso', team_invicta: 'EQUIPO INVICTA',
        ambassador_001: 'EMBAJADORA · 001', the_undefeated: 'INVICTA · INVENCIBLE', invited_by_ruby: 'RECOMENDADO POR RUBY',
        built_for_el_paso: 'HECHO PARA EL PASO', built_to_stay: 'PROTECCIÓN QUE PERDURA', ruby_note: 'Un mensaje de Ruby',
        trust_heading: 'Por qué Ruby confía en Invicta Roofing',
        quote_placeholder: '[Aquí irá el mensaje aprobado por la clienta. Por ejemplo: “Confío en Invicta porque explican el trabajo con claridad, tratan a las personas con respeto y respaldan la calidad de sus techos.”]',
        ambassador_title: 'Embajadora del Equipo Invicta', local_trust: 'CONFIANZA<br>LOCAL', real_people: 'PERSONAS<br>REALES',
        form_eyebrow: 'Empieza con una respuesta clara', form_title_top: 'Revisemos', form_title_bottom: 'tu techo.',
        form_intro: 'Solicita una inspección gratuita del techo. Invicta revisará tu solicitud y se comunicará contigo para coordinar una visita.',
        benefit_one: 'Inspección del techo sin costo', benefit_two: 'Fotos claras y recomendaciones', benefit_three: 'Sin presión para decidir',
        prefer_call: '¿Prefieres llamar?', inspection_request: 'SOLICITUD DE INSPECCIÓN', form_card_title: 'Cuéntanos un poco sobre tu hogar.', free: 'GRATIS',
        notice_success: 'Recibimos tu solicitud. Invicta Roofing se comunicará contigo pronto.',
        notice_error: 'No pudimos enviar tu solicitud. Llama al 915-630-1349 o inténtalo de nuevo.',
        form_unavailable: 'El formulario no está disponible temporalmente. Llama al 915-630-1349.', leave_blank: 'Déjalo en blanco',
        full_name: 'Nombre completo', phone_number: 'Número de teléfono', email_address: 'Correo electrónico', property_address: 'Dirección de la propiedad',
        address_placeholder: 'Dirección, El Paso, TX', service_question: '¿Cómo podemos ayudarte?', choose_need: 'Elige un servicio de techo',
        roof_inspection: 'Inspección de techo', roof_replacement: 'Reemplazo de techo', insurance_support: 'Apoyo con el seguro',
        repairs_maintenance: 'Reparaciones y mantenimiento', anything_else: '¿Hay algo más que debamos saber?', optional: 'Opcional',
        notes_placeholder: 'Cuéntanos sobre daños visibles, goteras o preguntas.', submit_request: 'Solicitar mi inspección gratuita',
        privacy_note: 'Al enviar, aceptas que Invicta Roofing se comunique contigo sobre esta solicitud de inspección.',
        footer_tagline: 'Protege tu hogar. Elige Invicta.'
      }
    };
    const languageButton = document.getElementById('language-toggle');
    function setPageLanguage(language) {
      const copy = pageCopy[language] || pageCopy.en;
      document.documentElement.lang = language;
      document.querySelectorAll('[data-i18n]').forEach((element) => {
        const value = copy[element.dataset.i18n];
        if (value !== undefined) element.innerHTML = value;
      });
      document.querySelectorAll('[data-i18n-placeholder]').forEach((element) => {
        const value = copy[element.dataset.i18nPlaceholder];
        if (value !== undefined) element.placeholder = value;
      });
      document.querySelectorAll('[data-i18n-aria-label]').forEach((element) => {
        const value = copy[element.dataset.i18nAriaLabel];
        if (value !== undefined) element.setAttribute('aria-label', value);
      });
      document.querySelectorAll('[data-i18n-alt]').forEach((element) => {
        const value = copy[element.dataset.i18nAlt];
        if (value !== undefined) element.alt = value;
      });
      languageButton.textContent = language === 'en' ? 'ESPAÑOL' : 'ENGLISH';
      languageButton.setAttribute('aria-label', language === 'en' ? 'Cambiar a español' : 'Switch to English');
      languageButton.setAttribute('aria-pressed', language === 'es' ? 'true' : 'false');
      document.title = language === 'en' ? 'Ruby Guzman | Team Invicta' : 'Ruby Guzman | Equipo Invicta';
      document.querySelector('meta[name="description"]').content = language === 'en'
        ? 'Connect with Invicta Roofing through Ruby Guzman\'s Team Invicta page and request a roof inspection.'
        : 'Comunícate con Invicta Roofing a través de la página de Ruby Guzman y solicita una inspección de techo.';
      try { localStorage.setItem('rubyPageLanguage', language); } catch (error) {}
    }
    languageButton.addEventListener('click', () => {
      setPageLanguage(document.documentElement.lang === 'en' ? 'es' : 'en');
    });
    let savedLanguage = 'en';
    try { savedLanguage = localStorage.getItem('rubyPageLanguage') === 'es' ? 'es' : 'en'; } catch (error) {}
    setPageLanguage(savedLanguage);
  </script>
</body>
</html>
