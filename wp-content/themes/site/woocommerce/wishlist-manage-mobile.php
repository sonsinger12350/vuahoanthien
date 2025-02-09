<?php
/**
 * Wishlist manage template - Modern layout
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\Wishlist\Templates\Wishlist\Manage
 * @version 3.0.0
 */

/**
 * Template variables:
 *
 * @var $page_title            string Page title
 * @var $template_part         string Template part currently being loaded (manage)
 * @var $user_wishlists        YITH_WCWL_Wishlist[] Array of user wishlists
 * @var $show_number_of_items  bool Whether to show number of items or not
 * @var $show_date_of_creation bool Whether to show date of creation or not
 * @var $show_download_as_pdf  bool Whether to show download button or not
 * @var $show_rename_wishlist  bool Whether to show rename button or not
 * @var $show_delete_wishlist  bool Whether to show delete button or not
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<ul class="shop_table cart wishlist_table wishlist_manage_table responsive mobile" cellspacing="0">

	<?php
	if ( ! empty( $user_wishlists ) ) :
		$user_wishlists = array_reverse($user_wishlists);
		foreach ( $user_wishlists as $wishlist ) :
			?>
			<li data-wishlist-id="<?php echo esc_attr( $wishlist->get_id() ); ?>">
				<div class="item-wrapper">
					<div class="item-details">
						<div class="wishlist-name wishlist-title <?php echo $show_rename_wishlist ? 'wishlist-title-with-form' : ''; ?>" >
							<h3>
								<a class="wishlist-anchor" href="<?php echo esc_url( $wishlist->get_url() ); ?>"><?php echo esc_html( $wishlist->get_formatted_name() ); ?></a>
							</h3>

							<?php if ( $show_rename_wishlist ) : ?>
								<a class="show-title-form">
									<?php
									/**
									 * APPLY_FILTERS: yith_wcwl_edit_title_icon
									 *
									 * Filter the icon of the edit Wishlist title button.
									 *
									 * @param string $icon Edit title icon
									 *
									 * @return string
									 */
									echo yith_wcwl_kses_icon( apply_filters( 'yith_wcwl_edit_title_icon', '<i class="fa fa-pencil"></i>' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
								</a>
							<?php endif; ?>

							<?php if ( $show_delete_wishlist || $show_download_as_pdf ) : ?>
								<div class="wishlist-item-delete">
									<div class="value">
										<?php if ( $show_download_as_pdf ) : ?>
											<a class="wishlist-download" href="<?php echo esc_url( $wishlist->get_download_url() ); ?>">
												<i class="fa fa-download"></i>
											</a>
										<?php endif; ?>

										<?php if ( $show_delete_wishlist ) : ?>
											<?php /* ?>
											<a class="wishlist-delete" onclick="return confirm('<?php esc_html_e( 'Are you sure you want to delete this wishlist?', 'yith-woocommerce-wishlist' ); ?>');" href="<?php echo esc_url( $wishlist->get_delete_url() ); ?>"><i class="fa fa-trash"></i></a>
											<?php */ ?>
											<a class="wishlist-delete" href="<?php echo esc_url( $wishlist->get_delete_url() ); ?>"><i class="fa fa-trash"></i></a>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>

						</div>

						<?php if ( $show_rename_wishlist ) : ?>
							<div class="hidden-title-form">
								<input type="text" value="<?php echo esc_attr( $wishlist->get_formatted_name() ); ?>" name="wishlist_options[<?php echo esc_attr( $wishlist->get_id() ); ?>][wishlist_name]" />
								<div class="edit-title-buttons">
									<a href="#" class="hide-title-form">
										<?php
										/**
										 * APPLY_FILTERS: yith_wcwl_cancel_wishlist_title_icon
										 *
										 * Filter the icon of the Cancel button when editing the Wishlist title.
										 *
										 * @param string $icon Cancel icon
										 *
										 * @return string
										 */
										echo yith_wcwl_kses_icon( apply_filters( 'yith_wcwl_cancel_wishlist_title_icon', '<i class="fa fa-remove"></i>' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										?>
									</a>
									<a href="#" class="save-title-form">
										<?php
										/**
										 * APPLY_FILTERS: yith_wcwl_save_wishlist_title_icon
										 *
										 * Filter the icon of the Save button when editing the Wishlist title.
										 *
										 * @param string $icon Save icon
										 *
										 * @return string
										 */
										echo yith_wcwl_kses_icon( apply_filters( 'yith_wcwl_save_wishlist_title_icon', '<i class="fa fa-check"></i>' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										?>
									</a>
								</div>
							</div>
						<?php endif; ?>

						<div class="item-details-table wishlist-item-row">
							<?php if ( $show_number_of_items ) : ?>
								<div class="wishlist-item-count">
									<?php /* ?><div class="label"><?php esc_html_e( 'Items:', 'yith-woocommerce-wishlist' ); ?></div><?php */ ?>
									<div class="value">
										<?php
										// translators: 1. number of items in wishlist.
										echo esc_html( sprintf( __( '%d items', 'yith-woocommerce-wishlist' ), $wishlist->count_items() ) );
										?>
									</div>
								</div>
							<?php endif; ?>
						</div>
						<div class="wishlist-item-row">	
							<div class="wishlist-privacy">
								<div class="label"><?php esc_html_e( 'Visibility:', 'yith-woocommerce-wishlist' ); ?></div>
								<div class="value">
									<select name="wishlist_options[<?php echo esc_attr( $wishlist->get_id() ); ?>][wishlist_privacy]" class="wishlist-visibility selectBox">
										<option value="0" class="public-visibility" <?php selected( $wishlist->get_privacy(), 0 ); ?> ><?php echo wp_kses_post( yith_wcwl_get_privacy_label( 0 ) ); ?></option>
										<option value="1" class="shared-visibility" <?php selected( $wishlist->get_privacy(), 1 ); ?> ><?php echo wp_kses_post( yith_wcwl_get_privacy_label( 1 ) ); ?></option>
										<option value="2" class="private-visibility" <?php selected( $wishlist->get_privacy(), 2 ); ?> ><?php echo wp_kses_post( yith_wcwl_get_privacy_label( 2 ) ); ?></option>
									</select>
								</div>
							</div>

							<?php if ( $show_date_of_creation ) : ?>
								<tr class="wishlist-dateadded">
									<td class="label"><?php esc_html_e( 'Created on:', 'yith-woocommerce-wishlist' ); ?></td>
									<td class="value"><?php echo esc_html( $wishlist->get_date_added_formatted() ); ?></td>
								</tr>
							<?php endif; ?>

							
						</div>
					</div>
					<!-- Add thumbnail -->
					<div class="product-thumbnail">
						<?php
						if ( $wishlist->has_items() ) :
							 $items = $wishlist->get_items(3);

						    for ($i = 0; $i < 3; $i++) : // Loop exactly 3 times
						        $item = isset($items[$i]) ? $items[$i] : null; // Check if the item exists

						        // Check if the current item has an image
						        $has_image = $item && $item->get_product()->get_image();

						        // Define the classes for the div
						        $classes = 'product-thumbnail-item';
						        if (!$has_image) {
						            $classes .= ' no-image';
						        }
						        ?>
						        <div class="<?php echo esc_attr($classes); ?>">
						            <?php
						            // Check if the item exists and has an image, then display the image
						            if ($item && $has_image) {
						                echo wp_kses_post($item->get_product()->get_image());
						            }
						            ?>
						        </div>
						    <?php endfor; ?>
 						<?php else : ?>
							<?php for ($i = 0; $i < 3; $i++) : ?>
 								 <div class="product-thumbnail-item no-image"></div>
 							<?php endfor; ?>
							
						<?php endif; ?>
					</div>
					<!-- end add thumbnail -->
				</div>
			</li>
			<?php
		endforeach;
	else :
		?>
		<li class="wishlist-empty">
			<?php echo wp_kses_post( YITH_WCWL_Frontend_Premium()->get_no_wishlist_message() ); ?>
		</li>
		<?php
	endif;
	?>

</ul>
