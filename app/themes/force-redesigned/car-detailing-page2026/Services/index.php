<?php
$base_url = esc_url(
    get_template_directory_uri() . str_replace(get_template_directory(), '', __DIR__)
);
?>

<section class="services-section">
  <div class="section-text-container">
    <h2 class="section-headline">The Elements vs. Your Vehicle</h2>
    <p class="section-text">
      Day-to-day driving does more to a vehicle's finish than most people give it credit for. Paint contamination, bonded grime, UV damage, and surface wear build up gradually, and by the time it's visible, it's already taken a toll on the finish and the resale value underneath it.
    </p>
  </div>

  <div class="services-section__grid">

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/1.webp" alt="Bonded tar and road fallout contamination on car paint surface" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">Bonded contamination from tar, fallout, and road grime that a standard wash won't remove</h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/2.webp" alt="UV damage dulling clear coat and fading paint on a vehicle" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">UV exposure that dulls the clear coat and fades the paint over time</h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/3.webp" alt="Swirl marks and fine scratches on car paint from improper washing" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">Swirl marks and light scratches from improper washing or everyday contact</h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/4.webp" alt="Car interior surface showing dirt staining and UV wear" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">Interior surfaces that absorb dirt, staining, and UV damage with regular use</h3>
      </div>
    </div>

    <div class="services-section__card">
      <img src="<?php echo $base_url; ?>/images/5.webp" alt="Paint surface wear and dull finish reducing vehicle resale value" class="services-section__image">
      <div class="services-section__overlay"></div>
      <div class="services-section__content">
        <h3 class="services-section__title">Surface wear that compounds quietly and shows up most at resale time</h3>
      </div>
    </div>

  </div>
</section>
