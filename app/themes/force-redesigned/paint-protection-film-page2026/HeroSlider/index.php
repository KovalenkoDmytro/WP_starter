<?php
$phone_number = get_option('company_phone');
$base_url = esc_url( get_template_directory_uri() . str_replace(get_template_directory(), '', __DIR__) );
?>


<section id="home-hero-slider" class="hero-section --ppf-packages-page2026">
  <div class="hero__slide">

      <div class="hero__overlay"></div>

      <img class="hero__image" src="<?php echo $base_url; ?>/bg-hero-image.webp" alt="Repair Services"/>

      <div class="hero__content">
        <h1 class="hero__title">
            PAINT<br>
            PROTECTION FILM
        </h1>
        <p class="hero__subtitle">Good looks are just the beginning</p>
        <p class="hero__description">
            Force Auto Styling protects your vehicle's flawless paint with SunTek Ultra PPF. This self-healing film shields against chips, scratches, and road debris, backed by a 10-year warranty. Get the best, most durable protection in Calgary.
        </p>

        <div class="hero__social">
          <a href="/contact" class="btn btn--primary hero__cta">Request a PPF Quote</a>
          <?php if ($phone_number) : ?>
          <a href="tel:<?=esc_attr($phone_number)?>" class="btn btn--outline hero__cta">Call <?=esc_html($phone_number)?></a>
          <?php endif; ?>
        </div>

      </div>

    </div>
  <div class="hero__scroll">Scroll Down ↓</div>
</section>


