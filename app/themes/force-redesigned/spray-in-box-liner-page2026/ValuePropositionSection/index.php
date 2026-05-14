<?php
$base_url = esc_url(
        get_template_directory_uri() . str_replace(get_template_directory(), '', __DIR__)
);
?>

<section class="value-proposition-section">
    <div class="value-proposition-section-inner">

        <div class="section-text-container">

            <h1 class="section-headline">
                Vehicles We Coat
            </h1>
            <p class="section-text">
                Your truck box is the obvious one, but a spray-in liner works well beyond that. If something on your
                vehicle takes a beating from regular use, chances are we can coat it.
            </p>
        </div>


        <div class="value-proposition-content">

            <img src="<?php echo $base_url; ?>/other_ways.webp"
                 alt="mechanical-services">


            <div class="benefits-list">
                <div class="benefit-card">
                    <ul>
                        <li>Full truck box (floor, walls, tailgate, and wheel wells)</li>
                    </ul>
                </div>

                <div class="benefit-card">
                    <ul>
                        <li>Tailgate-only applications</li>
                    </ul>
                </div>

                <div class="benefit-card">
                    <ul>
                        <li>Rocker panels and fender flares</li>
                    </ul>
                </div>

                <div class="benefit-card benefit-card-large">
                    <ul>
                        <li>Bumpers and running boards</li>
                    </ul>
                </div>

                <div class="benefit-card benefit-card-large">
                    <ul>
                        <li>Off-road and utility equipment</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</section>