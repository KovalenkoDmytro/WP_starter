<?php
$phone_number = get_option('company_phone');
?>

<section id="home-hero-slider" class="hero-section hero--home">
  <div class="hero__slide">

      <div class="hero__overlay"></div>

      <img class="hero__image" src="<?php echo get_template_directory_uri(); ?>/spray-in-box-liner-page2026/HeroSlider/bg-hero-image.webp" alt="Repair Services"/>

      <div class="hero__content">
        <h1 class="hero__title">
          Spray-In boxliner
        </h1>
        <p class="hero__subtitle">Protect the Box That Protects Your Gear.</p>
        <p class="hero__description">
          A professionally sprayed boxliner seals your truck box against scratches, dents, rust, and everything Calgary weather throws at it. Custom-fit to your truck, permanently bonded, and built to take a beating for years.
        </p>

        <div class="hero__social">
          <a href="/contact" class="btn btn--primary hero__cta">Book Your Repair</a>
          <?php if ($phone_number) : ?>
          <a href="tel:<?=esc_attr($phone_number)?>" class="btn btn--outline hero__cta">Call <?=esc_html($phone_number)?></a>
          <?php endif; ?>
        </div>

      </div>

    </div>
  <div class="hero__scroll">Scroll Down ↓</div>
</section>


