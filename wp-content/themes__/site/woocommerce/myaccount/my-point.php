<?php

$get_points = (int) get_user_meta( get_current_user_ID(), 'wps_wpr_points', true );

?>
<h2 class="mb-4 section-header border-0 mg-top-0"><span>Điểm tích lũy</span></h2>
<div class="my-points">
    Điểm: <?php echo $get_points;?>
</div>