<?php
$phone_number = get_option( 'company_phone' );
$base_url     = esc_url( get_template_directory_uri() . str_replace( get_template_directory(), '', __DIR__ ) );
?>

<section id="contact" class="contact-section contact-section--home">
  
  <div class="contact-section__container">
    
    <div class="contact-section__media">
        <img class="contact-section__image" src="<?php echo $base_url; ?>/Image.webp" alt="Calgary PPF Shop Background">
    </div>

    <div class="contact-section__content">
      <h2 class="contact-section__title">
        The Most Expensive Repair Is the One You Ignore.
      </h2>
      <p class="contact-section__description">
          Book your repair today and see why Calgary drivers have trusted us since 2017.
      </p>
      
      <div class="contact-section__actions">
        <a href="/contact" class="btn btn--primary contact-section__btn">Book Now</a>
        <?php if ($phone_number) : ?>
          <a href="tel:<?=esc_attr($phone_number)?>" class="btn btn--outline hero__cta">Call <?=esc_html($phone_number)?></a>
        <?php endif; ?>
    </div>

        <?php force_direction_link(); ?>

  </div>
</section>