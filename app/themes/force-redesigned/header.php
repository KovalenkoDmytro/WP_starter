<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package forcev2
 */

?>
  <!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://kit.fontawesome.com/56fd6d98ee.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
      href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@300;400;500;600;700;800&family=Oswald:wght@300;500&family=Open+Sans:wght@400;700&family=Roboto:wght@500;700&display=swap"
      rel="stylesheet">

    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Oswald:wght@300;500&family=Roboto+Condensed&family=Roboto:wght@500;700&display=swap"
      rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php
    echo get_template_directory_uri(); ?>/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.1/photoswipe.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.1/default-skin/default-skin.min.css">
    <link rel="stylesheet" href="<?php
    echo get_template_directory_uri(); ?>/assets/css/animate.css">


    <?php
    wp_head(); ?>
  </head>
<body <?php
body_class(); ?>>
  <header
    class="navbar navbar-top navbar-expand-xl navbar-dark navbar-fixed absolute-top px-xxl-5 px-xl-4 px-lg-4 px-md-2 main-menu">
    <div class="justify-content-between container-fluid">
      <?php
      $custom_logo_id = get_theme_mod('custom_logo');
      $logo = wp_get_attachment_image_src($custom_logo_id, 'full');

      if ( has_custom_logo() ) {
        // Display the custom logo
        echo '<a class="navbar-brand" href="' . esc_url(
            home_url('/')
          ) . '" rel="home"><img width="107" src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '"></a>';
      }
      else {
        // If no custom logo is set, display the site title as text
        echo '<h1><a href="' . esc_url(home_url('/')) . '">' . get_bloginfo('name') . '</a></h1>';
      }
      ?>

      <div class="navbar-container">
        <a href="tel:4032566501" class="btn --callUs">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="#fff"
                  d="m7.772 2.439l1.077-.344c1.008-.322 2.086.199 2.518 1.217l.86 2.028c.375.883.167 1.922-.514 2.568L9.82 9.706q.175 1.614 1.084 3.177a8.7 8.7 0 0 0 2.271 2.595l2.276-.76c.862-.287 1.801.044 2.33.821l1.232 1.81c.616.904.505 2.15-.258 2.916l-.818.821c-.814.817-1.976 1.114-3.052.778q-3.808-1.188-7.003-7.053q-3.199-5.875-2.258-9.968c.264-1.148 1.082-2.063 2.15-2.404"/>
          </svg>
        </a>

        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarsExample06" aria-controls="navbarsExample06" aria-expanded="false"
                aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>


      <div class="navbar-collapse collapse flex-grow-auto" id="navbarsExample06">

        <?php
        wp_nav_menu([
          'container' => 'false',
          'theme_location' => 'menu-1',
          'menu_class' => 'nav navbar-nav navigation',
          'menu_id' => 'navBar',
          'walker' => new Custom_Walker_Nav_Menu(),
        ]);
        ?>


        <div class="link-box d-none">
          <a href="<?php
          echo home_url('/'); ?>contact" class="theme-btn btn-style-two">
            <span class="txt">Get a Quote</span>
          </a>
        </div>


      </div>

    </div>
  </header>
<div class="page-wrapper">
<?php
$post_id = get_the_ID();
if ( $post_id == '16' || $post_id == '20' ) {
  ?>
  <section class="InnerBanner ceramicInnerBanner px-5">
  <?php
  // Replace 123 with your actual post ID

  $thumbnail_id = get_post_thumbnail_id($post_id);
  if ( $thumbnail_id ) {
    $image_src = wp_get_attachment_image_src($thumbnail_id, 'full');
    if ( $image_src ) {
      $backimage = $image_src[0]; // Output the URL of the featured image

    }
    else {
      $backimage = '';
    }
  }
  ?>

  <div class="InnerBanner-bg" style="background-image: url(<?php
  echo $backimage; ?>);">&nbsp;
  </div>
<?php
} ?>