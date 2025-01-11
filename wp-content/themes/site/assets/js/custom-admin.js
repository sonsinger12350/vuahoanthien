jQuery(function($){
	$(document).ready(function() {
		$('body').on('input', '.woocommerce_order_items input.item_cost', function() {
			let tr = $(this).closest('.item');
			let cost = $(this).val();
			let qty = tr.find('input.quantity').val();
			let coupon = tr.find('input.item_cost').attr('data-coupon');
			let total = 0;
			let subtotal = 0;

			if (cost) {
				total = (Number(cost) * Number(qty)) - Number(coupon);
				subtotal = (Number(cost) * Number(qty));
			}

			tr.find('input.line_total').val(total);
			tr.find('input.line_total').attr('data-total', total);

			tr.find('input.line_subtotal').val(subtotal);
			tr.find('input.line_subtotal').attr('data-subtotal', subtotal);
		});

		$('body').on('input', '.woocommerce_order_items input.quantity', function() {
			let tr = $(this).closest('.item');
			let cost = tr.find('input.item_cost').val();
			let qty = $(this).val();
			let coupon = tr.find('input.item_cost').attr('data-coupon');
			let total = 0;
			let subtotal = 0;

			if (qty) {
				total = (Number(cost) * Number(qty)) - Number(coupon);
				subtotal = (Number(cost) * Number(qty));
			}

			tr.find('input.line_total').val(total);
			tr.find('input.line_subtotal').val(subtotal);
		});

		$('body').on('input', '.woocommerce_order_items input.line_total', function() {
			let tr = $(this).closest('.item');
			let cost = 0;
			let qty = tr.find('input.quantity').val();
			let coupon = tr.find('input.item_cost').attr('data-coupon');
			let total = $(this).val();
			let dataTotal = tr.find('input.quantity').attr('data-qty')*total;
			let dataSubtotal = Number(dataTotal) + Number(coupon);

			if (total) cost = Number(total) / Number(qty);

			tr.find('input.item_cost').val(cost);
			tr.find('input.line_total').attr('data-total', dataTotal);
			tr.find('input.line_subtotal').attr('data-subtotal', dataSubtotal);
		});
	});
});