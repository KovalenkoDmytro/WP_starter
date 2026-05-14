<?php
$base_url = esc_url(
        get_template_directory_uri() . str_replace(get_template_directory(), '', __DIR__)
);
?>
<section class="other-services other-services--home">
  <div class="other-services__container">

    <div class="other-services__content">
      <div class="other-services__header">
        <h2 class="other-services__title">Fast Lane Looks. Long Haul Guts.</h2>
      </div>

      <div class="other-services__text">
        <p>
            Mechanical work should not be rushed or done in a makeshift setup. Our facility is fully outfitted for diagnostics, repair, and service, with in-house equipment and trained professionals on staff. We know car trouble is stressful. Our streamlined process ensures less complexity, better communication, and dependable results.
        </p>
      </div>
    </div>

    <div class="other-services__gallery">
      <img class="other-services__image" src="<?php echo $base_url; ?>/standarts.webp" alt="Other automotive services">
    </div>
  </div>
</section>