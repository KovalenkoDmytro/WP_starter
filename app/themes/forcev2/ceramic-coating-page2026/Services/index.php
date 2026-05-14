<?php
$base_url = esc_url(
    get_template_directory_uri() . str_replace(get_template_directory(), '', __DIR__)
);
?>

<section class="services-section">
  <div class="section-text-container">
    <h2 class="section-headline">What Your Paint Is Up Against</h2>
    <p class="section-text">
      Calgary's climate damages vehicle paint more than most owners realize. Intense summer UV rays gradually degrade the clear coat, while winter exposure to road salt, chemicals, and abrasive grit continuously wears down the surface during every drive.
    </p>
  </div>

  <div class="services-section__grid">

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/2.webp" alt="UV rays oxidizing and fading car clear coat in Calgary summer heat" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">UV exposure that fades and oxidizes the clear coat</h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/1.webp" alt="Road salt and winter chemicals etching into unprotected car paint" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">Road salt and chemicals that etch into unprotected paint</h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/3.webp" alt="Dirt, grime, and brake dust bonded to car paint surface" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">Dirt, grime, and brake dust that bonds to the surface</h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/4.webp" alt="Water spots and mineral deposits on car paint after washing" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">Water spots and mineral deposits from rain and washing</h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/5.webp" alt="Dull paint surface from general surface wear and gloss loss over time" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">General surface wear that dulls gloss over time</h3>
      </div>
    </div>

  </div>
</section>
