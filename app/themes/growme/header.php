<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package GrowME
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="facebook-domain-verification" content="j903h3lcbpq2kx01jlyttkcbv792qn" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-917151438"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-917151438');
</script>

	<?php wp_head(); ?>
	<link href="https://fonts.googleapis.com/css?family=Zilla+Slab:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">



	<div id="content" class="site-content">
<?php echo do_shortcode( '[elementor-template id="57"]' ); ?>
