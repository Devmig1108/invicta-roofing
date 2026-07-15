<form class="lead-form" action="/process-home-quick-form.php" method="post">
  <input type="hidden" name="form_token" value="<?= htmlspecialchars($formToken, ENT_QUOTES, 'UTF-8') ?>" />
  <input type="hidden" name="form_type" value="home_quick_inspection" />

  <div class="website-verification-wrap" aria-hidden="true">
    <label>
      Leave this field blank
      <input type="text" name="website_verification_code" tabindex="-1" autocomplete="off" />
    </label>
  </div>

  <?php if ($currentFormStatusTarget === 'quick' && !empty($formStatus['message'])): ?>
    <div class="form-status form-status-<?= htmlspecialchars($formStatus['type'], ENT_QUOTES, 'UTF-8') ?>">
      <?= htmlspecialchars($formStatus['message'], ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <label>
    Full name
    <input type="text" name="name" autocomplete="name" placeholder="Your name" required />
  </label>

  <label>
    Phone number
    <input type="tel" name="phone" autocomplete="tel" placeholder="915-000-0000" required />
  </label>

  <label>
    Roofing need
    <select name="service" required>
      <option value="">Select one</option>
      <option value="Roof Inspections">Roof Inspections</option>
      <option value="Roof Replacements">Roof Replacements</option>
      <option value="Insurance Support">Insurance Support</option>
      <option value="Repairs & Maintenance">Repairs & Maintenance</option>
    </select>
  </label>

  <button class="btn btn-primary btn-full" type="submit">Request My Inspection</button>
  <p class="form-note">No spam. No pressure. Just a clear roofing recommendation.</p>
</form>