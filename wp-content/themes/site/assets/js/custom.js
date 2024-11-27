(function($){

    $(window).on('load', function(){

        var body = $('body');

        $('form.comment-form').each(function(){
            var f = $(this);

            $('.form-suggest-text .btn-text', f).on('click', function(){
                var b = $(this), i = $('#yourReview'), text = i.val();

                if( !b.hasClass('active') ) 
                {
                    i.val( text + ( text != '' ? ', ' : '' ) + b.text() );
                    
                    b.addClass('active');
                }
            });
        });

        $('input[type=checkbox].input-compare').on('change',function(){
            var checkboxes = $('input[type=checkbox]:checked');
            if( this.checked && checkboxes.length>1 ) {
                var ids = [];

                checkboxes.each(function(i){
                    if( i<4 ) {
                        ids.push(this.value);
                    }
                });
                
                $('#compareFooter').each(function(){
                    var a = $('a',this);

                    $('.number', this).text(checkboxes.length);

                    a.attr('href', a.attr('data-href') + '?ids=' + ids.join('-') );
                }).removeClass('d-none');

                // location.href = site_setting.compare_url + '?ids=' + ids.join('-');
            } else {
                $('#compareFooter').addClass('d-none');
            }
        });

        var comparies = $('.btn-remove-compare').on('click',function(e){
            e.preventDefault();

            var ids = [], value = this.value;

            comparies.each(function(){
                if( value != this.value ) {
                    ids.push(this.value);
                }
            });

            if( ids.length>1 ) {
                location.href = site_setting.compare_url + '?ids=' + ids.join('-');

                console.log('url', location.href);
            } else {
                location.href = site_setting.home_url;
            }
        });

        $('.btn-copy').on('click',function(e){
            e.preventDefault();

            $('.bi-clipboard', this).addClass('bi-clipboard-fill');
            
            $('.data-copy').each(function(){
                /* Select the text field */
                this.select();
                this.setSelectionRange(0, 99999); /* For mobile devices */

                /* Copy the text inside the text field */
                navigator.clipboard.writeText(this.value);
            });
        });

        $('#list-products .row').each(function(){
            var p = $(this), paged = 1;

            $('.btn-loadmore').on('click',function(e){
                e.preventDefault();

                var b = $(this), total = parseInt( b.data('total') ), parts = b.data('href').split('?');
                
                b.attr('disabled','disabled');
                
                body.addClass('loading');
                
                paged++;
                if( paged>total ) {
                    paged = 1;
                }
                
                $.post( b.data('href') + ( parts.length >= 2 ? '&' : '?' ) + 'paged=' + paged,{
                    ajax: ( new Date().getTime() )
                }, function( data ){
                    b.removeAttr('disabled');
                    body.removeClass('loading');

                    p.append( $(data).find('.row').html() );
                });
            });
        });

        if( 0 && location.search!='' ) {
            $('#top').each(function(){
                $('html, body').animate({
                    scrollTop: $(this).offset().top
                }, 500);
                // window.scrollTo(0,window.scrollTo(0,10));
            });
        }

        $('.sidebar-form').each(function(){
            var f = this;

            $('input[type=checkbox]',f).on('change', function(){
                f.submit();
            });
        });

        

        $('.product_short_description ul').each(function(){
            var ul = $(this);

            if( ul.find('img').length>0 ) {
                ul.addClass('list-icon');

                $('> li', ul).each(function(){
                    if( $(this).find('img').length>0 ) {
                        $(this).addClass('has-icon');
                    }
                });
            }
        });

        $('#payment .checkout-payment-method .form-check').click(function(){
            $('.form-check-description', this).slideDown();
        });

        $('.cart-form').each(function(){
            $('.quantity', this).on('change', quantity_change );
            $('.btn-change', this).on('mouseup touchend', function(){
                setTimeout(quantity_change,100);
            });
        });

        $('.card-footer').each(function(){
            var card = $(this);

            var a = $('a', card).addClass('addjs').on('click', function(e){
                e.preventDefault();
                
                $('.quantity', product_quantity).val(1);

                a.addClass('d-none');
                product_quantity.removeClass('d-none');

                a.parent().css('pointer-events','none');

                // popup_message('Đang thêm vào giỏ hàng');
                
                $.get(this.href, function(){
                    // popup_message('hide');

                    var cart = $('.shop_cart_count'), total = parseInt( cart.eq(0).text() ) + 1;

                    cart.text( total );

                    $('.nav-item-shop-cart').toggleClass('nav-item-has-count', total>0 );

                    // popup_message('Thêm giỏ hàng thành công! Giỏ hàng đang có ' +total + ' sản phẩm.');
                    // popup_message('Thêm giỏ hàng thành công!');

                    a.parent().css('pointer-events','');
                });
            });

            var product_quantity = $('.product-item-quantity', card).each(function(){
                var p = $(this);

                $('.btn-change', p).on('click', function(){
                    var e = $('.quantity', p), q = parseInt( e.val() );

                    if( q <= 0 ) {
                        return;
                    }

                    var cart = $('.shop_cart_count'), total = parseInt( cart.eq(0).text() );

                    if( $(this).hasClass('btn-cart-increase') ) {
                        q++;
                        total ++;
                    } else {
                        q--;

                        if( q<1 ) {
                            q = 0;
                            product_quantity.addClass('d-none');
                            a.removeClass('d-none');
                        }

                        total--;
                        if( total<0 ) {
                            total = 0;
                        }
                    }
                    
                    e.val( q );

                    cart.text( total );

                    $('.nav-item-shop-cart').toggleClass('nav-item-has-count', total>0 );

                    product_item_quantity_change( p, q );
                });
            });
        });

        $('.modal-voucher').each(function(){
            $('.list-my-coupons > .row').on('click', function(e){
                e.preventDefault(); 

                $('.control-coupon').val( $('small', this).text() );
            });
        });

        $('.modal-checkout-shipping').each(function(){
            var p = $(this);

            $('.shipping_address_list .form-check-label', p).on('click',function(){
                $('input', this).each(function(){
                    var i = $(this);
                    // console.log( i.data() );

                    $('#shipping_first_name').val( i.data('name') );
                    $('#shipping_address_1').val( i.data('address') );
                    $('#shipping_phone').val( i.data('phone') );
                });
            });

            $('.btn-close', p).on('click',function(){
                $('input', p).each(function(){
                    var i = $(this);

                    i.val( i.data('value') );
                });

                $('.shipping_address_list input', p).prop('checked', false);
            });
            
            $('.btn-save', p).on('click',function(){
                var prefix = '#billing_', v = '';

                if( 1 || $('#ship-to-different-address-checkbox').prop('checked') ) {
                    prefix = '#shipping_';
                }

                v = $(prefix+'first_name').val();
                $(prefix+'first_name').data('value', v);
                $('.shipping_name_value').text( v );

                v = $(prefix+'address_1').val();
                $(prefix+'address_1').data('value', v);
                $('.shipping_address_value').text( v );

                v = $(prefix+'phone').val();
                $(prefix+'phone').data('value', v);
                $('.shipping_phone_value').text( v );
            });
        });

        $('.woocommerce-shipping-fields').each(function(){
            if( $('#ship-to-different-address-checkbox').val() == '1' ) {
                var fields = [
                    'first_name',
                    'last_name',
                    'address_1',
                    'phone',
                ];
                
                $.each(fields, function(i, key){
                    var b = $('#billing_' + key);
                    if( b.val() == '' ) {
                        $('#shipping_' + key).on('change',function(){
                            b.val( $(this).val() );
                        });
                    }
                });
            }
        });
        
        $('.card-img-top').each(function(i){
            var img = $(this);

            img.parent().css('background-image', "url('" + img.attr('src') + "'" );
        });

        $('.track-order-form').each(function(i){
            var f = this;

            $(f).on('submit',function(e){
                e.preventDefault();

                var v = $('.search-input', f).val();

                if( v!='' ) {
                    $('#trackOrderContent .card.order').hide();
                    $('#trackOrderContent .card.order-id-' + v.replace('#', '')).show();
                } else {
                    $('#trackOrderContent .card.order').show();
                }

                return false;
            });
        });

        /*
        $('.product-item').each(function(){
            var p = $(this);

            p.attr({
                'data-bs-toggle' : 'tooltip',
                'data-bs-delay' : 1000,
                'data-bs-title' : $('.card-text', p).text()
            });
        });
        */
        
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // if( url_search_get('coupons') == 'show' ) {
        //     $('#discountCode').modal('show');
        //     history.pushState({}, '', location.origin + location.pathname );
        // }

        $('.favorite-link').each(function(){
            var btn = $(this), id = btn.attr('href');
    
            if( id.substring(0,1) == '#' ) {
                btn.on('click',function(e){
                    e.preventDefault();
        
                    $(id).modal('show');
                });
                
                btn.parent().attr({
                    'data-bs-toggle' : "tooltip",
                    'data-bs-title': 'Vui lòng đăng nhập để lưu sản phẩm vào danh sách yêu thích.'
                });
            } else {
                btn.on('click',function(e){
                    e.preventDefault();
    
                    // console.log('favorite-link', this.href);
    
                    if( btn.hasClass('submiting') ) return;
                    btn.addClass('submiting');
    
                    var url = this.href, remove = false;
                    if( btn.hasClass('favorited') ) {
                        remove = true;
                        url = btn.data('remove');
                    }
                    
                    if( url!='' ) {
                        $.get(url, function(res){
                            // console.log('res', res );
    
                            btn.removeClass('submiting');
                            
                            var wishlist = $('.wishlist_count'), count = parseInt( wishlist.text() );
                            
                            if( remove ) {
                                count--;
                                if( count<0 ) {
                                    count = 0;
                                }
                                btn.removeClass('favorited');
                                $('i', btn).removeClass('bi-suit-heart-fill').addClass('bi-suit-heart');
                            } else {
                                $('i', btn).removeClass('bi-suit-heart').addClass('bi-suit-heart-fill');
                                btn.addClass('favorited');
                                count++;
                            }
    
                            wishlist.text( count );

                            $('.nav-item-wishlist').toggleClass('nav-item-has-count', count>0 );
                        });
                    }
                }).addClass('jsed');
            }
        });
    });
    
    function url_search_get( key = '', value = '' )
    {
        var s = location.search;

        if( typeof key == 'string' && key != '' && s != '' ) {
            s.substring(1).split('&').forEach( v => {
                var a = v.split('=');
                if( a.length == 2 && value == '' && a[0] == key ) {
                    value = a[1];
                }
            });
        }
        
        return value;
    }

    var qty_change_id = 0;
    function quantity_change()
    {
        $('.cart-form').each(function(){
            var p = $(this), total = 0, url = this.action;

            $('.cart-item', p).each(function( i ){
                var subtotal = parseInt( $('.product-quantity', this).data('price') ) * parseInt( $('.quantity', this).val() );

                total += subtotal;

                $('.cart_sub_total', this).html( subtotal.format(0,0) );

            });
            
            // $('.btn-update-cart', p).removeAttr('disabled');

            $('.cart_coupon', p).each(function(){
                var e = $(this), min = parseInt( e.data('minimum') || 0 );
                if( total>min ) {
                    total -= parseInt( e.data('amount') || 0 );
                } else {
                    e.remove();
                }
            });
            
            var cart_total = $('.cart_total', p).html( total.format(0,0) );

            if( qty_change_id>0 ) {
                clearTimeout(qty_change_id);
                qty_change_id = 0;
            }

            qty_change_id = setTimeout(function(){
                p.css('pointer-events','none');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: p.serialize(), // serializes the form's elements.
                    success: function(data)
                    {
                        // console.log('data', data, $(data).find('.cart_total') );

                        $(data).find('.cart_total').each(function(){
                            cart_total.html( $(this).text() );
                        });

                        p.css('pointer-events','');
                        
                        // console.log('data', data); // show response from the php script.
                    }
                });
            }, 2000);
        });
    }

    function product_item_quantity_change( p, quantity )
    {
        var qtyTimeout = parseInt( p.data('qtyTimeout') || 0 );
        if( qtyTimeout>0 ) {
            clearTimeout(qtyTimeout);
        }

        qtyTimeout = setTimeout(function(){
            p.parent().css('pointer-events','none');

            // popup_message('Đang cập nhật giỏ hàng');

            $.ajax({
                type: "POST",
                url: p.data('href'),
                data: {
                    change_quantity: quantity,
                    check : ( new Date().getTime() )
                }, // serializes the form's elements.
                success: function( data )
                {
                    // popup_message('hide');

                    // total = parseInt( data );

                    // $('.shop_cart_count').text( total );

                    // $('.nav-item-shop-cart').toggleClass('nav-item-has-count', total>0 );

                    p.parent().css('pointer-events','');
                    
                    // console.log('data', data); // show response from the php script.
                }
            });
        }, 2000);

        p.data('qtyTimeout', qtyTimeout);
    }

    function popup_message( text )
    {
        $('#modal-message').each(function(){
            var p = $(this);

            if( text == 'hide' ) {
                return p.modal('hide');
            }

            if( p.data('show') == 1 ) return;

            $('.message', p).text( text );

            p.modal('show');

            p.data('show', 1);

            setTimeout(function(){
                p.modal('hide').data('show', 0);
            },3000);
        });
    }
    
    $('.wishlist-page-links .popup-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the link from following its href

        // Show the modal with the specified data-bs-target
        var targetModal = $(this).data('bs-target');
        $(targetModal).modal('show');
    });
    

  //   $(".pp_woocommerce form").on("submit", function(event) {
  //       event.preventDefault();

  //       // Perform any necessary form data validation here

  //       // Redirect to link A
  //       window.location.href = "https://vuahoanthien.com/san-pham-yeu-thich-cua-ban/?wishlist-action=manage";
  //       // Perform any necessary form data validation here
  // });
    
})(jQuery);

/**
 * Number.prototype.format(n, x)
 * 
 * @param integer n: length of decimal
 * @param integer x: length of sections
 */
Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';

    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};