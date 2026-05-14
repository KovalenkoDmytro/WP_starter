<?php
$phone_number   = get_option('company_phone');
$hero_image_url = esc_url(
    get_template_directory_uri() . str_replace(get_template_directory(), '', __DIR__) . '/bg-hero-image.webp'
);
?>

<section id="home-hero-slider" class="hero-section --ceramic-coating">
  <div class="hero__slide">

      <div class="hero__overlay"></div>

      <img class="hero__image" src="<?php echo $hero_image_url; ?>" alt=""/>

      <div class="hero__content">
          <p class="hero__subtitle">Calgary Paint Wears Faster Than Most Drivers Expect.</p>
        <h1 class="hero__title">
            GYEON CERAMIC<br>
            COATING CALGARY
        </h1>
          <p class="hero__subtitle">Ceramic coating with Gyeon is how you stay ahead of it.</p>
        <p class="hero__description">
            Nano coatings are permanent particles bonding to paint, glass, and wraps. This hard, hydrophobic surface resists dirt, UV damage, and chemicals, providing professional protection Calgary drivers can depend on.
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


