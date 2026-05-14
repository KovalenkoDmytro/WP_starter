<?php
$phone_number = get_option('company_phone');
?>

<section id="home-hero-slider" class="hero-section hero--home">
  <div class="hero__slide">

      <div class="hero__overlay"></div>

      <img class="hero__image" src="<?php echo get_template_directory_uri(); ?>/mechanical-services-page2026/HeroSlider/bg-hero-image.webp"/>

      <div class="hero__content">
        <h1 class="hero__title">
          Mechanical<br>
          Repair Services
        </h1>
        <p class="hero__subtitle">Pretty Cars Break Down Too.</p>
        <p class="hero__description">
          Force Auto Styling knows your vehicle inside and out. Literally. The same team that protects your paint, wraps your ride, and details every inch also keeps it running the way it should.
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


