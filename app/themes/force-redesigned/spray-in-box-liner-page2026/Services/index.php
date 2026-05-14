<?php
$base_url = esc_url(
    get_template_directory_uri() . str_replace(get_template_directory(), '', __DIR__)
);
?>

<section class="services-section">
  <div class="section-text-container">
    <h2 class="section-headline">What Your Truck Bed Is Up Against</h2>
    <p class="section-text">
        Calgary is hard on truck beds in ways that are not always obvious until the damage is already done. Between hauling in cold weather, salt and chemical exposure from winter roads, and the general wear of loading and unloading tools, equipment, and materials, the bare metal underneath is constantly at risk.
    </p>
  </div>

  <div class="services-section__grid">

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/1.webp" alt="Scratches and gouges on truck bed from tools and cargo shifting during transit" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
          <h3 class="services-section__title">
              Scratches and gouges from tools, equipment, and cargo shifting during transit
          </h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/2.webp" alt="Dents and impact marks on truck bed from heavy loads dropped during loading" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
          <h3 class="services-section__title">
              Dents and impact marks from heavy loads dropped during loading
          </h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/3.webp" alt="Rust and corrosion on truck bed from trapped moisture, road salt, and Calgary winter chemicals" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
          <h3 class="services-section__title">
              Rust and corrosion from trapped moisture, salt, and road chemicals
          </h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/4.webp" alt="UV damage dulling and weakening unprotected truck bed surface over time" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
          <h3 class="services-section__title">
              UV damage that dulls and weakens the box surface over time
          </h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/5.webp" alt="Cargo sliding on unprotected truck bed surface damaging both the box and equipment" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
          <h3 class="services-section__title">
              Cargo sliding on an unprotected surface, which damages both the box and your gear
          </h3>
      </div>
    </div>

  </div>
</section>
