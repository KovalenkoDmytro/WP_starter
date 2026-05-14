<?php
$phone_number = get_option('company_phone');
?>

<section id="contact" class="contact-section contact-section--home">
  
  <div class="contact-section__container">
    
    <div class="contact-section__media">
      <img class="contact-section__image" src="<?php echo get_template_directory_uri(); ?>/spray-in-box-liner-page2026/ContactSection/Image.webp" alt="Calgary PPF Shop Background">
    </div>

    <div class="contact-section__content">
      <h2 class="contact-section__title">
        Your Truck Works Hard. Its Box Should Hold Up.
      </h2>
      <p class="contact-section__description">
        Whether you are hauling tools, equipment, bikes, or building materials, a spray-in boxliner from Force keeps your truck box protected season after season.
      </p>
      
      <div class="contact-section__actions">
        <a href="/contact" class="btn btn--primary contact-section__btn">Book Now</a>
        <?php if ($phone_number) : ?>
          <a href="tel:<?=esc_attr($phone_number)?>" class="btn btn--outline hero__cta">Call <?=esc_html($phone_number)?></a>
        <?php endif; ?>
    </div>

  </div>
</section>