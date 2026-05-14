<?php
$phone_number   = get_option('company_phone');
$hero_image_url = esc_url(
    get_template_directory_uri() . str_replace(get_template_directory(), '', __DIR__) . '/bg-hero-image.webp'
);
?>

<section id="home-hero-slider" class="hero-section --professional-detailing">
  <div class="hero__slide">

      <div class="hero__overlay"></div>

      <img class="hero__image" src="<?php echo $hero_image_url; ?>" alt=""/>

      <div class="hero__content">
        <h1 class="hero__title">
            CALGARY'S <br>
            PROFESSIONAL CAR <br>
            DETAILING
        </h1>
        <p class="hero__subtitle">Good looks are just the beginning.</p>
        <p class="hero__description">
            Protect your paint with PPF. Force Auto uses Suntek Ultra, a self-healing film with a 10-year warranty that guards against chips and road damage. Get durable PPF installed by our Calgary professionals today.
        </p>

        <div class="hero__social">
          <a href="/contact" class="btn btn--primary hero__cta">Get a PPF Quote</a>
          <?php if ($phone_number) : ?>
          <a href="tel:<?=esc_attr($phone_number)?>" class="btn btn--outline hero__cta">Call <?=esc_html($phone_number)?></a>
          <?php endif; ?>
        </div>

      </div>

    </div>
  <div class="hero__scroll">Scroll Down ↓</div>
</section>


