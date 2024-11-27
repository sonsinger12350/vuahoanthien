<?php
/**
 * Discount table
 *
 * This template can be overridden by copying it to yourtheme/advanced_woo_discount_rules/discount_table.php.
 *
 * HOWEVER, on occasion Discount rules will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 */
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if( empty($ranges) || empty($woocommerce) ) return; 

if(isset($ranges['layout']['bulk_variant_table']) && $ranges['layout']['bulk_variant_table'] == "default_variant_empty"){?>
    <div class="awdr-bulk-customizable-table"> </div><?php
} else {
?>
<div class="awdr-bulk-customizable-table">
    <h4>Mua giá bán sỉ</h4>
    <?php
    foreach ($ranges as $range) :
        if (!isset($range['discount_value'])){
            continue;
        }
        
        $for_text = '';

        if (isset($range['from']) && !empty($range['from']) && isset($range['to']) && !empty($range['to'])) {
            $discount_range = $range['from'] . ' - ' . $range['to'];
        } elseif (isset($range['from']) && !empty($range['from']) && isset($range['to']) && empty($range['to'])) {
            $discount_range = $range['from'];
            $for_text = 'trở lên';
        } elseif (isset($range['from']) && empty($range['from']) && isset($range['to']) && !empty($range['to'])) {
            $discount_range =  '0 - ' . $range['to'];
        } elseif (isset($range['from']) && empty($range['from']) && isset($range['to']) && empty($range['to'])) {
            $discount_range = '';
        }

        ?>
        <p class="wdr-discount-row" data-range="<?php esc_attr_e($discount_range); ?>" data-discounted-price="<?php esc_attr_e($range['discounted_price']); ?>">
          - Mua từ <b class="wdr-discount-range"><?php esc_html_e($discount_range); ?></b> sản phẩm <?php echo $for_text;?> với giá <b class="wdr-discount-price"><?php echo site_wc_price( $range['discounted_price'] ); ?>đ</b>
        </p>
    <?php endforeach;?>
</div>
<?php
}