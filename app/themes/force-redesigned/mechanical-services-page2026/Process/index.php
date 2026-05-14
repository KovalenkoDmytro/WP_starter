<?php
$phone_number = get_option('company_phone');
?>


<section class="process process--home">
  
  <div class="process__header">
    <h2 class="process__title">Let Us Take it From Here</h2>
    <p class="process__subtitle">A step-by-step process followed to prevent car paint issues.</p>
  </div>

  <div class="process__steps">
    <div class="process__step">
      <div class="process__border"></div>
      <div class="process__number">1</div>
      <div class="process__content">
        <h3 class="process__step-title">Book your appointment</h3>
        <p class="process__description">
            online or call <?php if ($phone_number) : ?><a href="tel:<?=esc_attr($phone_number)?>"> <?=esc_html($phone_number)?></a><?php endif; ?>. Appointments are strongly recommended to ensure same-day service, but walk-ins are welcome based on availability.
        </p>
      </div>
    </div>

    <div class="process__step">
      <div class="process__border"></div>
      <div class="process__number">2</div>
      <div class="process__content">
        <h3 class="process__step-title">Drop off your vehicle</h3>
        <p class="process__description">at our Macleod Trail facility. We run diagnostics and give you a clear breakdown of what needs attention.</p>
      </div>
    </div>

    <div class="process__step">
      <div class="process__border"></div>
      <div class="process__number">3</div>
      <div class="process__content">
        <h3 class="process__step-title">Approve the work.</h3>
        <p class="process__description">We notify you and send a detailed quote for approval before any work begins. This ensures zero ambiguity about the scope of work and the cost.</p>
      </div>
    </div>

    <div class="process__step">
      <div class="process__border"></div>
      <div class="process__number">4</div>
      <div class="process__content">
        <h3 class="process__step-title">Pick up and go.</h3>
        <p class="process__description">Your vehicle is ready, the invoice matches the quote, and if you need anything else down the road, you know where to find us.</p>
      </div>
    </div>
  </div>
</section>