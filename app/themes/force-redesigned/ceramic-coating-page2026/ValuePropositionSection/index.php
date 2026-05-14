<?php
$base_url = esc_url(
        get_template_directory_uri() . str_replace(get_template_directory(), '', __DIR__)
);
?>

<link rel="stylesheet" href="<?php echo $base_url; ?>/style.css?v=1.11" type="text/css" media="all" />



<section class="value-proposition-section">
    <div class="value-proposition-section-inner">
        <!-- Header Content -->
        <div class="section-text-container">
            <h2 class="section-headline">
                Prep Done Right for Results That Last.
            </h2>
            <p class="section-text">
                Optimal ceramic coating results depend on careful preparation. Our team decontaminates, inspects, and corrects every surface before application to avoid locking in defects. This professional prep ensures years of high performance rather than a quick temporary finish.
            </p>
        </div>


        <div class="value-proposition-content">

            <img src="<?php echo $base_url; ?>/other_ways.webp"  alt="mechanical-services">


            <div class="benefits-list">
                <div class="benefit-card">
                    <ul>
                        <li>Decontamination and correction completed before any coating is applied</li>
                    </ul>
                </div>

                <div class="benefit-card">
                    <ul>
                        <li>Gyeon certified applicator with in-house application on every job</li>
                    </ul>
                </div>

                <div class="benefit-card">
                    <ul>
                        <li>Transparent quoting with no hidden fees or surprises at pickup</li>
                    </ul>
                </div>

                <div class="benefit-card benefit-card-large">
                    <ul>
                        <li>One facility for everything: ceramic, PPF, tint, detailing, and mechanical work</li>
                    </ul>
                </div>

                <div class="benefit-card benefit-card-large">
                    <ul>
                        <li>Concierge services available: detailing, towing, body repair coordination, and rental assistance</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>