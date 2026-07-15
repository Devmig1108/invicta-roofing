<form class="lead-form large-form reveal" action="/process-home-detailed-form.php" method="post">
  <input type="hidden" name="form_token" value="<?= htmlspecialchars($formToken, ENT_QUOTES, 'UTF-8') ?>" />
  <input type="hidden" name="form_type" value="home_detailed_inspection" />

  <div class="website-verification-wrap" aria-hidden="true">
    <label>
      Leave this field blank
      <input type="text" name="website_verification_code" tabindex="-1" autocomplete="off" />
    </label>
  </div>

  <?php if ($currentFormStatusTarget === 'detailed' && !empty($formStatus['message'])): ?>
    <div class="form-status form-status-<?= htmlspecialchars($formStatus['type'], ENT_QUOTES, 'UTF-8') ?>">
      <?= htmlspecialchars($formStatus['message'], ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <div class="form-grid">
    <label>
      Full name
      <input type="text" name="fullName" autocomplete="name" required />
    </label>

    <label>
      Phone
      <input type="tel" name="phone" autocomplete="tel" required />
    </label>
  </div>

  <label>
    Property address
    <input type="text" name="address" autocomplete="street-address" placeholder="Street address, El Paso, TX" required />
  </label>

  <label>
    What do you need help with?
    <select name="roofingNeed" required>
      <option value="">Choose one</option>
      <option value="Roof Inspections">Roof Inspections</option>
      <option value="Roof Replacements">Roof Replacements</option>
      <option value="Insurance Support">Insurance Support</option>
      <option value="Repairs & Maintenance">Repairs & Maintenance</option>
    </select>
  </label>

  <label>
    How did you hear about us?
    <textarea name="heardAbout" rows="3" placeholder="Tell us how you found out about Invicta Roofing."></textarea>
  </label>

  <label>
    Notes
    <textarea name="message" rows="4" placeholder="Tell us about leaks, missing shingles, roof age, visible damage, or maintenance concerns."></textarea>
  </label>

  <button class="btn btn-primary btn-full" type="submit">Schedule My Free Inspection</button>
  <p class="form-note">By submitting, you agree to be contacted about your roof inspection request.</p>
</form>