document.addEventListener("DOMContentLoaded", function(){
    const loader = document.querySelector("#loader-wrapper");
    document.body.style.overflow = "auto";
    loader.className += " auto";
    
    window.addEventListener("load", function() {
        //setTimeout(function() {
            loader.remove();
        //}, 2000);
        // Delay the trackbar's appearance by 3 seconds (3000 milliseconds)
        document.body.style.overflow = "auto";
        
    });
});
 
window.addEventListener("load", function() {
  var slideContainers = document.querySelectorAll('.slide-product');
  slideContainers.forEach(function(slideContainer) {
    slideContainer.classList.add('loaded');
  });  
});

(function($){
    
    var body = $('body');

    $(window).on('load', function(){
        
        // $(document).on('click', '.yith-wcwl-add-button', function(e){
        //     alert("a");
        // });

        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })

        $('.woocommerce').addClass('row');

        $('form.change-pass-form').each(function(){
            var f = this;

            $(f).on('submit', function(e){
                var error = [];

                if( f['password_current'].value == '' ) {
                    error.push('Mật khẩu hiện tại');
                    f['password_current'].focus();
                } else if( f['password_1'].value == '' ) {
                    error.push('Mật khẩu mới');
                    f['password_1'].focus();
                } else if( f['password_2'].value != f['password_1'].value ) {
                    error.push('Xác nhận mật khẩu không chính xác');
                    f['password_2'].focus();
                }

                if( error.length>0 ) {
                    e.preventDefault();

                    alert('Vui lòng điền đầy đủ thông tin! (' + error.join(',')+')');
                }
            });
        });

        $('form.comment-form').each(function(){
            var f = this;

            $('.form-suggest-text .btn-text', f).on('click', function(){
                var b = $(this), i = $('#yourReview'), text = i.val();

                if( !b.hasClass('active') ) 
                {
                    i.val( text + ( text != '' ? ', ' : '' ) + b.text() );
                    
                    b.addClass('active');
                }
            });

            $(f).on('submit', function(e){
                e.preventDefault();

                var error = [];

                if( f['rating'].value == '' ) {
                    error.push('Đánh giá');
                } else if( f['comment'].value == '' ) {
                    error.push('Nội dung');
                } else if( f['author'].value == '' ) {
                    error.push('Tên của bạn');
                } 
                // else if( f['email'].value == '' ) {
                //     error.push('Email');
                // }
                
                if( error.length>0 ) {
                    alert('Vui lòng điền đầy đủ thông tin! (' + error.join(',')+')');

                    return;
                }

                popup_loading();

                var url = location.href;
                
                /* $.post( url, $(f).serialize() + '&ajax_i=' + ( new Date().getTime() ), function( res ) {
                    // console.log('res', res);

                    $('#writeReview').modal('hide');

                    popup_loading('hide');
                    popup_message( res.message );
                    
                    setTimeout(function(){
                        location.href = url;
                    }, 3000);
                },'JSON'); */

                var formData = new FormData(this);
                formData.append('ajax_i', new Date().getTime());
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    dataType: "json",
                    enctype: 'multipart/form-data',
                    processData: false,
                    success: function (response) {
                        
                        $('#writeReview').modal('hide');

                        popup_loading('hide');
                        popup_message( response.message );
                        
                        setTimeout(function(){
                            location.href = url;
                        }, 3000);
                    }
                });
            });
        });

        $('#customerReviews .btn-filter input').on('change',function(){
            this.form.submit();
        });

        $('.reviews-form').each(function(){
            var f = this;

            $(document).on('click', '.badge-filter', function(){
                f.submit();
            });
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

        $('.btn-reset-compare').on('click',function(e){
            e.preventDefault();

            $('.input-compare:checked').prop('checked', false);

            $('#compareFooter').addClass('d-none').find('.number').text(0);
        });


        $('.search-btn-item').on('click',function(e){
            e.preventDefault();
            $(this).toggleClass('active');
            body.addClass('searchbox-opening');
            $('.search-box form').toggleClass('active').slideToggle();
        });

        $('.btn-copy').on('click',function(e){
            e.preventDefault();

            $('.bi', this).addClass('active');

            // $('.bi', this).addClass('bi-clipboard-fill');
            
            $('.data-copy').each(function(){
                /* Select the text field */
                this.select();
                this.setSelectionRange(0, 99999); /* For mobile devices */

                /* Copy the text inside the text field */
                navigator.clipboard.writeText(this.value);
            });

            let popup = $('#yith-wcwl-popup-message')

            popup.find('#yith-wcwl-message').html('Copy link thành công');
            popup.css('display', 'block');
            
            setTimeout(() => {
                popup.css('display', 'none');
            }, 2000);
        });

        $('#list-products .product-list').each(function(){
            var p = $(this), paged = 1;

            $('.btn-loadmore').on('click',function(e){
                e.preventDefault();

                var b = $(this), 
                    total = parseInt( b.data('total') ), 
                    paged = parseInt( b.data('paged') || 1 ), 
                    parts = b.data('href').split('?');

                // $.get(parts, function(data) {
                //   var totalp = $(data).find('.product-list').length;
                //   console.log('Total number of products:', totalp);
                // }); 
                
                b.attr('disabled','disabled');
                
                body.addClass('loading');
                // popup_loading();
                
                paged++;
                if( paged > total ) {
                    // paged = 1;
                    return;
                }

                b.data('paged', paged);
                
                $.post( b.data('href') + ( parts.length >= 2 ? '&' : '?' ) + 'paged=' + paged,{
                    ajax: ( new Date().getTime() )
                }, function( data ){
                    b.removeAttr('disabled');
                    body.removeClass('loading');
                    // popup_loading('hide');

                    p.append( $(data).find('.product-list').html() );

                    // card_footer_add_event();
                });

                if( paged >= total ) {
                    b.remove();
                }

                // Add paged on url;
                const url = new URL(location);
                url.searchParams.set("pi", paged);
                history.pushState({}, "", url);
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

            $('input[type=checkbox], input[type=radio]',f).on('change', function() {
                if ($(this).attr('name') == 'prices[]') {
                    if ($(this).is(':checked')) {
                        $('input[type=checkbox][name="prices[]"]').prop('checked', false);
                        $(this).prop('checked', true);

                        $('[name="min_price"]').attr('disabled', true);
                        $('[name="max_price"]').attr('disabled', true);
                    }
                }

                f.submit();
            });
        });

        // $('.explore-more').each(function(){
        //     var f = this;
        //     var currentHeight = $('.collapse',f).height();
        //     if(currentHeight < 160) {
        //         $('.collapse',f).removeClass('collapse');
        //         $(this).addClass('tooShort');
        //     }
        // });

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

        $('.modal-voucher').each(function(){
            var modal = $(this);

            $('.list-my-coupons > .row').on('click', function(e){
                e.preventDefault(); 

                $('.control-coupon').val( $('small', this).text() );
            }).dblclick(function(e){
                e.preventDefault(); 

                $('.control-coupon').val( $('small', this).text() );

                $('form', modal).each(function(){
                    this.submit(); 
                });
            });
        });

        $('.shipping_address_list .form-check-label').on('click',function(){
            $('input', this).each(function(){
                var i = $(this);
                // console.log( i.data() );

                $('#shipping_first_name').val( i.data('name') );
                $('#shipping_address_1').val( i.data('address') );
                $('#shipping_phone').val( i.data('phone') );
            });
        });

        /*
        $('.modal-checkout-shipping').each(function(){
            var p = $(this);

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
        
        $('.shipping_address_fields').each(function(){
            // if( $('#ship-to-different-address-checkbox').val() == '1' ) {
                var fields = [
                    'first_name',
                    'last_name',
                    'address_1',
                    'phone',
                ];
                
                $.each(fields, function(i, key){
                    $('#shipping_' + key).on('change',function(){
                        $('#billing_' + key).val( $(this).val() );
                    });
                });
            // }
        });
        */
                
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

        $('form.checkout').each(function(){
            var f = this;

            $('.btn-checkout-now', f).on('click', function(e){
                e.preventDefault();

                var errors = [];

                $('.input-required', f).each(function(){
                    var input = $(this).removeClass('is-invalid'),
                        p = input.parent(),
                        c = '.invalid-feedback',
                        value = input.val();
                    
                    if( errors.length == 0 && ( 
                        value == '' || 
                        ( this.type == 'tel' && check_phone(value) == false )
                    ) ) {
                        input.addClass('is-invalid');
                        errors.push( input.data('message') );
                        if( p.find(c).show().length == 0 ) {
                            p.append('<div class="invalid-feedback">Thông tin chưa chính xác.'
                                    + ( this.type == 'tel' ? '( VD: 0931008893 )' : '' ) 
                                    +'</div>' );
                        }
                        this.focus();
                    } else {
                        p.remove(c);
                    }
                });

                if( errors.length>0 ) {
                    return false;
                }
                
                var btn = $(this).attr('disabled', 'disabled');

                popup_loading();

                $.post( '/?wc-ajax=checkout', $(f).serialize(), function( res ) {
                    // console.log('res', res);

                    popup_loading('hide');
                    
                    btn.removeAttr('disabled');

                    if( res.result == 'failure' ) {
                        popup_message( res.messages );
                    } else {
                        $('#modal-check-thanks').each(function(){
                            $('.order_id', this).html( '#' + res.order_id );
                        }).on('click', function(){
                            if( typeof site_setting.home_url == 'string' ) {
                                location.href = site_setting.home_url;
                            }
                        }).modal('show');
                    }
                });
            });
        });

        $('form.deal-form').each(function(){
            var f = this, modal = $('#modal-deal');

            $(f).on('submit', function(e){
                e.preventDefault();
                
                $('.message-loading', modal).removeClass('d-none');
                $(f).addClass('d-none');

                $.post( f.action, $(f).serialize(), function( res ) {
                    console.log('res', res);

                    $('.message-loading', modal).addClass('d-none');
                    $('.message-success', modal).removeClass('d-none');
                    
                    setTimeout(function(){
                        $('#modal-deal').modal('hide');
                    }, 3000);
                });
            });
        });

        $('.product-detail-form').each(function(){
            var f = this, $f = $(f);
            
            $('.btn-submit', f).on('click', function(e){
                e.preventDefault();
                
                var quantity = parseInt( $('.quantity', f).val() );
                
                $f.css('pointer-events','none');
                
                var cartLink = $('.shop-cart-bottom'); // Your cart link element
                cartLink.css('pointer-events','none');
                cartLink.addClass('disabled');
    
                $('.shop_cart_message').html('Đang thêm vào giỏ hàng').addClass('show');
    
                $.post(f.action, $f.serialize(), function(){
                    $('.shop_cart_message').removeClass('show');
    
                    var cart = $('.shop_cart_count'), total = parseInt( cart.eq(0).text() ) + quantity;
    
                    cart.text( total );
    
                    $('.nav-item-shop-cart').toggleClass('nav-item-has-count', total>0 );
                    $('.shop-cart-bottom').toggleClass('d-none', total == 0 );
    
                    // popup_message('Thêm giỏ hàng thành công! Giỏ hàng đang có ' +total + ' sản phẩm.');
                    // popup_message('Thêm giỏ hàng thành công!');
    
                    $f.css('pointer-events','');

                    cartLink.css('pointer-events','');
                    cartLink.removeClass('disabled');
    
                    f.reset();
                });
            });

            function auto_set_price(){
                $('.single_variation_wrap .woocommerce-variation-price', f).each(function(){
                    var regular = replace_price( $('del .woocommerce-Price-amount', this).text() ),
                        sale = replace_price( $('ins .woocommerce-Price-amount', this).text() );
                    
                    $('.product_regular_price').html( regular.format(0,0) + ' VNĐ');
                    $('.product_sale_price').html( sale.format(0,0) + ' VNĐ');
                });
            }
            auto_set_price();

            var variationID = 0;
            $('.variations select', f).on('change', function(){
                // if( variationID>0 ) {
                //     clearTimeout(variationID);
                // }
                // variationID = setTimeout(auto_set_price, 500);
                auto_set_price();
            });

            $('.variations .value', f).each(function(){
                var p = $(this);

                var labels = $('.variation_radio label', p).on('click', function(){
                    labels.removeClass('l_checked');
                    $(this).addClass('l_checked');
                    $('.variation_value select', p).val( $('input', this).val() ).trigger('change');
                });
            });
        });

        $('form.my-address-form').on('submit', function(e){
            e.preventDefault();
            
            var f = this, errors = [];

            $('.input-text', f).each(function(){
                var input = $(this).removeClass('is-invalid'),
                    p = input.parent(),
                    c = '.invalid-feedback',
                    value = input.val();
                
                if( errors.length == 0 && ( 
                    value == '' || 
                    ( this.type == 'tel' && check_phone(value) == false )
                ) ) {
                    input.addClass('is-invalid');
                    errors.push( input.data('message') );
                    if( p.find(c).show().length == 0 ) {
                        p.append('<div class="invalid-feedback">Thông tin chưa chính xác.</div>');
                    }
                    this.focus();
                } else {
                    p.remove(c);
                }
            });

            if( errors.length>0 ) {
                return false;
            }

            this.submit();
        });

        $('.lost-password-form').each(function () {
            var f = this;

            $('.btn-primary', f).on('click', function (e) {
                e.preventDefault();
                
                if (f['user_phone'].value == '') {

                    alert('Vui lòng điền đúng số điện thoại!');

                    return false;
                }

                var btn = $(this).attr('disabled', 'disabled');

                popup_loading();

                $.post(f.action, $(f).serialize() + '&ajax_reset=' + (new Date().getTime()), function (res) {
                    console.log('res', res);

                    popup_loading('hide');

                    btn.removeAttr('disabled');

                    if (res.code == 200) {
                        $('#resetpasswordSuccess').on('click', function(){
                            if( typeof site_setting.home_url == 'string' ) {
                                location.href = site_setting.home_url;
                            }
                        }).modal('show');

                        setTimeout(function(){
                            $('#resetpasswordSuccess').modal('hide');
                            if( typeof site_setting.home_url == 'string' ) {
                                location.href = site_setting.home_url;
                            }
                        },5000);
                    } else {
                        alert('Vui lòng điền đúng số điện thoại!');
                    }
                },'JSON');
            });
        });

        card_footer_add_event();

        /* Code in end */
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
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
            var p = $(this), 
                total = 0,
                quantity = 0,
                url = this.action,
                need_amount = 0,
                minimum_amount = 0,
                
                has_coupon = false,
                coupon_amount = 0,
                current_coupon_archived = 0,
                current_coupon_archived_value = 0,
                current_coupon_archived_code = '',
                current_coupon_archived_desc = 0;

            $('.cart-item', p).each(function( i ){
                var q = parseInt( $('.quantity', this).val() ),
                    subtotal = parseInt( $('.product-quantity', this).data('price') ) * q;

                quantity += q;
                total += subtotal;

                $('.cart_sub_total', this).html( subtotal.format(0,0) + '<ins class="woocommerce-Price-currencySymbol">đ</ins>' );
            });
            
            // $('.btn-update-cart', p).removeAttr('disabled');
            //console.log(total);

            $('.cart_coupon', p).each(function(){
                var e = $(this), min = parseInt( e.data('minimum') || 0 );
                if( total>min ) {
                    total -= parseInt( e.data('amount') || 0 );
                    // coupon_amount = parseInt( e.data('amount') || 0 );
                    // total -= coupon_amount;
                } else {
                    e.remove();
                }
            });

            $('.list-coupon .gift-item').each(function() {
                let e = $(this);
                let min = parseInt( e.data('min') || 0 );

                if ( total>min ) {
                    e.prev().addClass('disabled');
                    e.removeClass('disabled');
                }
                else {
                    e.addClass('disabled');
                }
            });

            let couponAvailable = '';
            let voucherAvailable = {};

            if (listEarnableGift.coupon) {
                for (let key in listEarnableGift.coupon) {
                    if (total > listEarnableGift.coupon[key]) couponAvailable = listEarnableGift.coupon[key];
                }
            }

            if (listEarnableGift.voucher) {
                for (let key in listEarnableGift.voucher) {
                    if (listEarnableGift.voucher[key]) {
                        for (let k in listEarnableGift.voucher[key]) {
                            if (total > listEarnableGift.voucher[key][k]) voucherAvailable[key] = listEarnableGift.voucher[key][k];
                        }
                    }
                }
            }

            if (couponAvailable || voucherAvailable) {
                $('.list-cart-gift-available .list-my-gifts .gift-item').addClass('d-none');
                $('.list-cart-gift-unavailable .list-my-gifts .gift-item').removeClass('d-none');
                $(`.list-cart-gift-available .list-coupon-available .gift-item[data-min="${couponAvailable}"]`).removeClass('d-none');
                $(`.list-cart-gift-available .list-coupon-available .gift-item[data-min="${couponAvailable}"] input[name="gift[]"]`).prop('checked', true);
                $(`.list-cart-gift-unavailable .list-coupon .gift-item[data-min="${couponAvailable}"]`).addClass('d-none');

                if (voucherAvailable) {
                    for (let key in voucherAvailable) {
                        $(`.list-cart-gift-available .list-voucher-available .gift-item[data-min="${voucherAvailable[key]}"]`).removeClass('d-none');
                        $(`.list-cart-gift-available .list-voucher-available .gift-item[data-min="${voucherAvailable[key]}"] input[name="gift[]"]`).prop('checked', true);
                        $(`.list-cart-gift-unavailable .list-voucher .gift-item[data-min="${voucherAvailable[key]}"]`).addClass('d-none');

                        $(`.list-cart-gift-unavailable .list-industry[data-industry="${key}"] .list-voucher .gift-item`).each(function() {
                            if (Number($(this).data('min')) < Number(voucherAvailable[key])) $(this).addClass('d-none');
                        })
                    }
                }

                $(`.list-cart-gift-unavailable .list-coupon .gift-item`).each(function() {
                    if (Number($(this).data('min')) < Number(couponAvailable)) $(this).addClass('d-none');
                })

                autoEarnGift();
                $('#btn-save-gift').click();
            }

            if ($('[name="gift[]"]:checked').length > 0) {
                let removeGift = [];

                $('[name="gift[]"]:checked').each(function() {
                    if ($(this).closest('.gift-item').hasClass('d-none')) removeGift.push($(this).val());
                });

                if (removeGift.length) {
                    $.ajax({
                        url: woocommerce_params.ajax_url,
                        type: "POST",
                        data: {
                            action: "remove_gift_from_cart",
                            gift: removeGift
                        },
                        success: function(response) {
                            if (response.success) {
                                $('.list-cart-gift-available .gift-item').each(function() {
                                    if ($(this).hasClass('d-none') && $(this).find('[name="gift[]"]').is(':checked')) {
                                        $(this).find('[name="gift[]"]').prop('checked', false);
                                    }
                                });
                            }
                            else {
                                alert('Đã có lỗi xảy ra');
                            }
                        }
                    });
                }
            }

            hideShowListGift();
            
            var cart_total = $('.cart_total', p).html( total.format(0,0) );
            $('.shop_cart_count').html( quantity );

            $('.list-coupons .coupon-item').each(function(){
                let min = parseInt( $(this).data('min') || 0 );
                let coupontitle = $(this).data('title');
                //console.log(coupontitle);
                
                if( total>=min ) {
                    has_coupon = true;
                    current_coupon_archived_value = min;
                    $('.list-coupons .coupon-item.checked').removeClass("checked");
                    $(this).addClass("checked");
                } 
                if( need_amount == 0 && min>total ) {
                    need_amount = min - total;
                    minimum_amount = min;
                }
            });

            //console.log(current_coupon_archived_value);

            if( need_amount>0 || has_coupon == true) {
                $('.need_amount').html( need_amount.format(0,0) );
                $('.row-need-amount').each(function(){
                    $('.minimum-amount', this).addClass('d-none');
                    $('.minimum-amount.' + minimum_amount, this).removeClass('d-none');  
                    $('.minimum-amount.' + current_coupon_archived_value, this).addClass('current-coupon');   
                    // current_coupon_archived_desc = $('.minimum-amount.' + current_coupon_archived_value, this).text();             
                    current_coupon_archived_desc = $('.minimum-amount.' + current_coupon_archived_value, this).attr("data-desc");              
                    current_coupon_archived_code = $('.minimum-amount.' + current_coupon_archived_value, this).attr("data-coupon");          
                    // console.log(current_coupon_archived_code);   
                }).removeClass('d-none').removeClass('current-coupon');
            } else {
                $('.row-need-amount').addClass('d-none');
            }

            if( need_amount == 0 && has_coupon == true) {
                $('.row-need-amount .line-1').addClass('d-none');
            } else {
                $('.row-need-amount .line-1').removeClass('d-none');
            }
            //current_coupon_archived_desc = $('.minimum-amount.current-coupon').text();
            //console.log(current_coupon_archived_desc);
            //console.log(has_coupon);
            //console.log(need_amount);

            if( has_coupon ) {
                // $('.cart_coupon_warning').html('Chúc mừng bạn đã đạt điều kiện sử dụng khuyến mãi <b style="text-transform: uppercase;">'+current_coupon_archived_code+'</b> là ' + current_coupon_archived_desc + ' <b>Vui lòng nhấn chọn mã khuyến mãi để sử dụng</b>').show();
                // $('.cart_coupon_warning').html('Chúc mừng bạn đã được tặng khuyến mãi <b style="text-transform: uppercase;">'+current_coupon_archived_code+'</b> là ' + current_coupon_archived_desc).show();
            } else {
                $('.cart_coupon_warning').hide();
            }

            if( qty_change_id>0 ) {
                clearTimeout(qty_change_id);
                qty_change_id = 0;
            }

            qty_change_id = setTimeout(function(){
                p.css('pointer-events','none');

                // popup_message('Đang cập nhật giỏ hàng');
                $('.shop_cart_message').html('Đang cập nhật giỏ hàng.').addClass('show');
                
                var cartLink = $('.shop-cart-bottom'); // Your cart link element
                cartLink.css('pointer-events','none');
                cartLink.addClass('disabled');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: p.serialize() + '&ajax_t=' + (new Date().getTime()), // serializes the form's elements.
                    success: function(data)
                    {                        
                        // console.log('data', data); // show response from the php script.

                        // popup_message('hide');
                        $('.shop_cart_message').removeClass('show');

                        // Update cart - sidebar
                        var db = $(data);

                        $.each([
                            '.cart-infomation',
                            '.cart-item-price',
                            '.cart_sub_total',
                        ], function(o,selector){
                            $(selector, db).each(function(i){
                                // console.log(selector);
                                // console.log($(this).html());
                                p.find(selector).eq(i).html( $(this).html() );
                            });
                        });

                        $('.product-quantity', db).each(function(i){
                            p.find('.product-quantity').eq(i).data('price', $(this).data('price') );
                        });

                        p.css('pointer-events','');
                        cartLink.css('pointer-events','');
                        cartLink.removeClass('disabled');
                    }
                });
            }, 500);
        });
    }

    function card_footer_add_event() 
    {
        $(document).on('change', '.input-compare', function(){
            var checkboxes = $('.input-compare:checked');
            if( checkboxes.length>1 ) {
                var ids = [];

                if( checkboxes.length>4 ) {
                    this.checked = false;
                    
                    return;
                }
                
                checkboxes.each(function(){
                    ids.push(this.value);
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

        $(document).on('click', '.favorite-link', function(e){
            e.preventDefault();

            var btn = $(this);            

            if( btn.hasClass('submiting') ) return;
            btn.addClass('submiting');

            var url = this.href, remove = false;
            if( btn.hasClass('favorited') ) {
                remove = true;
                url = btn.data('remove');
            }
            
            if( url!='' ) {

                var wishlist = $('.wishlist_count'), count = parseInt( wishlist.text() );
                    
                if( remove ) {
                    count--;
                    if( count<0 ) {
                        count = 0;
                    }
                    $('i', btn).removeClass('bi-suit-heart-fill').addClass('bi-suit-heart');
                    btn.removeClass('favorited');
                } else {
                    $('i', btn).removeClass('bi-suit-heart').addClass('bi-suit-heart-fill');
                    btn.addClass('favorited');
                    count++;
                }
                
                wishlist.text( count );

                $('.nav-item-wishlist').toggleClass('nav-item-has-count', count>0 );
                
                $.get(url, function(res){
                    // console.log('res', res );
                    btn.removeClass('submiting');                            
                });
            }
        });
        
        $('.card-img-top:not(.jsed)').each(function(i){
            var img = $(this).addClass('jsed');

            img.parent().css('background-image', "url('" + img.attr('src') + "'" );
        });
        
        $(document).on('click', '.product-item a[href*="add-to-cart"]', function(e){
            e.preventDefault();
            
            var a = $(this), 
                product_quantity = $('.product-item-quantity', a.parent());
            
            $('.quantity', product_quantity).val(1).data('value', 1);
            
            a.addClass('d-none');
            product_quantity.removeClass('d-none');
            
            product_item_quantity_change( product_quantity, 1 );

            var cart = $('.shop_cart_count'), total = parseInt( cart.eq(0).text() );

            total++;

            cart.text( total );

            $('.nav-item-shop-cart').toggleClass('nav-item-has-count', total>0 );
            $('.shop-cart-bottom').toggleClass('d-none', total == 0 );

            /*
            a.parent().css('pointer-events','none');

            // popup_message('Đang thêm vào giỏ hàng');
            $('.shop_cart_message').html('Đang thêm vào giỏ hàng').addClass('show');

            $.get(this.href + '&js-add=' + (new Date().getTime()), function(){
                // popup_message('hide');
                $('.shop_cart_message').removeClass('show');

                var cart = $('.shop_cart_count'), total = parseInt( cart.eq(0).text() ) + 1;

                cart.text( total );

                $('.nav-item-shop-cart').toggleClass('nav-item-has-count', total>0 );
                $('.shop-cart-bottom').toggleClass('d-none', total == 0 );

                // popup_message('Thêm giỏ hàng thành công! Giỏ hàng đang có ' +total + ' sản phẩm.');
                // popup_message('Thêm giỏ hàng thành công!');

                a.parent().css('pointer-events','');
            });
            */
        });

        $(document).on('click', '.product-item-quantity .btn-change', function(e){
            e.preventDefault();
            e.stopPropagation();

            var p = $(this).parent(), 
                eq = $('.quantity', p),
                q = parseInt( eq.val() );

            if( q <= 0 ) {
                return;
            }

            var cart = $('.shop_cart_count'), total = parseInt( cart.eq(0).text() );

            if( $(this).hasClass('btn-cart-increase') ) {
                q++;
                total++;
            } else {
                q--;

                if( q<1 ) {
                    q = 0;
                    p.addClass('d-none');
                    $('a', p.parent()).removeClass('d-none');
                }
                
                total--;
                if( total<0 ) {
                    total = 0;
                }
            }
            
            eq.val( q ).data('value', q);

            cart.text( total );

            $('.nav-item-shop-cart').toggleClass('nav-item-has-count', total>0 );
            $('.shop-cart-bottom').toggleClass('d-none', total == 0 );

            product_item_quantity_change( p, q );
        });

        /*
        $(document).on('change', '.product-item-quantity .quantity', function(){
            var eq = $(this),
                p = eq.parent(),
                q = parseInt( this.value ), 
                old = eq.data('value');

            if( q <= 0 ) {
                q = 0;
            }

            var cart = $('.shop_cart_count'), total = parseInt( cart.eq(0).text() );

            total += ( q - old );

            cart.text( total );

            $('.nav-item-shop-cart').toggleClass('nav-item-has-count', total>0 );
            $('.shop-cart-bottom').toggleClass('d-none', total == 0 );
            
            eq.val( q ).data('value', q);
            
            product_item_quantity_change( p, q );
        });
        */
    }

    function product_item_quantity_change( p, quantity )
    {
        var qtyTimeout = parseInt( p.data('qtyTimeout') || 0 );
        if( qtyTimeout>0 ) {
            clearTimeout(qtyTimeout);
        }

        qtyTimeout = setTimeout(function(){
            $('.product-item-quantity').css({
                'pointer-events':'none',
                'opacity': 0.6,
            });

            // popup_message('Đang cập nhật giỏ hàng');
            $('.shop_cart_message').html('Đang cập nhật giỏ hàng.').addClass('show');

            var cartLink = $('.shop-cart-bottom'); // Your cart link element
                cartLink.css('pointer-events','none');
                cartLink.addClass('disabled');

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
                    $('.shop_cart_message').removeClass('show');
                    $('.product-item-quantity').removeAttr('style');

                    cartLink.css('pointer-events','');
                    cartLink.removeClass('disabled');
                }
            });
        }, 1000);

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

            $('.message', p).html( text );

            p.modal('show');

            p.data('show', 1);

            setTimeout(function(){
                p.modal('hide').data('show', 0);
            },3000);
        });
    }

    function popup_loading( text )
    {
        $('#modal-loading').each(function(){
            var p = $(this);

            if( text == 'hide' ) {
                return p.modal('hide');
            }

            if( p.data('show') == 1 ) return;

            p.modal('show');

            p.data('show', 1);

            setTimeout(function(){
                p.modal('hide').data('show', 0);
            },3000);
        });
    }

    function check_phone( phone )
    {
        if( typeof phone != 'string' || phone.length != 10 ) return false;

        return phone.substring(0,1) == '0' && phone.match('[0-9]{10}') != null;
    }

    function replace_price( price_text )
    {
        return parseInt( price_text.replace('.','') );
    }

    /* create by Nhut */
    /*
    var $cartInfomation   = $(".cart-infomation");
    var $cartInfomationInside   = $(".cart-infomation .cart-infomation-inside");
    var $cartInfomationHeight  = $cartInfomationInside.height();
    var $cartInfomationWidth  = $cartInfomation.width();
    var $shoptableCart  = $(".shop_table");
    var $shoptableCartHeight  = $shoptableCart.height();
    var $cartInfomationCurrentTop     = $cartInfomation.offset().top;  
    var $shoptableCartCurrentTop     = $shoptableCart.offset().top;  
    $(window).scroll(function() {
        var $ScreenCurrentHeight = $(window).scrollTop();
        if ($ScreenCurrentHeight > $cartInfomationCurrentTop) {
            $cartInfomationInside.addClass("elm-sticked");
            $cartInfomationInside.width($cartInfomationWidth);
        } else if (($ScreenCurrentHeight + $cartInfomationHeight) > ($shoptableCartCurrentTop + $shoptableCartHeight )) {
            $cartInfomationInside.removeClass("elm-sticked");
        } else {
            $cartInfomationInside.removeClass("elm-sticked");
        }
        //console.log($ScreenCurrentHeight);
        //console.log($ScreenCurrentHeight );
        console.log($shoptableCartCurrentTop + $shoptableCartHeight );
        console.log($ScreenCurrentHeight + $cartInfomationHeight);
        //console.log($cartInfomationHeight);   
    });
    */

    $('.modal-auto-clear').on('shown.bs.modal', function () {
        $(this).delay(2000).fadeOut(200, function () {
            $(this).modal('hide');
        });
    });

    var isMobile = false;

    if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
        isMobile  = true;
    }  


    if(isMobile == true) {
        $('.has-child a.nav-link').append('<div class="more-btn hide-on-desktop"><i class="bi bi-chevron-right"></i></div>');
    }  

    $('.nav-main .nav-item.has-child a.nav-link').append('<div class="more-btn"><i class="bi bi-chevron-right"></i></div>');


    var $height_header = $('.highlight').height();
    $(window).scroll(function() {
        var $height = $(window).scrollTop();
        //alert('a');
        if(($height) > $height_header) {
            $('body').addClass('scrolling');
        } else {
            $('body').removeClass('scrolling');
        }
    });

    $('.wpcf7-response-output').bind('DOMSubtreeModified', function(){
        var textError = $('.wpcf7-response-output').text();
        $(this).delay(2000).slideToggle(200);
        if (textError != '') {
            
        }
    });

    // Listen for changes in the "quantity" inputs Wishlist List product
    $(".quantity").on("input", function() {
        // Find the closest "product-item-quantity" parent element
        var $productItemQuantity = $(this).closest(".product-item-quantity");
        
        // Find the corresponding "input-quantity" input within the same parent
        var $inputQuantity = $productItemQuantity.find(".input-quantity");

        // Copy the value from the "quantity" input to the "input-quantity" input
        $inputQuantity.val($(this).val());
    });

    // $(document).on('click', '.quantity-field .btn-change', function(e) {
    //     // Find the input element within the same container
    //     alert("aaa");

    //     // Find the input element within the same container
    //     var quantityInput = $(this).siblings('.quantity-input');

    //     //alert(quantityInput);

    //     if ($(this).hasClass('btn-decrease')) {
    //         // Decrease button clicked
    //         if (parseInt(quantityInput.val()) > 1) {
    //             quantityInput.val(parseInt(quantityInput.val()) - 1);
    //         }
    //     } else if ($(this).hasClass('btn-increase')) {
    //         // Increase button clicked
    //         quantityInput.val(parseInt(quantityInput.val()) + 1);
    //     }  
    // });

    $('.quantityInput').each(function() {
        const quantityInput = $(this);
        const decreaseButton = quantityInput.prev('.btn-decrease');
        const increaseButton = quantityInput.next('.btn-increase');
        const addToCartLink = $('.add_to_cart[data-product_id="' + quantityInput.attr('productid') + '"]');

        quantityInput.on('change', function(e) {
          e.preventDefault();   
          updateAddToCartLinkQuantity();
        });
        
        decreaseButton.click(function() {
          quantityInput.val(Math.max(quantityInput.val() - 1, 1));
          updateAddToCartLinkQuantity();
        });

        increaseButton.click(function() {
          quantityInput.val(parseInt(quantityInput.val()) + 1);
          updateAddToCartLinkQuantity();
        });

        function updateAddToCartLinkQuantity() {
          const quantity = quantityInput.val();
          const addToCartLinkUrl = addToCartLink.attr('href');
          const newAddToCartLinkUrl = addToCartLinkUrl.replace(/quantity=\d+/, `quantity=${quantity}`);

          addToCartLink.attr('href', newAddToCartLinkUrl);
        }
    });

    const deleteButtons = document.querySelectorAll('.remove_from_wishlist');
    // Loop through each delete button and attach a click event listener
    deleteButtons.forEach(deleteButton => {
        deleteButton.addEventListener('click', function(event) {
          //event.preventDefault(); // Prevent the default action (e.g., following the link)

          // Find the parent <tr> element of the clicked delete button
          const parentTr = this.closest('tr.yith-wcwl-row-item');

          // Add the "deleted-item" class to the parent <tr> element
          parentTr.classList.add('deleted-item');
          // Add the "fade-out" class to initiate the fade-out animation
          parentTr.classList.add('fade-out');

          // After a delay (0.5 seconds in this case), remove the element from the DOM
          setTimeout(() => {
            parentTr.remove();
          }, 500);
        });
    });

    // Get the elements
    const wishlistFooter = document.querySelector('.wishlist-page.list-long .yith_wcwl_wishlist_footer');
    const sectionHeader = document.querySelector('.wishlist-page.list-long .section-header');

    if (wishlistFooter && sectionHeader) {
        // Get the scroll position
        const scrollPosition = window.scrollY;

        // Get the offset position of the wishlist footer
        const wishlistFooterOffset = wishlistFooter.offsetTop;

        // Set a function to fix the wishlist footer
        function fixWishlistFooter() {
          wishlistFooter.classList.add('fixed');
        }

        // Set a function to remove the fixed wishlist footer
        function removeFixedWishlistFooter() {
          wishlistFooter.classList.remove('fixed');
        }

        // Add an event listener to the window to call the fix and remove fixed wishlist footer functions when the user scrolls
        window.addEventListener('scroll', function() {
          // Get the new scroll position
          const newScrollPosition = window.scrollY;
          // If the user has scrolled past the section header and the wishlist footer is not already fixed, fix it
          if (newScrollPosition > sectionHeader.offsetTop && !wishlistFooter.classList.contains('fixed')) {
            fixWishlistFooter();
          }
          // If the user has scrolled back above the section header and the wishlist footer is fixed, remove it
          if (newScrollPosition < sectionHeader.offsetTop && wishlistFooter.classList.contains('fixed')) {
            removeFixedWishlistFooter();
          }
          // If the user has scrolled past the original position of the wishlist footer and the wishlist footer is still fixed, remove the fixed class
          if (newScrollPosition > (wishlistFooterOffset - window.innerHeight) && wishlistFooter.classList.contains('fixed')) {
            removeFixedWishlistFooter();
          }
        });
    }    

    setTimeout(function() {
        $('.btn-favorite .like-count').removeClass("d-none");
    }, 3000);    

    $('.delete-link').on('click', function (e) {
        e.preventDefault(); // Prevent the default link behavior
        var removeUrl = $(this).attr('href');

        // Show the modal
        $('#deleteModal').addClass("show").css('display', 'block');

        $('.confirm-link').attr('href', removeUrl); 

        // Handle the confirmation
        // $('.confirm-link').on('click', function () {
        //     // Get the removal URL from the data-removal-url attribute
        //     var customRemoveUrl = $(this).data('removal-url');

        //     // Perform an AJAX request to remove the item from the cart
        //     $.ajax({
        //         url: customRemoveUrl, // Use the custom removal URL
        //         type: 'GET',
        //         success: function (response) {
        //             // Reload the cart page to reflect the updated cart contents
        //             window.location.reload();
        //         }
        //     });
        // });

        // Handle cancel
        $('#cancelDelete').on('click', function (e) {
            e.preventDefault(); 
            // If the user clicks "Cancel," hide the modal
            $('#deleteModal').removeClass("show").css('display', 'none');
        });
    });

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    $('body').on('click', '.btn-compare', function() {
        let btn = $(this);
        let id = btn.attr('data-id');
        
        if (id && !btn.hasClass('loading-custom')) {
            btn.addClass('loading-custom');

            $.ajax({
                url: woocommerce_params.ajax_url,
                type: "GET",
                data: {
                    action: "get_modal_product_compare",
                    id
                },
                success: function(response) {
                    btn.removeClass('loading-custom');

                    if (response.success) {
                        $('.modal-compare-product .list-product .item[data-number="1"] .product').html(response.data.content);
                        $('.modal-compare-product .list-product .item[data-number="1"] .product .input-compare').prop('checked', true);
                        $('.modal-compare-product .list-product .item[data-number="1"]').addClass('active');
                        $('.modal-compare-product .list-product .item .btn-favorite div').html(`<div class="yith-wcwl-add-button"><a href="?add_to_wishlist=${id}&amp;_wpnonce=95802b9764" class="add_to_wishlist single_add_to_wishlist button alt tooltip-added" data-product-id="${id}" data-product-type="simple" data-original-product-id="${id}" data-title="Thêm yêu thích" rel="nofollow"><i class="yith-wcwl-icon fa fa-heart-o"></i><span class="btn-text">Thêm yêu thích</span></a></div>`);
                        $('.modal-compare-product').modal('show');
                    }
                    else {
                        alert('Đã có lỗi xảy ra');
                    }
                }
            });
        }
    });

    let timeoutSearch = null;

    $('body').on('input', '.modal-compare-product .form-search [name="keyword"]', function() {
        clearTimeout(timeoutSearch);

        timeoutSearch = setTimeout(() => {
            let keyword = $(this).val();
            let product_type = $('.modal-compare-product .form-search [name="product_type"]').val();
            let current_product = $('.btn-compare').attr('data-id');
            
            if (keyword) {
                $('.modal-compare-product .result-search').html('<div class="loaderbox s"><div id="loader"></div><img src="/assets/images/icons/icon-Logo.png" class="img-fluid w-80" title="Vua Hoàn Thiện"></div>');
                $('.modal-compare-product .result-search').addClass('active');

                let scrollableDiv = $('.modal-compare-product');
                if ($(window).width() <= 576) scrollableDiv = $('.modal-compare-product .modal-body');
                scrollableDiv.animate({ scrollTop: scrollableDiv.prop("scrollHeight")}, 500);

                $.ajax({
                    url: woocommerce_params.ajax_url,
                    type: "GET",
                    data: {
                        action: "search_product_compare",
                        keyword,
                        product_type,
                        current_product
                    },
                    success: function(response) {
                        if (response.success) {
                            $('.modal-compare-product .result-search').html(response.data.content);
                        }
                        else {
                            alert('Đã có lỗi xảy ra');
                        }
                    }
                });
            }
            else {
                $('.modal-compare-product .result-search').removeClass('active');
            }
        }, 1000);
    });

    $('body').on('click', '.modal-compare-product .result-search .item', function() {
        let item = $('.modal-compare-product .result-search .item');
        let btn = $(this);
        let id = btn.attr('data-id');
        let element = $('.modal-compare-product .list-product .item:not(.active)').first();

        if ($('.modal-compare-product .input-compare:checked').length > 0) {
            $('.modal-compare-product .input-compare:checked').each(function() {
                if ($(this).val() == id) return false;
            });
        }

        if (id && element.length && !item.hasClass('loading-custom') && !btn.hasClass('active')) {
            item.addClass('loading-custom');

            $.ajax({
                url: woocommerce_params.ajax_url,
                type: "GET",
                data: {
                    action: "get_modal_product_compare",
                    id
                },
                success: function(response) {
                    item.removeClass('loading-custom');

                    if (response.success) {
                        element.find('.product').html(response.data.content);
                        element.find('.product .input-compare').prop('checked', true);
                        element.find('.product .btn-favorite div').html(`<div class="yith-wcwl-add-button"><a href="?add_to_wishlist=${id}&amp;_wpnonce=95802b9764" class="add_to_wishlist single_add_to_wishlist button alt tooltip-added" data-product-id="${id}" data-product-type="simple" data-original-product-id="${id}" data-title="Thêm yêu thích" rel="nofollow"><i class="yith-wcwl-icon fa fa-heart-o"></i><span class="btn-text">Thêm yêu thích</span></a></div>`);
                        element.addClass('active');
                        btn.addClass('active');
                    }
                    else {
                        alert('Đã có lỗi xảy ra');
                    }
                }
            });
        }
    });

    $('body').on('click', '.modal-compare-product .remove-product', function() {
        let parent = $(this).parent();
        let product = parent.find('.product .input-compare').val();

        parent.find('.product').html('<div class="empty"><div class="add-item">+</div></div>');
        parent.removeClass('active');
        $(`.modal-compare-product .result-search .item[data-id="${product}"]`).removeClass('active');
    });

    $('body').on('click', '.modal-compare-product .remove-all', function() {
        let element = $('.modal-compare-product .list-product .item:not([data-number="1"])');

        element.removeClass('active');
        element.find('.product').html('<div class="empty"><div class="add-item">+</div></div>');
        $('.modal-compare-product .result-search .item').removeClass('active');
    });

    $('body').on('click', '.modal-compare-product .btn-compare-product', function() {
        let ids = [];

        $('.modal-compare-product .list-product .product .input-compare:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length <= 1) {
            alert('Vui lòng chọn thêm sản phẩm');
            return false;
        }

        let url = window.location.origin;
        url += '/so-sanh/?ids='+ids.join('-');
        window.open(url);
    });

    $('body').on('click', '.modal-compare-product .list-product .item a.product-image', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        window.open(url);
    });

    $('body').on('click', '#btn-save-gift', function(e) {
        let btn = $(this);
        let gift = $('input[name="gift[]"]:checked');

        let order_gift = null;

        if (gift.length) {
            order_gift = gift.map(function() {
                return $(this).val();
            }).get()
        }

        btn.addClass('loading-custom');

        $.ajax({
            url: woocommerce_params.ajax_url,
            type: "GET",
            data: {
                action: "save_gift_selection",
                order_gift
            },
            success: function(response) {
                btn.removeClass('loading-custom');
                if (response.success) {
                    let content = '';

                    for (let key in order_gift) {
                        content += `<p class="fw-bold">${order_gift[key]}</p>`;
                    }
                }
            }
        });
    });

    $('body').on('click', '.remove-gift', function(e) {
        let element = $(this);
        let gift = element.attr('data-gift');

        if (gift) {   
            element.parent().addClass('loading-custom');

            $.ajax({
                url: woocommerce_params.ajax_url,
                type: "GET",
                data: {
                    action: "remove_gift_from_cart",
                    "gift": [gift]
                },
                success: function(response) {
                    element.parent().removeClass('loading-custom');

                    if (response.success) {
                        element.parent().remove();
                        $(`[name="gift[]"][value="${gift}"]`).prop('checked', false);
                    }
                    else {
                        alert('Đã có lỗi xảy ra');
                    }
                }
            });
        }
    });

    $('body').on('click', '[name="coupon_selected"]', function() {
        let coupon = $(this).val();

        if ($(this).is(':checked')) {
            $.ajax({
                url: woocommerce_params.ajax_url,
                type: "POST",
                data: {
                    action: "replace_coupon",
                    coupon
                },
                success: function(response) {
                    if (response.success) location.reload();
                }
            });
        }
        else {
            window.location.href="/cart/?remove_coupon="+coupon;
        }
    });

    $('body').on('click', '.list-cart-gift-available .list-my-gifts .gift-item:visible input[type="checkbox"]', function() {
        $('#btn-save-gift').click();
    });

    autoEarnGift();
    hideShowListGift();
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

function hideShowListGift() {
    $('.list-cart-gift-available .list-coupon-available').each(function() {
        if ($(this).find('.gift-item:not(.d-none)').length == 0) {
            $(this).addClass('d-none');
            $('.list-cart-gift-available .title-coupon').addClass('d-none');
        }
        else {
            $(this).removeClass('d-none');
            $('.list-cart-gift-available .title-coupon').removeClass('d-none');
        }
    });

    $('.list-cart-gift-available .list-industries .list-industry').each(function() {
        if ($(this).find('.gift-item:not(.d-none)').length == 0) $(this).addClass('d-none');
        else $(this).removeClass('d-none');
    });

    $('.list-cart-gift-unavailable .list-coupon').each(function() {
        if ($(this).find('.gift-item:not(.d-none)').length == 0) {
            $(this).addClass('d-none');
            $('.list-cart-gift-unavailable .title-coupon').addClass('d-none');
        }
        else {
            $(this).removeClass('d-none');
            $('.list-cart-gift-unavailable .title-coupon').removeClass('d-none');
        }
    });

    $('.list-cart-gift-unavailable .list-industries .list-industry').each(function() {
        if ($(this).find('.gift-item:not(.d-none)').length == 0) $(this).addClass('d-none');
        else $(this).removeClass('d-none');
    });

    if ($('.list-cart-gift-available .list-industries .list-industry:not(.d-none)').length == 0) $('.list-cart-gift-available .title-voucher').addClass('d-none');
    else $('.list-cart-gift-available .title-voucher').removeClass('d-none');

    if ($('.list-cart-gift-unavailable .list-industries .list-industry:not(.d-none)').length == 0) $('.list-cart-gift-unavailable .title-voucher').addClass('d-none');
    else $('.list-cart-gift-unavailable .title-voucher').removeClass('d-none');

    if ($('.list-cart-gift-available .list-coupon-available .gift-item:not(.d-none)').length == 0 && $('.list-cart-gift-available .list-voucher-available .gift-item:not(.d-none)').length == 0) {
        $('.list-cart-gift-available').addClass('d-none');
        $('.title-gift').addClass('d-none');
    }
    else {
        $('.list-cart-gift-available').removeClass('d-none');
        $('.title-gift').removeClass('d-none');
    }

    if ($('.list-cart-gift-unavailable .list-coupon .gift-item:not(.d-none)').length == 0 && $('.list-cart-gift-unavailable .list-voucher .gift-item:not(.d-none)').length == 0) {
        $('.list-cart-gift-unavailable').addClass('d-none');
        $('.title-gift-unavailable').addClass('d-none');
    }
    else {
        $('.list-cart-gift-unavailable').removeClass('d-none');
        $('.title-gift-unavailable').removeClass('d-none');
    }
}

function autoEarnGift() {
    if ($('.list-cart-gift-available .list-my-gifts .gift-item:visible input[type="checkbox"]:checked').length == 0) {
        if ($('.list-cart-gift-available .list-my-gifts .gift-item:visible input[type="checkbox"]').length) {
            $('.list-cart-gift-available .list-my-gifts .gift-item:visible input[type="checkbox"]').prop('checked', true);
            $('#btn-save-gift').click();
        }
    }

    let hiddenChecked = false;
    let visibleUnchecked = false;

    $('.list-cart-gift-available .gift-item').each(function() {
        if ($(this).hasClass('d-none') && $(this).find('[name="gift[]"]').is(':checked')) {
            hiddenChecked = true;
            $(this).find('[name="gift[]"]').prop('checked', false);
        }

        if (!$(this).hasClass('d-none') && !$(this).find('[name="gift[]"]').is(':checked')) {
            visibleUnchecked = true;
            $(this).find('[name="gift[]"]').prop('checked', true);
        }
    });

    if (hiddenChecked && visibleUnchecked) {
        $('#btn-save-gift').click();
    }
}