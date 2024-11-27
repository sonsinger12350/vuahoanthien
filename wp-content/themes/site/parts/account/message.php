<?php

get_header();

?>
<div class="container">
  <div class="pt-3">
  <?php
    // Start the Loop.
    while ( have_posts() ) : the_post();
      
      the_content();

    endwhile;
  ?>
  </div>
</div>
<?php

get_footer();