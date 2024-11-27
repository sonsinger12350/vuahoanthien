<?php
/**
 * Template Name: Welcome Page
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
//get_header();
?>

<style type="text/css">
    body {
        margin: 0;
    }
</style>
<picture>
    <source media="(min-width: 640px)" srcset="<?php site_the_assets();?>images/opening_poster_mb.png">
    <source media="(max-width: 575.98px)" srcset="<?php site_the_assets();?>images/opening_poster_mb.png">
    <img style="width: 100%;" src="<?php site_the_assets();?>images/opening-poster.jpg" />
</picture>


<?php

//get_footer();