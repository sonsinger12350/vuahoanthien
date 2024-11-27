<?php

global $sidebar_choose;

$list = site_wc_get_sorts();
$sort = site__get('sort', 'discount');

// var_dump($sidebar_choose);

?>
<div class="product-filter d-flex align-items-center justify-content-between mb-4">
  <span class="arrange-label">Sắp xếp theo:</span>
  <?php if( site_is_mobile()) :?>
      <div class="list-sort">
        <select class="form-control sort-select" onchange="location = this.value;">
          <?php foreach( $list as $key => $value ): ?>
            <option value="<?php echo add_query_arg( array('sort' => $key ) ); ?>"<?php echo $sort==$key?' selected':''; ?>><?php echo $value; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
  <?php else: ?>
      <div class="list-sort">
        <?php foreach( $list as $key => $value ):?>
        <a class="btn btn-outline-secondary rounded<?php echo $sort==$key?' active':'';?> sort-by-<?php echo $key; ?>" href="<?php echo add_query_arg( array('sort' => $key ) );?>"><?php echo $value;?></a>
        <?php endforeach;?>
      </div>
  <?php endif; ?>    
</div>

<?php if( isset($sidebar_choose) && count($sidebar_choose)>0 ):?>
<div class="product-filter list-item-filter d-flex align-items-center mb-4">
  <span>Đang chọn:</span>
  <?php foreach( $sidebar_choose as $value => $name ): ?>
  <a class="btn btn-primary rounded" href="<?php echo str_replace($value,'', urldecode($_SERVER['REQUEST_URI']) );?>">
    <?php echo $name;?>
    <i class="bi bi-x"></i>
  </a>
  <?php endforeach;?>
</div>
<?php endif;?>
