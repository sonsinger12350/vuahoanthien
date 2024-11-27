<?php
global $product;
$post_id = $product->get_id();



$fields = site_acf_get_fields( 'Product Fields', $group = 1 );

//var_dump($fields);
$post_content = get_the_content();
$contentFixed = preg_replace( '/<p[^>]+>/i', '<p>', $post_content );

// Get Specify Spec of Category Product Group 
$loai_sp = get_field("product_type");
$specs_sp = "";

switch ($loai_sp) {
  case "bon_cau":
    //$specs_sp = site_acf_get_fields( 'bon_cau_specs', $group = 1 );
    $specs_sp = get_field("bon_cau_specs");
    break;
  case "bon_tam":   
    $specs_sp = get_field("bon_tam_specs");
    break;
  case "bon_tieu":   
    $specs_sp = get_field("bon_tieu_specs");
    break; 
  case "lavabo":   
    $specs_sp = get_field("lavabo_specs");
    break; 
  case "nap_bon_cau":   
    $specs_sp = get_field("nap_bon_cau_specs");
    break;   
  case "sen_bon_tam":   
    $specs_sp = get_field("sen_bon_tam_specs");
    break;    
  case "sen_tam":   
    $specs_sp = get_field("sen_tam_specs");
    break;
  case "voi_lavabo":   
    $specs_sp = get_field("voi_lavabo_specs");
    break; 
  case "bep":   
    $specs_sp = get_field("bep_specs");
    break; 
  case "lo":   
    $specs_sp = get_field("lo_specs");
    break;   
  case "cong_tac_o_cam":   
    $specs_sp = get_field("cong_tac_o_cam_specs");
    break; 
  case "thiet_bi_dong_cat":   
    $specs_sp = get_field("thiet_bi_dong_cat_specs");
    break;  
  case "voi_nuoc":   
    $specs_sp = get_field("voi_nuoc_specs");
    break;
  case "may_bom":   
    $specs_sp = get_field("may_bom_specs");
    break;    
  case "may_say_tay":   
    $specs_sp = get_field("may_say_tay_specs");
    break;   
  case "tu_dien":   
    $specs_sp = get_field("tu_dien_specs");
    break;   
  case "quat_hut":   
    $specs_sp = get_field("quat_hut_specs");
    break;   
  case "khoa_dien_tu":   
    $specs_sp = get_field("khoa_dien_tu_specs");
    break;
  case "bon_nuoc":   
    $specs_sp = get_field("bon_nuoc_specs");
    break;
  case "may_nlmt":   
    $specs_sp = get_field("may_nlmt_specs");
    break;
  case "tay_hoi":   
    $specs_sp = get_field("tay_hoi_specs");
    break;
  case "tay_nam_cua":   
    $specs_sp = get_field("tay_nam_cua_specs");
    break;
  case "phu_kien_khoa_dien_tu":   
    $specs_sp = get_field("phu_kien_khoa_dien_tu_specs");
    break;
  case "led_tuyp":   
    $specs_sp = get_field("led_tuyp_specs");
    break;
  case "led_ban_nguyet":   
    $specs_sp = get_field("led_ban_nguyet_specs");
    break;
  case "led_bulb":   
    $specs_sp = get_field("led_bulb_specs");
    break;
  case "led_downlight":   
    $specs_sp = get_field("led_downlight_specs");
    break;
  case "led_khan_cap":   
    $specs_sp = get_field("led_khan_cap_specs");
    break;
  case "phu_kien_tu_bep":   
    $specs_sp = get_field("phu_kien_tu_bep_specs");
    break;
  case "chau_rua_chen":   
    $specs_sp = get_field("chau_rua_chen_specs");
    break;
  case "may_hut_mui":   
    $specs_sp = get_field("may_hut_mui_specs");
    break;
  case "may_rua_chen":   
    $specs_sp = get_field("may_rua_chen_specs");
    break;  
  case "tu_lanh":   
    $specs_sp = get_field("tu_lanh_specs");
    break; 
  case "tu_ruou":   
    $specs_sp = get_field("tu_ruou_specs");
    break;
  case "gach":   
    $specs_sp = get_field("gach_specs");
    break;
  case "vat_tu_op_lat":   
    $specs_sp = get_field("vat_tu_op_lat_specs");
    break;
  case "bo_dieu_khien_trung_tam":   
    $specs_sp = get_field("bo_dieu_khien_trung_tam_specs");
    break;  
  case "cam_bien_thong_minh":   
    $specs_sp = get_field("cam_bien_thong_minh_specs");
    break; 
  case "cong_tac_thong_minh":   
    $specs_sp = get_field("cong_tac_thong_minh_specs");
    break;  
  case "den_thong_minh":   
    $specs_sp = get_field("den_thong_minh_specs");
    break;  
  case "bo_dieu_khien_hong_ngoai":   
    $specs_sp = get_field("bo_dieu_khien_hong_ngoai_specs");
    break; 
  case "pittong_nang_canh_tu":   
    $specs_sp = get_field("pittong_nang_canh_tu_specs");
    break;                                                    
  default:
    $specs_sp = "";  
}


?>
<div class="row">
  <div class="col-12 col-md-7">
    <div class="container-tambo section-bg bg-lights mb-3" id="productOverview">
      <div class="row">
        <h2 class="section-header border-0">
          <span>Chi tiết sản phẩm</span>
        </h2>
        <div class="explore-more">
          <div class="collapse" id="detailMore">
            <div class="col-12 d-flex d-lg-block flex-column"> <!-- block-fade-sm-down -->
              <?php echo $contentFixed;//the_content();?>
            </div>
          </div>
          <div class="explore-more-action">
            <a class="btn btn-link btn-viewmore" data-bs-toggle="collapse" href="#detailMore" role="button"
              aria-expanded="false" aria-controls="detailMore" id="viewMoreLink">
              <span class="text-1">Xem thêm</span>
              <span class="text-2">Rút gọn</span>
            </a>
          </div>
          <script>
              document.addEventListener('DOMContentLoaded', function () {
              const viewMoreLink = document.getElementById('viewMoreLink');
              const detailMore = document.getElementById('detailMore');
              const productSpecsElement = document.getElementById('productOverview');
      
              // Listen for the "show.bs.collapse" event
              detailMore.addEventListener('show.bs.collapse', function () {
                // Add your desired class to the <a> element when the second <span> is shown
                viewMoreLink.classList.add('linkShow');
              });
              // Listen for the "hide.bs.collapse" event
              detailMore.addEventListener('hide.bs.collapse', function () {
                // Remove the specified class from the <a> element when the second <span> is hidden
                viewMoreLink.classList.remove('linkShow');
                productSpecsElement.scrollIntoView({ behavior: 'smooth' });
              });
            });


          </script>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-5">
    <div class="container-tambo bg-light" data-modal-id="productSpecsModal" id="productSpecs"> <!--  pb-3 mb-5 section-clickable-sm-down modal-sm-down -->
      <div class="section-bg">
        <div class="row">
          <h2 class="section-header border-0">
            <span>Thông số kỹ thuật</span>
          </h2>
        </div>
        <div class="product-spec"> <!-- block-fade-sm-down -->
          <?php 
          $tinh_nang_khac = array();
          foreach( $fields as $key => $field ):    
            //echo $key;       
            $fielditem = acf_get_field($key);
            //$field_object = get_field_object($key , $post_id);
            //var_dump($fielditem);
            //echo $fielditem['type'];
            //if( preg_match('/compare/i', $field['wrapper']['class'] ) == false ) continue;
            if ($fielditem['name'] == 'feature') {
              $tinh_nang_khac[] = $fielditem;
            }
            if ($fielditem['type'] != 'text') continue;
            if ( get_field($field['name']) != null):
          ?>
            <div class="row mx-lg-0 spec-01">
              <div class="col-4 py-2 border-top product-spec-title bg-grey-lightests">
                <?php echo $field['title'];?>
              </div>
              <div class="col-8 py-2 border-top product-spec-content">
                <?php the_field( $field['name'] );?>
              </div>
            </div>
          <?php 
            endif;
          endforeach;?>
          <?php
            //var_dump($specs_sp);
            if ($specs_sp):
              foreach ($specs_sp as $key => $specs_sp_item): 
                  $fielditem = acf_get_field($key);
                  $field_object = get_field_object($key , $post_id);
                  //var_dump($fielditem);
                  if ($fielditem) {
                    $field_label = $fielditem['label'];
                  }  
              ?>
              <div class="row mx-lg-0 spec-02">
                <div class="col-4 py-2 border-top product-spec-title bg-grey-lightests">
                  <?php echo $field_label; ?>
                </div>
                <div class="col-8 py-2 border-top product-spec-content">
                  <?php echo $specs_sp_item; ?>
                </div>
              </div>
          <?php endforeach;   
            endif;
          ?>
          <?php 
          $tinh_nang_specs = get_field( $tinh_nang_khac[0]['name'] );
          //var_dump($tinh_nang_specs);
          if ($tinh_nang_specs): ?>
            <div class="row mx-lg-0 spec-03">
              <div class="col-4 py-2 border-top product-spec-title bg-grey-lightests">
                <?php echo $tinh_nang_khac[0]['label'];?>
              </div>
              <div class="col-8 py-2 border-top product-spec-content">
                <?php the_field( $tinh_nang_khac[0]['name'] );?>
              </div>
            </div>
          <?php endif ?> 
        </div>
      </div>
      <?php
        //$files = get_field('files');
        $files_link = get_field('files_link');
        if( $files_link ): 
      ?>
      <div class="section-bg">
        <div class="row">
          <h2 class="section-header border-0">
            <span>Tài liệu đính kèm</span>
          </h2>
        </div>
        <div class="product-spec product-spec-documents">
          <?php foreach( $files_link as $field ):           
            
            $file['url'] = $field['file'];
            $file['title'] = $field['name'];
            // if( $field['name']!='' ) {
            //   $file['title'] = $field['name'];
            // }
          ?>
          <div class="row-item mx-lg-0">
            <a href="<?php echo $file['url'];?>" target="_blank">
              <i class="bi bi-file-earmark-pdf"></i>
              <span><?php echo $file['title'];?></span>
            </a>  
          </div>
          <?php endforeach;?>
        </div>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>