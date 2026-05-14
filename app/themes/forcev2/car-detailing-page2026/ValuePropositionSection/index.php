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
                The Foundation of Force Auto Protection
            </h2>
            <p class="section-text">
                Detailing at Force is how a lot of our protection work starts, and the standard we hold for surface prep carries through to every detail job we take on.
            </p>
        </div>


        <div class="value-proposition-content">

            <img src="<?php echo $base_url; ?>/other_ways.webp"  alt="mechanical-services">


            <div class="benefits-list">
                <div class="benefit-card">
                    <ul>
                        <li>Expert, in-house care from trained detailing technicians</li>
                    </ul>
                </div>

                <div class="benefit-card">
                    <ul>
                        <li>Transparent pricing, guaranteed, no hidden charges at pickup</li>
                    </ul>
                </div>

                <div class="benefit-card">
                    <ul>
                        <li>Honest recommendations based on your vehicle's actual condition, never upselling</li>
                    </ul>
                </div>

                <div class="benefit-card benefit-card-large">
                    <ul>
                        <li>Full concierge support, including coordination for body repair, towing, and rentals</li>
                    </ul>
                </div>

                <div class="benefit-card benefit-card-large">
                    <ul>
                        <li>A single facility for total vehicle care: detailing, PPF, ceramic, tint, and mechanical work.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>