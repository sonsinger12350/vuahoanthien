<?php
/**
 * Template Name: Compare
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

$ids = explode('-', sanitize_text_field( isset( $_GET['ids'] ) ? $_GET['ids'] : '' ) );
$error_compare_check = false;

// Check if all products have the same product type
if (count($ids) > 1) {
    $first_product = wc_get_product($ids[0]);
    $product_type = get_field('product_type', $first_product->get_id());
    switch ($product_type) {
        case "bon_cau":
          //$specs_sp = site_acf_get_fields( 'bon_cau_specs', $group = 1 );
          $specs_slug = "bon_cau_specs";
          $specs_s = get_field("bon_cau_specs", $first_product->get_id());
          break;
        case "bon_tam":   
          $specs_slug = "bon_tam_specs";
          $specs_s = get_field("bon_tam_specs", $first_product->get_id());
          break;
        case "bon_tieu":   
          $specs_slug = "bon_tieu_specs";  
          $specs_s = get_field("bon_tieu_specs", $first_product->get_id());
          break; 
        case "lavabo":   
          $specs_slug = "lavabo_specs";  
          $specs_s = get_field("lavabo_specs", $first_product->get_id());
          break; 
        case "nap_bon_cau":   
          $specs_slug = "nap_bon_cau_specs";  
          $specs_s = get_field("nap_bon_cau_specs", $first_product->get_id());
          break;   
        case "sen_bon_tam":   
          $specs_slug = "sen_bon_tam_specs";
          $specs_s = get_field("sen_bon_tam_specs", $first_product->get_id());
          break;    
        case "sen_tam":   
          $specs_slug = "sen_tam_specs";
          $specs_s = get_field("sen_tam_specs", $first_product->get_id());
          break;
        case "voi_lavabo":   
          $specs_slug = "voi_lavabo_specs";
          $specs_s = get_field("voi_lavabo_specs", $first_product->get_id());
          break; 
        case "bep":   
          $specs_slug = "bep_specs";
          $specs_s = get_field("bep_specs", $first_product->get_id());
          break; 
        case "lo":   
          $specs_slug = "lo_specs";
          $specs_s = get_field("lo_specs", $first_product->get_id());
          break;   
        case "cong_tac_o_cam":   
          $specs_slug = "cong_tac_o_cam_specs";
          $specs_s = get_field("cong_tac_o_cam_specs", $first_product->get_id());
          break; 
        case "thiet_bi_dong_cat":   
          $specs_slug = "thiet_bi_dong_cat_specs";
          $specs_s = get_field("thiet_bi_dong_cat_specs", $first_product->get_id());
          break;        
        default:
          $specs_s = "";  
      }
      // $specs_s = site_acf_get_fields( 'Bồn cầu Specs', $group = 1 );
      // var_dump($specs_s);


    foreach ($ids as $id) {
        $product = wc_get_product($id);
        $current_product_type = get_field('product_type', $product->get_id());

        //var_dump($current_product_type);

        if ($current_product_type !== $product_type) {
            // Products have different product types, show a warning message
            $error_compare_check = true;
            //echo '<div class="warning-message">' . esc_html($message) . '</div>';

            // Exit the loop
            break;
        }
    }
}


site_body_class_add( 'compare-products' );

get_header();

$products = wc_get_products(array(
  'limit' => 4,
  'include' => $ids,
));

$acf_fields = site_acf_get_fields( 'Product Fields', $group = 1 );

$n = count($products);

?>
<div class="bg-light">
  <div class="container">
    <div class="section-bg">
      <?php if($error_compare_check == true): ?>
            <div class="warning-message"><i class="bi bi-exclamation-triangle"></i> <?php echo esc_html('Những sản phẩm này không cùng loại. Xin vui lòng so sánh những sản phẩm cùng loại với nhau.');  ?></div>
      <?php endif; ?>
      <div class="row row-cols-md-5 mb-3 mt-3">
        <div class="col-12 col-md py-3">
          <h2 class="section-header m-0 mb-3 border-0">
            <span>So sánh sản phẩm</span>
          </h2>        
        </div>
        <?php foreach( $products as $product ): ?>
        <div class="col-12 col-md">
          <div class="card h-100 product-item">
            <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="text-decoration-none product-image mb-3">
              <img src="<?php echo wp_get_attachment_image_url( $product->get_image_id(), 'medium' );?>" class="card-img-top"
                alt="<?php echo $product->get_title(); ?>" />
            </a>
            <div class="card-body p-0">
              <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="text-decoration-none fw-bold">
                <p class="card-text text-dark text-limit-2"><?php echo $product->get_title(); ?></p>
              </a>
              <div class="product-rating">
                <div class="d-block fs-5 text starts" style="--rating: <?php site_wc_the_stars_percent( $product )?>%;"></div>
              </div>
              <div class="d-flex flex-column product-price">
                <div class="d-flex flex-row flex-wrap align-items-end justify-content-between">
                  <div class="product-price-left">
                    <p><small><del><?php echo site_wc_price($product->get_regular_price());?> <sup class="fs-12 text">đ</sup></del></small></p>
                    <p><b class="me-1 text-danger"><?php echo site_wc_price($product->get_sale_price());?><sup class="text-danger fs-12 text">đ</sup></b></p>
                  </div>
                  <div class="d-flex bg-danger align-items-center justify-content-center border rounded percent-save">
                    <span class="fs-11 fw-bold text-light">-<?php site_wc_the_discount_percent( $product )?>%</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer bg-transparent border-0 p-0 pt-2">
              <button class="btn btn-outline-danger w-100 fw-bold btn-remove-compare" value="<?php echo $product->get_id();?>"><i class="bi bi-trash"></i> Xóa</button>
            </div>
          </div>
        </div>
        <?php endforeach;?>
      </div>
      <?php if($error_compare_check == false): ?>
      <h2 class="section-header border-0">
        <span>Thông số kỹ thuật</span>
      </h2>
      <div class="d-none d-md-block">
        <div class="row row-cols-md-5 flex-nowrap">
          <div class="col py-2 border-top product-spec-title bg-grey-lightest">
            Sản phẩm
          </div>
          <?php foreach( $products as $product ): ?>
          <div class="col py-2 border-top product-spec-content">
            <?php echo $product->get_title(); ?>
          </div>
          <?php endforeach;?>
        </div>
        <div class="row row-cols-md-5 flex-nowrap">
          <div class="col py-2 border-top product-spec-title bg-grey-lightest">
            Thương hiệu
          </div>
          <?php foreach( $products as $product ): ?>
          <div class="col py-2 border-top product-spec-content">
            <?php site_the_product_brand( $product->get_id() ); ?>
          </div>
          <?php endforeach;?>
        </div>
        <?php foreach( $acf_fields as $key => $field ):
                 $fielditem = acf_get_field($key); 
                 //var_dump($fielditem);
                 //var_dump($field['name']);
                 //var_dump(get_field($field['name'] , $product->get_id())); 
                 if ($fielditem['type'] != 'select' && $fielditem['type'] != 'group'):
                  if (get_field($field['name'] , $product->get_id()) != null):
        ?>  
                    <div class="row row-cols-md-5 flex-nowrap">
                      <div class="col py-2 border-top product-spec-title bg-grey-lightest">
                        <?php echo $field['title'];?>
                      </div>
                      <?php foreach( $products as $product ): ?>
                      <div class="col py-2 border-top product-spec-content">
                        <?php the_field( $field['name'], $product->get_id() ); ?>
                      </div>
                      <?php endforeach;?>
                    </div>
        <?php 
                  endif;
                endif; 
                if($fielditem['type'] == 'group' && $fielditem['name'] == $specs_slug ):
                  //var_dump($fielditem[ID]);
                  $specs_sp = $fielditem['sub_fields'];
                  //var_dump($specs_sp);
                  foreach ($specs_sp as $specs_sp_item): 
                    //var_dump($specs_sp_item["parent"]);
                    //echo $specs_sp_item['key'];
                    //$fieldsubitem = acf_get_field($subkey);
                    // if ($fieldsubitem) {
                    //   $field_label = $fieldsubitem['label'];
                    // } 
                    $subkey = $specs_sp_item['key'];
                    //var_dump($subkey);
                    //$fielditem = acf_get_field($subkey);
                    //var_dump($fielditem);
                    //echo $specs_sp_item["parent"];
                    //echo $fielditem["ID"];
                   ?>
                     <div class="row row-cols-md-5 flex-nowrap">
                        <div class="col py-2 border-top product-spec-title bg-grey-lightest">
                          <?php echo $specs_sp_item['label']; ?>
                        </div>
                        <?php foreach( $products as $product ): 
                                //echo $product->get_id();
                                $loai_sp = get_field("product_type", $product->get_id());

                                //echo $loai_sp;
                                $specs_sps = "";

                                switch ($loai_sp) {
                                  case "bon_cau":
                                    //$specs_sp = site_acf_get_fields( 'bon_cau_specs', $group = 1 );
                                    $specs_sps = get_field("bon_cau_specs", $product->get_id());
                                    break;
                                  case "bon_tam":   
                                    $specs_sps = get_field("bon_tam_specs", $product->get_id());
                                    break;
                                  case "bon_tieu":   
                                    $specs_sps = get_field("bon_tieu_specs", $product->get_id());
                                    break; 
                                  case "lavabo":   
                                    $specs_sps = get_field("lavabo_specs", $product->get_id());
                                    break; 
                                  case "nap_bon_cau":   
                                    $specs_sps = get_field("nap_bon_cau_specs", $product->get_id());
                                    break;   
                                  case "sen_bon_tam":   
                                    $specs_sps = get_field("sen_bon_tam_specs", $product->get_id());
                                    break;    
                                  case "sen_tam":   
                                    $specs_sps = get_field("sen_tam_specs", $product->get_id());
                                    break;
                                  case "voi_lavabo":   
                                    $specs_sps = get_field("voi_lavabo_specs", $product->get_id());
                                    break; 
                                  case "bep":   
                                    $specs_sps = get_field("bep_specs", $product->get_id());
                                    break; 
                                  case "lo":   
                                    $specs_sps = get_field("lo_specs", $product->get_id());
                                    break;   
                                  case "cong_tac_o_cam":   
                                    $specs_sps = get_field("cong_tac_o_cam_specs", $product->get_id());
                                    break; 
                                  case "thiet_bi_dong_cat":   
                                    $specs_sps = get_field("thiet_bi_dong_cat_specs", $product->get_id());
                                    break;        
                                  default:
                                    $specs_sps = "";  
                                }
                                //var_dump($specs_sps);

                                if ($specs_sps):
                                  foreach ($specs_sps as $keys => $specs_sp_items): 
                                      $fielditems = acf_get_field($keys);
                                      //var_dump($fielditems);
                                      if ($fielditems) {
                                        $field_labels = $fielditems['label'];
                                      }
                                      if ($specs_sp_item['label'] == $field_labels):
                          ?>
                                      <div class="col py-2 border-top product-spec-content">
                                          <?php echo $specs_sp_items; ?>
                                      </div>

                          <?php   
                                      endif;
                                endforeach;   
                              endif;
                          ?>
                          
                        <?php endforeach;?>  
                    </div>   
               <?php //endif;
                 endforeach;  
                endif;
              endforeach;
        ?>
        <?php /*
          $loai_sp = get_field("product_type");
          $specs_sp = "";

          switch ($loai_sp) {
            case "bon_cau":
              //$specs_sp = site_acf_get_fields( 'bon_cau_specs', $group = 1 );
              $specs_sp = get_field("bon_cau_specs", $product->get_id());
              break;
            case "bon_tam":   
              $specs_sp = get_field("bon_tam_specs", $product->get_id());
              break;
            case "bon_tieu":   
              $specs_sp = get_field("bon_tieu_specs", $product->get_id());
              break; 
            case "lavabo":   
              $specs_sp = get_field("lavabo_specs", $product->get_id());
              break; 
            case "nap_bon_cau":   
              $specs_sp = get_field("nap_bon_cau_specs", $product->get_id());
              break;   
            case "sen_bon_tam":   
              $specs_sp = get_field("sen_bon_tam_specs", $product->get_id());
              break;    
            case "sen_tam":   
              $specs_sp = get_field("sen_tam_specs", $product->get_id());
              break;
            case "voi_lavabo":   
              $specs_sp = get_field("voi_lavabo_specs", $product->get_id());
              break; 
            case "bep":   
              $specs_sp = get_field("bep_specs", $product->get_id());
              break; 
            case "lo":   
              $specs_sp = get_field("lo_specs", $product->get_id());
              break;   
            case "cong_tac_o_cam":   
              $specs_sp = get_field("cong_tac_o_cam_specs", $product->get_id());
              break; 
            case "thiet_bi_dong_cat":   
              $specs_sp = get_field("thiet_bi_dong_cat_specs", $product->get_id());
              break;        
            default:
              $specs_sp = "";    
          }

          if ($specs_sp):
            //var_dump($specs_sp);
            foreach ($specs_sp as $key => $specs_sp_item): 
              $fielditem = acf_get_field($key);
              $field_object = get_field_object($key , $post_id);
              //var_dump($fielditem);
              if ($fielditem) {
                $field_label = $fielditem['label'];
              }  
          ?>
            <div class="row row-cols-md-5 flex-nowrap">
              <div class="col py-2 border-top product-spec-title bg-grey-lightest">
                <?php echo $field_label; ?>
              </div>
              <div class="col py-2 border-top product-spec-content">
                <?php echo $specs_sp_item; ?>
              </div>
            </div>
          <?php endforeach;   
            endif;
          */
        ?>

      </div>

      <?php endif; ?>

      <div class="d-md-none d-block">
        <div class="d-flex border-bottom">
          <div class="fw-bold">Sản phẩm</div>
        </div>
        <div class="d-flex mb-3 spec-contents">
          <?php foreach( $products as $i => $product ): ?>
          <div class="<?php echo $i<$n-1?'border-end ':'';?>spec-contents-cell">
            <?php echo $product->get_title(); ?>
          </div>
          <?php endforeach;?>
        </div>
        <div class="d-flex border-bottom">
          <div class="fw-bold">Thương hiệu</div>
        </div>
        <div class="d-flex mb-3 spec-contents">
          <?php foreach( $products as $i => $product ): ?>
          <div class="<?php echo $i<$n-1?'border-end ':'';?>spec-contents-cell">
            <?php site_the_product_brand( $product->get_id() ); ?>
          </div>
          <?php endforeach;?>
        </div>
        <?php foreach( $acf_fields as $field ):?>
        <div class="d-flex border-bottom">
          <div class="fw-bold"><?php echo $field['title'];?></div>
        </div>
        <div class="d-flex mb-3 spec-contents">
          <?php foreach( $products as $i => $product ): ?>
          <div class="<?php echo $i<$n-1?'border-end ':'';?>spec-contents-cell">
            <?php the_field( $field['name'], $product->get_id() ); ?>
          </div>
          <?php endforeach;?>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </div>
</div>
<script>
var cp_ids = [<?php echo implode(',', $ids);?>];
</script>
<?php

get_footer();
