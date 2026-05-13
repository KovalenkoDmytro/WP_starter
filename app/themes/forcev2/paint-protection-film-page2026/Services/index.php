<?php
$base_url = esc_url(
    get_template_directory_uri() . str_replace(get_template_directory(), '', __DIR__)
);
?>

<section class="services-section">
  <div class="section-text-container">
    <h2 class="section-headline">What Your Paint Is Up Against</h2>
    <p class="section-text">
      Paint protection film (PPF) is an invisible, impact-resistant shield that absorbs rock chips and road debris, protection ceramic coatings can't provide. As a physical barrier, it takes the impact to preserve your factory finish, while SunTek Ultra's self-healing technology uses heat to erase minor marks. Since Calgary roads are exceptionally harsh, PPF prevents the accumulated damage that often surfaces only at resale.
    </p>
  </div>

  <div class="services-section__grid">

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/1.webp" alt="Car hood with rock chip damage from winter gravel and road debris" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">Winter gravel and road debris that chip hoods, bumpers, and mirrors</h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/2.webp" alt="Rock chip damage on car paint from high-speed highway driving in Calgary" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">Rock chips on high-speed highway stretches</h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/3.webp" alt="Road salt and abrasive chemicals etching into unprotected car paint surface" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">Abrasive road chemicals and salt that work into the paint surface</h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/4.webp" alt="Bug acid and road tar staining on car paint without PPF protection" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">Bug acids and road tar that stain if left untreated</h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/5.webp" alt="UV-faded clear coat on car hood — paint protection film blocks UV damage" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">UV exposure that fades the clear coat over time</h3>
      </div>
    </div>

  </div>
</section>
