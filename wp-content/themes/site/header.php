<?php

/**

 * The Header for our theme

 *

 * Displays all of the <head> section and everything up till <div id="main">

 *

 * @package WordPress

 * @subpackage Twenty_Fourteen

 * @since Twenty Fourteen 1.0

 */



?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" /> -->
	<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi, viewport-fit=cover" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if (is_singular() && pings_open(get_queried_object())) : ?>
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

	<!--[if lt IE 9]>

<script src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'></script>

<script src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'></script>

<![endif]-->

</head>

<div id="loader-wrapper">
	<div class="loaderbox s">
		<div id="loader"></div>
		<img src="https://vuahoanthien.com/assets/images/icons/icon-Logo.png" class="img-fluid w-80" title="Vua Hoàn Thiện">
	</div>
</div>

<body <?php site_body_class(); ?>>
	<?php

		global $no_nav, $no_coupon;
		global $sidebar_true;

		$sidebar_true = false;

		if (empty($no_nav)) {
			if (site_is_mobile()) {
				//get_template_part( 'parts/section/nav');
				get_template_part('parts/section/navMobile');
			} else {
				get_template_part('parts/section/nav');
			}
		}



		if (empty($no_coupon)) get_template_part('parts/section/coupons');
	?>

	<main>