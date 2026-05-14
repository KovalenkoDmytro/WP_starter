<?php
$base_url = esc_url(
        get_template_directory_uri() . str_replace(get_template_directory(), '', __DIR__)
);
$css_ver = filemtime(__DIR__ . '/style.css');
?>

<link rel="stylesheet" href="<?php echo $base_url; ?>/style.css?v=<?php echo $css_ver; ?>" type="text/css" media="all" />

<section class="packages-section">
	<div class="section-text-container">
		<h2 class="section-headline">
			Calgary's Most Complete Vehicle Paint Protection Packages
		</h2>
	</div>

	<div class="packages-section__grid">
		<div class="packages-section__item">
			<a href="/contact" class="package-card">
				<div class="package-card__header">
					<h3 class="package-card__title">BRONZE<br>PROTECTION</h3>
				</div>
				<div class="package-card__body">
					<span class="package-card__label">Most Vehicles</span>
					<p class="package-card__price"><span>$</span>599.<span>00</span></p>
					<p class="package-card__description">One coat of <strong>Gyeon</strong> covering all painted surfaces.</p>
					<p class="package-card__description">Glossy & hydrophobic protection for up to 2 years.</p>
				</div>
			</a>
		</div>
		<div class="packages-section__item">
			<a href="/contact" class="package-card">
				<div class="package-card__header">
					<h3 class="package-card__title">SILVER<br>PROTECTION</h3>
				</div>
				<div class="package-card__body">
					<span class="package-card__label">Most Vehicles</span>
					<p class="package-card__price"><span>$</span>1099.<span>00</span></p>
					<p class="package-card__description">Two coats of <strong>Gyeon</strong> covering all painted, plastic and glass surfaces.</p>
					<p class="package-card__description">Glossy & hydrophobic protection for up to 5 years.</p>
				</div>
			</a>
		</div>
		<div class="packages-section__item">
			<a href="/contact" class="package-card">
				<div class="package-card__header">
					<h3 class="package-card__title">GOLD<br>PROTECTION</h3>
				</div>
				<div class="package-card__body">
					<span class="package-card__label">Most Vehicles</span>
					<p class="package-card__price"><span>$</span>1799.<span>00</span></p>
					<p class="package-card__description">Four coats of <strong>Gyeon</strong> covering all painted, plastic and glass surfaces plus alloy wheels*.</p>
					<p class="package-card__description">Glossy & hydrophobic protection for up to 10 years.</p>
					<p class="package-card__note"><em>*Only wheel faces will be coated</em></p>
				</div>
			</a>
		</div>
		<div class="packages-section__item">
			<a href="/contact" class="package-card">
				<div class="package-card__header">
					<h3 class="package-card__title">ANNUAL REJUVENATION</h3>
				</div>
				<div class="package-card__body">
					<span class="package-card__label">Most Vehicles</span>
					<p class="package-card__price"><span>$</span>99.<span>00</span></p>
					<p class="package-card__description">As an added value to our ceramic customers, we offer an annual program to have your vehicle protection professionally rejuvenated.</p>
					<p class="package-card__description">Service includes a full exterior wash, re-application of Gyeon topcoat, then a surface wax to keep your vehicle shining.</p>
					<div class="package-card__addon">
						<span class="package-card__addon-label">Retail Value</span>
						<span class="package-card__addon-price">$299</span>
					</div>
				</div>
			</a>
		</div>
		<div class="packages-section__item">
			<a href="/contact" class="package-card">
				<div class="package-card__header">
					<h3 class="package-card__title">WHEEL COATING</h3>
				</div>
				<div class="package-card__body">
					<span class="package-card__label">Set of 4</span>
					<p class="package-card__price"><span>$</span>399.<span>00</span></p>
					<p class="package-card__description">The ultimate protection against the harsh elements, while providing your wheels with the same glossy and hydrophobic properties as your vehicle.</p>
					<ul class="package-card__list">
						<li class="package-card__list-item">Wheels are removed from the vehicle, cleaned and prepped.</li>
						<li class="package-card__list-item">4 coats of Gyeon are applied to the faces, spokes and barrels.</li>
						<li class="package-card__list-item">Wheels will then be re-installed and re-torqued to specification.</li>
					</ul>
				</div>
			</a>
		</div>
		<div class="packages-section__item">
			<a href="/contact" class="package-card">
				<div class="package-card__header">
					<h3 class="package-card__title">INTERIOR COATING</h3>
				</div>
				<div class="package-card__body">
					<span class="package-card__label">Set of 4</span>
					<p class="package-card__price"><span>$</span>399.<span>00</span></p>
					<ul class="package-card__list">
						<li class="package-card__list-item">2 coats of Gyeon are applied to all interior plastic, leather, vinyl and glass surfaces to protect from fading & discoloration.</li>
						<li class="package-card__list-item">Stain protection is sprayed on all carpet and cloth upholstery.</li>
						<li class="package-card__list-item">Cleaning will be a breeze after we protect your interior surfaces.</li>
					</ul>
				</div>
			</a>
		</div>
	</div>
</section>
