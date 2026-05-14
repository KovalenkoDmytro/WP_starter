<?php
$base_url = esc_url(
        get_template_directory_uri() . str_replace(get_template_directory(), '', __DIR__)
);
?>


<section class="value-proposition-section">
    <div class="value-proposition-section-inner">
        <!-- Header Content -->
        <div class="section-text-container">
            <p class="section-subtitle">Beyond the Finish</p>
            <h2 class="section-headline">
                Our Full-Service Advantage
            </h2>
            <p class="section-text">
                We are known for wraps, PPF, and tint, but our team has decades of experience in every aspect of auto work from body repair to full mechanical service. This means convenience for you as your vehicle stays in one trusted facility instead of bouncing between specialty garages. We look at the whole picture, ensuring our broad knowledge catches things a single-service shop might miss.
            </p>
        </div>


        <div class="value-proposition-content">
            <img src="<?php echo $base_url; ?>/other_ways.webp"  alt="mechanical-services">

            <div class="benefits-list">
                <div class="benefit-card">
                    <ul>
                        <li>Transparent quoting with no hidden fees or surprise line items at pickup</li>
                    </ul>
                </div>

                <div class="benefit-card">
                    <ul>
                        <li>Parts sourced to your preference — OEM or aftermarket, we'll give you both options</li>
                    </ul>
                </div>

                <div class="benefit-card">
                    <ul>
                        <li>Step-by-step walkthroughs so you understand every repair before you approve it</li>
                    </ul>
                </div>

                <div class="benefit-card benefit-card-large">
                    <ul>
                        <li> Concierge services available: body repair coordination, towing, detailing, and rental assistance while your vehicle is in the shop</li>
                    </ul>
                </div>

                <div class="benefit-card benefit-card-large">
                    <ul>
                        <li>One facility for everything: styling, protection, and mechanical work</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>