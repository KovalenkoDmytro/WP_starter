<?php

/**
 * Template Name: SEO Calgary Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header();
?>


<?php if ( have_rows( 'general_components' ) ): ?>
	<?php while ( have_rows( 'general_components' ) ): the_row(); ?>

		<?php if ( get_row_layout() == 'main_hero_block' ): ?>
            <div class="p-5 main_hero_block"
                 style="background-image: url('<?= get_sub_field( 'background_image' )['sizes']['medium'] ?>'); background-size: cover">
                <div class="container d-flex gap-5 align-items-center justify-content-between">
                    <div class="col-7 " style="max-width: 650px">
                        <h1 class="main-title fw-bold "><?= get_sub_field( 'main_title_' ) ?></h1>
                        <div class="main-hero-block-text">
							<?= get_sub_field( 'text' ) ?>
                        </div>
                    </div>
                    <div class="col-5 d-flex flex-column align-items-center">
                        <p class="h5 fw-medium fs-3"><?= get_sub_field( 'subtitle' ) ?></p>
                        <img class="img-fluid mt-4 mb-4" style="width: 100%"
                             src="<?= get_sub_field( 'image' )['sizes']['large'] ?>">
                        <p class="fw-medium fs-4"><?= get_sub_field( 'subtitle2' ) ?></p>
                        <a class="btn btn-danger rounded-5 fw-bold fs-4"
                           href="<?= get_sub_field( 'link' )['url'] ?>"><?= get_sub_field( 'link' )['title'] ?></a>
                    </div>
                </div>

            </div>
		<?php endif ?>

		<?php if ( get_row_layout() == 'links_list' ): ?>
            <div class="links_list" style="background-color: <?= get_sub_field( 'background_color' ) ?>;color: #fff">
                <div class="container d-flex align-items-center align-self-center justify-content-center mb-5 p-4 gap-5"
                     style="color: #fff">
					<?php foreach ( get_sub_field( 'links' ) as $link ) : ?>

						<?php if ( $link['is_link'] ) : ?>
                            <a style="width: initial; color: #fff" href="<?= $link['link']['url'] ?>">
								<?= $link['link']['title'] ?>
                            </a>
						<?php else: ?>
                            <p style="width: initial; margin: 0"><?= $link['text'] ?></p>
						<?php endif ?>

					<?php endforeach ?>
                </div>
            </div>
		<?php endif ?>

		<?php if ( get_row_layout() == 'slider' ): ?>

            <div class="mb-5">
                <div class="slickSlider">
					<?php foreach ( get_sub_field( 'slides' ) as $key => $slides ) : ?>
                        <div class="slide">

                            <img class="image"
                                 src="<?= get_sub_field( 'slides' )[ $key ]['slide']['image']['sizes']['medium'] ?>"
                            />

                        </div>

					<?php endforeach; ?>
                </div>
            </div>
		<?php endif ?>

		<?php if ( get_row_layout() == 'title_headline' ): ?>
            <p class="h3 text-center mb-3" style="color: red"><?= get_sub_field( 'title' ) ?></p>
		<?php endif ?>

		<?php if ( get_row_layout() == 'text_with_google_reviews' ): ?>
            <div class="container d-flex mb-5 gap-5 ">
                <div class="col">
                    <p class="h2 fw-bold mb-4"><?= get_sub_field( 'left_container' )['title'] ?></p>
                    <div class="wrapper_text "
                         style="color: #6A7283"><?= get_sub_field( 'left_container' )['text'] ?></div>
                    <a class="btn btn-danger rounded-5 fw-medium fs-4 mt-5"
                       href="<?= get_sub_field( 'link' )['url'] ?>"><?= get_sub_field( 'link' )['title'] ?></a>
                </div>

                <div class="col d-flex flex-column align-items-center pt-5">
                    <img class="img-fluid rounded-5" style="width: 100%"
                         src="<?= get_sub_field( 'right_container' )['image']['sizes']['large'] ?>"/>
                </div>
            </div>
		<?php endif ?>

		<?php if ( get_row_layout() == 'benefits_section' ): ?>
            <div class="container d-flex flex-column align-items-center mt-5  mb-5 benefits_section">
                <p class="h2 fw-bold text-center mb-5"><?= get_sub_field( 'title' ) ?></p>
                <div class="row mb-5">
					<?php foreach ( get_sub_field( 'benefits' ) as $key => $benefit ): ?>
                        <div class="col d-flex flex-column align-items-center">

                            <img class="img-fluid"
                                 src="<?= $benefit['benefit']['image']['sizes']['thumbnail'] ?>"/>
                            <p class="h5 text-center"><?= $benefit['benefit']['title'] ?></p>
                            <div class="text-center fw-light"><?= $benefit['benefit']['short_description'] ?></div>
                        </div>
					<?php endforeach ?>

                </div>

            </div>

		<?php endif ?>

		<?php if ( get_row_layout() == 'case_study' ):

			?>
            <div class="container d-flex mb-5 grid gap-4 cases">
				<?php foreach ( get_sub_field( 'cases' ) as $key => $case ) : ?>
                    <div class="case case_<?= $key ?> col mb-5 rounded-5 "
                         style="background-image: url('<?= $case['case']['background_image']['sizes']['medium'] ?>'); background-size: cover">
                        <div class="case-content d-flex  d-flex flex-column align-items-center">
                            <div class="case-title d-flex align-items-center justify-content-center">
                                <img class="img-fluid" src="<?= $case['case']['logo']['sizes']['medium'] ?>"/>
                            </div>

                            <div class="text mt-4  d-flex flex-column align-items-center">
                                <p class="h3 fw-bold"><?= $case['case']['text_section1']['title'] ?></p>
                                <div class="mb-3 text-center"><?= $case['case']['text_section1']['subtitle'] ?></div>
                                <hr/>

                                <p class="h3 fw-bold"><?= $case['case']['text_section2']['title'] ?></p>
                                <div class="mb-3 text-center"><?= $case['case']['text_section2']['subtitle'] ?></div>
                                <hr/>

                                <p class="h3 fw-bold"><?= $case['case']['text_section3']['title'] ?></p>
                                <div class="mb-3 text-center"><?= $case['case']['text_section3']['subtitle'] ?></div>

                            </div>

                        </div>
                    </div>

				<?php endforeach ?>

            </div>

		<?php endif ?>

	<?php endwhile ?>
<?php endif ?>


<?php if ( have_rows( 'seo_page_components' ) ): ?>
	<?php while ( have_rows( 'seo_page_components' ) ): the_row(); ?>
		<?php if ( get_row_layout() == 'text_with_contact_form' ): ?>
            <div class="text_with_contact_form p-5" style="background-color: #E9F2FA">
                <div class="container d-flex">
                    <div class="row grid gap-4 mb-5">
                        <div class="col">
                            <p class="h2 fw-bold mb-4">
								<?= get_sub_field( 'first_block' )['title'] ?>
                            </p>
                            <div style="color: #6A7283">
								<?= get_sub_field( 'first_block' )['text'] ?>
                            </div>
                        </div>
                        <div class="col p-5 rounded-5" style="background-color: #FFF">
                            <div><?= get_sub_field( 'first_block' )['form_content']['short_text'] ?></p>
								<?= get_sub_field( 'first_block' )['form_content']['shortcode'] ?>
                            </div>
                        </div>

                        <div class="row d-flex align-items-center justify-content-center gap-4 mt-5">
                            <div class="col iframe rounded-5">
								<?= get_sub_field( 'second_block' )['video_code'] ?>
                            </div>
                            <div class="col">
                                <p class="h2 fw-bold mb-4"><?= get_sub_field( 'second_block' )['title'] ?></p>
                                <div style="color: #6A7283"><?= get_sub_field( 'second_block' )['text'] ?></div>
                            </div>
                        </div>
                        <div class="row mb-5 gap-4 mt-5">
							<?php foreach ( get_sub_field( 'advantages' ) as $key => $advantage ): ?>
                                <div class="col d-flex flex-column align-items-center rounded-5 p-4"
                                     style="background-color: #fff">
                                    <p class=" text-center fw-bold fs-1 mb-0"><?= $advantage['advantage']['title'] ?></p>
                                    <div class="text-center fs-3 lh-1"
                                         style="color: #CE241E"><?= $advantage['advantage']['short_description'] ?></div>
                                </div>
							<?php endforeach ?>

                        </div>
                    </div>
                </div>
            </div>
            </div>

		<?php endif ?>

		<?php if ( get_row_layout() == 'text_with_images_and_logos' ): ?>
            <div class="text_with_images_and_logos p-5" style="background-color: #E9F2FA">
                <div class="container ">
                    <div class="row grid gap-4 mb-5">
                        <div class="col">
                            <p class="h2 fw-bold mb-4 " style="max-width: 500px;">
								<?= get_sub_field( 'first_block' )['title'] ?>
                            </p>
                            <div style="color: #6A7283">
								<?= get_sub_field( 'first_block' )['text'] ?>
                            </div>
                            <a class="btn btn-danger rounded-5 fw-medium fs-4 mt-3"
                               style="color: #fff"
                               href="<?= get_sub_field( 'first_block' )['link']['url'] ?>"><?= get_sub_field( 'first_block' )['link']['title'] ?></a>

                        </div>
                        <div class="col ">
                            <img class="img-fluid rounded-5"
                                 style="width: 100%; max-height: 530px; object-fit: cover;"
                                 src="<?= get_sub_field( 'first_block' )['image']['sizes']['medium'] ?>"/>

                        </div>
                    </div>

                    <div class="row grid gap-4 mb-5 mt-4">

                        <div class="col ">
                            <img class="img-fluid rounded-5"
                                 style="width: 100%"
                                 src="<?= get_sub_field( 'second_block' )['image']['sizes']['medium'] ?>"/>

                        </div>

                        <div class="col">
                            <p class="h2 fw-bold mb-4">
								<?= get_sub_field( 'second_block' )['title'] ?>
                            </p>
                            <div style="color: #6A7283">
								<?= get_sub_field( 'second_block' )['text'] ?>
                            </div>

                        </div>
                    </div>

                    <div class="d-flex flex-column align-items-center row mb-5">
                        <p class=" fw-medium text-center mb-2 fs-4"
                           style="color: #D80303"><?= get_sub_field( 'seo_tools_section' )['title'] ?></p>

                        <div class="row mb-2 " style="max-width: 1000px;">
							<?php foreach ( get_sub_field( 'seo_tools_section' )['logos'] as $key => $logo ): ?>
                                <div class="col gap-3 d-flex justify-content-center align-items-center align-self-center">

                                    <img class="img-fluid"
                                         src="<?= $logo['logo']['sizes']['medium'] ?>"/>

                                </div>
							<?php endforeach ?>

                        </div>

                    </div>
                </div>

            </div>
		<?php endif ?>

		<?php if ( get_row_layout() == 'faq' ): ?>
            <div class="container">
                <div class="row mt-5 d-flex flex-column justify-content-center align-items-center align-self-center">
                    <p class="h2 fw-bold mb-4 text-center" style="width: 605px">
						<?= get_sub_field( 'title' ) ?>
                    </p>

                    <div class="accordion mb-5 mt-4" style="max-width: 800px;" id="accordionExample">
						<?php foreach ( get_sub_field( 'questions_and_answers' ) as $key => $question ): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse_<?= $key ?>" aria-expanded="false"
                                            aria-controls="collapse_<?= $key ?>">
										<?= $question['question'] ?>
                                    </button>
                                </h2>
                                <div id="collapse_<?= $key ?>" class="accordion-collapse collapse"
                                     data-bs-parent="#accordion_<?= $key ?>">
                                    <div class="accordion-body">
										<?= $question['answer'] ?>
                                    </div>
                                </div>
                            </div>
						<?php endforeach ?>


                    </div>

                </div>
            </div>
		<?php endif ?>

		<?php if ( get_row_layout() == 'text_with_tabs_and_slider' ): ?>
            <div class="container">
                <div class="row mt-5 p-5 d-flex rounded-5 flex-column justify-content-center align-items-center align-self-center"
                     style="background-color: #f4f4f4">
                    <p class="h2 fw-bold mb-4 text-center" style="max-width: 500px">
						<?= get_sub_field( 'title' ) ?>
                    </p>

                    <div class="text-center" style="color: #6A7283; max-width: 900px">
						<?= get_sub_field( 'short_description' ) ?>
                    </div>

                    <div class="tabs-container">
                        <div class="row">
                            <div class="d-flex gap-3 mb-5 mt-5 justify-content-center" id="myTab" role="tablist">
								<?php foreach ( get_sub_field( 'tabs' ) as $key => $tab ): ?>
                                    <div class="nav-item" role="presentation">
                                        <button class="btn btn-secondary rounded-5 fw-bold <?= $key == 0 ? 'btn-danger ' : '' ?>"
                                                id="tab_<?= $key ?>"
                                                data-bs-toggle="tab"
                                                data-bs-target="#tab-pane_<?= $key ?>"
                                                type="button"
                                                role="tab"
                                                aria-controls="tab-pane_<?= $key ?>"
                                                aria-selected="<?= $key == 0 ? 'true' : 'false' ?>">
											<?= htmlspecialchars( $tab['tab']['title'], ENT_QUOTES, 'UTF-8' ) ?>
                                        </button>
                                    </div>
								<?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="tab-content" id="myTabContent">
								<?php foreach ( get_sub_field( 'tabs' ) as $key => $tab ): ?>
                                    <div class="tab-pane fade <?= $key == 0 ? 'show active' : '' ?> row "
                                         id="tab-pane_<?= $key ?>"
                                         role="tabpanel"
                                         aria-labelledby="tab_<?= $key ?>"
                                         tabindex="0">
                                        <div class="d-flex gap-5 justify-content-center align-items-center align-self-center">
                                            <div class="col-3">
                                                <p class="h2 fw-bold">
													<?= htmlspecialchars( $tab['tab']['title'], ENT_QUOTES, 'UTF-8' ) ?>
                                                </p>
                                                <div  style="color: #6A7283;">
													<?= $tab['tab']['short_description'] ?>
                                                </div>
                                            </div>
                                            <div class="col-9 slider-container">
                                                <?php if($tab['tab']['slider']) :?>

                                                    <div class="slider_items p-3">
                                                        <?php foreach ( $tab['tab']['slider'] as $key => $slide ): ?>
                                                            <div class="slider_item p-4 rounded-5 shadow-sm" style="background-color: #fff;">
                                                                <p class="fs-5 fw-bold fw-media mb-4 " style="color: #D80303"><?= $slide['title'] ?></p>
                                                                <div><?= $slide['short_description'] ?></div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="arrays p-3">
                                                        <button style="color: #fff; background-color: #000; border-radius: 50%; border: none" class="  custom-arrow custom-prev">&lt;</button>
                                                        <button style="color: #fff; background-color: #000; border-radius: 50%; border: none" class="custom-arrow custom-next">&gt;</button>
                                                    </div>
					                            <?php endif; ?>


                                            </div>
                                        </div>
                                    </div>
								<?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="banner mb-5"
                 style="background: url('<?= get_sub_field( 'background' )['sizes']['large'] ?>')">

                <div class="container banner-wrapper p-5 d-flex flex-column align-items-center rounded-5"
                     style="background: url('<?= get_sub_field( 'banner' )['background']['sizes']['large'] ?>');"
                >
                    <p class="fs-1 title fw-bold mb-4 text-center" style="max-width: 600px; color: #fff ">
						<?= get_sub_field( 'banner' )['title'] ?>
                    </p>
                    <a class="btn btn-danger rounded-5 fw-medium fs-4 mt-3"
                       href="<?= get_sub_field( 'banner' )['link']['url'] ?>"><?= get_sub_field( 'banner' )['link']['title'] ?></a>


                </div>

            </div>

		<?php endif ?>


	<?php endwhile ?>
<?php endif ?>


<?php

get_footer();