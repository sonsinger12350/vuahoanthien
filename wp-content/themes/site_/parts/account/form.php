<?php

global $no_nav;

$no_nav = 1;

get_header();

// Start the Loop.
while ( have_posts() ) : the_post();

  the_content();

endwhile;

?>
<button class="btn btn-scroll-top"><i class="bi bi-arrow-up"></i></button>
<?php wp_footer(); ?>
</body>
</html>