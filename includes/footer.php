  </main>

  <footer class="footer section-dark">
    <div class="container footer-grid">
      <div>
        <a class="brand footer-brand" href="<?= $basePath ?>" aria-label="Invicta Roofing home">
          <img class="brand-logo footer-logo" src="<?= $basePath ?>images/logo_md.png" alt="Invicta Roofing" width="280" height="84"
            loading="lazy" />
        </a>
        <p>Undefeated roofing for El Paso homes. Family-approved. Built to last.</p>
      </div>

      <div>
        <h2>Services</h2>
        <a href="<?= $basePath ?>roof-inspections/">Roof Inspections</a>
        <a href="<?= $basePath ?>roof-replacement/">Roof Replacements</a>
        <a href="<?= $basePath ?>roof-repair/">Roof Repairs</a>
        <a href="<?= $basePath ?>roof-coatings/">Roof Coatings</a>
        <a href="<?= $basePath ?>roof-insurance-claims-assistance/">Insurance Claims Assistance</a>
      </div>

      <div>
        <h2>Contact</h2>
        <p>509 Giles Rd. Suite A<br />El Paso, TX 79915</p>
        <a href="tel:+19156301349">915-630-1349</a>
        <a href="mailto:Support@invictaroofs.com">Support@invictaroofs.com</a>
        <a href="https://www.facebook.com/profile.php?id=61587098842468" target="_blank" rel="noopener">Facebook</a>
        <a href="https://www.instagram.com/invictaroofing915/" target="_blank" rel="noopener">Instagram</a>
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

    document.querySelectorAll('.nav-dropdown-toggle').forEach((button) => {
      button.addEventListener('click', (event) => {
        event.stopPropagation();

        const dropdown = button.closest('.nav-dropdown');
        const isOpen = dropdown.classList.contains('is-open');

        document.querySelectorAll('.nav-dropdown').forEach((item) => {
          item.classList.remove('is-open');

          const itemButton = item.querySelector('.nav-dropdown-toggle');

          if (itemButton) {
            itemButton.setAttribute('aria-expanded', 'false');
          }
        });

        dropdown.classList.toggle('is-open', !isOpen);
        button.setAttribute('aria-expanded', String(!isOpen));
      });
    });

    document.addEventListener('click', () => {
      document.querySelectorAll('.nav-dropdown').forEach((dropdown) => {
        dropdown.classList.remove('is-open');

        const button = dropdown.querySelector('.nav-dropdown-toggle');

        if (button) {
          button.setAttribute('aria-expanded', 'false');
        }
      });
    });

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