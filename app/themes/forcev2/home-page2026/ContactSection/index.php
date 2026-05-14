<?php
$phone_number = get_option( 'company_phone' );
$base_url     = esc_url( get_template_directory_uri() . str_replace( get_template_directory(), '', __DIR__ ) );

?>


<section id="contact" class="contact-section contact-section--home">

    <div class="contact-section__container">

        <div class="contact-section__media">
            <img class="contact-section__image" src="<?php echo $base_url; ?>/Image.webp"
                 alt="Calgary PPF Shop Background">
        </div>

        <div class="contact-section__content">
            <h2 class="contact-section__title">
                Have a question?<br>
                Get a PPF quote in Calgary.
            </h2>
            <p class="contact-section__description">
                Tell us about your vehicle, how you drive, and what you're looking to protect. We'll walk you through
                paint protection film options and recommend coverage that makes sense for your use.
            </p>

            <div class="contact-section__actions">
                <a href="/contact" class="btn btn--primary contact-section__btn">Request a PPF Quote</a>
                <?php if ( $phone_number ) : ?>
                    <a href="tel:+14032566501" class="btn btn--outline hero__cta">Call 403-<span>256<span>-6501</a>
                <?php endif; ?>
            </div>

            <a class="direction-link" href="https://maps.app.goo.gl/9e9R8UpYFhrZAu2p9">
                5004 Macleod Trail SW, Calgary
            </a>
        </div>

    </div>
</section>