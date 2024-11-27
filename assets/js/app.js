$(document).ready(() => {



  $('.view-all, .view-less').click((e) => {

    let parent = e.target.parentNode;

    if (parent.tagName.toLowerCase() === 'li') {

      parent = parent.parentNode;

    }

    let children = parent.children

    for (let i = 0; i < children.length; i++) {

      if (i > 4) children[i].classList.toggle('d-none');

    }

  });



  let popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));

  popoverTriggerList.map(function (popoverTriggerEl) {

    const popoverId = popoverTriggerEl.attributes['data-content-id'];

    const placement = popoverTriggerEl.attributes['data-bs-placement'];

    if (popoverId) {

      return new bootstrap.Popover(popoverTriggerEl, {

          sanitize: false,

          content: function () {

            return $(`#${popoverId.value}`).html();

          },

          placement: placement.value,

          html: true,

      });

    }else{//do something else cause data-content-id isn't there.

    }

  });

  $('body').on('click', function (e) {

    $('[data-bs-toggle=popover]').each(function () {

        // hide any open popovers when the anywhere else in the body is clicked

        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {

            $(this).popover('hide');

        }

    });

  });

  $('.btn-pack-size').hover(function() {

    let target = $(this);

    let packSize = target.data('pack-size');

    $('.pack-size').html(packSize);

  });

  $('.delivery-item').click(function() {

    let target = $(this);

    let children = $('.delivery-item-content').children();

    target.parent().parent().find('.delivery-item').removeClass('selected');

    target.addClass('selected');

    children.each(function(index) {

      children[index].classList.toggle("d-none");

    });

  });

  // $(window).on('scroll', function() {

  //   if ($(window).width() < 700 || !$('.product-detail').length) return;



  //   let productOverview = $('#productOverview');

  //   let productNav = $('#productNav');

  //   let productSpecs = $('#productSpecs');

  //   let customerReviews = $('#customerReviews');

  //   let sectionQnA = $('#sectionQnA');

  //   let productSummaryTop = $('#productSummary').offset().top;

  //   let productSummaryHeight = $('#productSummary').height();

  //   let prev;

  //   if (window.pageYOffset >= productSummaryTop + (productSummaryHeight / 2) && productNav.hasClass('d-none')) {

  //     productNav.removeClass('d-none');

  //   }

  //   prev = productOverview.prev().length < 1 ? productOverview.parent().prev() : productOverview.prev();

  //   if (window.pageYOffset >= prev.offset().top + (prev.height() / 2)) {

  //     $('[data-target="#productOverview"]').parent().parent().find('.active').removeClass('active')

  //     $('[data-target="#productOverview"]').addClass('active');

  //   }

  //   prev = productSpecs.prev().length < 1 ? productSpecs.parent().prev() : productSpecs.prev();

  //   if (window.pageYOffset >= prev.offset().top + (prev.height() / 2)) {

  //     $('[data-target="#productSpecs"]').parent().parent().find('.active').removeClass('active')

  //     $('[data-target="#productSpecs"]').addClass('active');

  //   }

  //   prev = customerReviews.prev().length < 1 ? customerReviews.parent().prev() : customerReviews.prev();

  //   if (window.pageYOffset >= prev.offset().top + (prev.height() / 2)) {

  //     $('[data-target="#customerReviews"]').parent().parent().find('.active').removeClass('active')

  //     $('[data-target="#customerReviews"]').addClass('active');

  //   }

  //   prev = sectionQnA.prev().length < 1 ? sectionQnA.parent().prev() : sectionQnA.prev();

  //   if (window.pageYOffset >= prev.offset().top + (prev.height() / 2)) {

  //     $('[data-target="#sectionQnA"]').parent().parent().find('.active').removeClass('active')

  //     $('[data-target="#sectionQnA"]').addClass('active');

  //   }

  //   if (window.pageYOffset <= productSummaryTop && !productNav.hasClass('d-none')) {

  //     productNav.addClass('d-none');

  //     $('[data-target="productOverview"]').parent().parent().find('.active').removeClass('active')

  //   }

  // });



  $('.quantity-input-group').each(function(){

    var g = $(this);



    $('.btn-increase', g).on('click', function() {

      let value = $('.quantity', g).val();

      $('.quantity', g).val(parseInt(value) + 1);

    });



    $('.btn-decrease', g).on('click', function() {

      let value = $('.quantity', g).val();

      if (value > 1) {

        $('.quantity', g).val(parseInt(value) - 1);

      }

    });

  });



  $('#promoCodeInput').on('keyup', function() {

    let target = $(this);

    if (target.val().length > 0) {

      $('.btn-apply-promo').removeAttr('disabled');

    } else {

      $('.btn-apply-promo').attr('disabled', 'disabled');

    }

  });

  let target = $('.checkout-card-offers a[data-bs-toggle="tooltip"]');

  target.each(function() {

    let id = $(this).data('id')

    let element = document.getElementById(`${id}Tooltip`);

    let desHtml = $(`#${id}Description`);



    let options = {

      html: true,

      placement: 'bottom',

      customClass: 'bank-offer-des',

      title: desHtml.html()

    }

    new bootstrap.Tooltip(element, options)

  });



  $(':radio[name="methodAtm"]').change(function() {

    let target = $(this);

    $('.checkout-atm-bank').find('.active').removeClass('active');

    target.parent().addClass('active');

  });

  $(':radio[name="paymentMethod"]').change(function() {

    let value = $(this).val();

    value === 'methodAtm' ? $('.checkout-atm-bank').removeClass('d-none') : $('.checkout-atm-bank').addClass('d-none');

  });

  $('.form-add-credit-card input[type="text"]').focus(function() {

    let nameAttrValue = $(this).attr('name');

    if (nameAttrValue === 'cvc') {

      $('.credit-card').addClass('rotate-y-180');

      $('.credit-card-front').css('z-index', 0);

    } else {

      $('.credit-card').removeClass('rotate-y-180');

      $('.credit-card-front').css('z-index', 10);

      $('.credit-card').find('.active').removeClass('active');

      $(`#${nameAttrValue}`).addClass('active');

    }

  });



  let countCheckItem = () => {

    let numberOfItem = $('.cart-item').find('input:checked').length

    $('#numberOfItem').html(`(${numberOfItem})`);

    $('#checkAll').prop('checked', numberOfItem === $('.cart-item').length);

  }

  $('#checkAll').change(function() {

    let value = $(this).prop('checked');

    $('.cart-item').find('.form-check-input').prop('checked', value);

    countCheckItem();

  });

  $('.cart-item .form-check-input').change(countCheckItem);



  let listCompare = [];

  let createHtmlThumbnail = (item) => {

    return `<div class="position-relative mx-2 border h-100-px">

              <img class="h-100" src="${item.img}" />

              <button class="btn btn-light btn-sm btn-rm-compare position-absolute top-0 start-100 translate-middle border rounded-circle text-primary" data-id="${item.id}"><i class="bi bi-x"></i></button>

            </div>`

  }

  let generateListCompare = () => {

    let elements = [];

    for (let i = 0; i < listCompare.length; i++) {

      elements.push(createHtmlThumbnail(listCompare[i]));

    }

    $('#compareList').html(elements.join(''));

    $('#compareList .btn-rm-compare').click(function() {

      let target = $(this);

      let id = target.data('id');

      listCompare = listCompare.filter(item => item.id !== id);

      if (listCompare.length < 4) {

        $('.checkbox-compare .form-check-input').removeAttr('disabled');

        $(`.checkbox-compare input[data-id="${id}"]`).prop('checked', false);

      }

      listCompare.length === 0 ? $('#compareFooter').addClass('d-none') : generateListCompare();

    });

  }

  $('.checkbox-compare .form-check-input').change(function() {

    let target = $(this);

    let id = target.data('id');

    let img = target.data('img');

    if (!target.prop('checked')) {

      listCompare = listCompare.filter(item => item.id !== id);

    }

    if (target.prop('checked')) {

      listCompare.push({id: id, img: img});

    }



    if (listCompare.length === 4) {

      $('.checkbox-compare .form-check-input').attr('disabled', 'disabled');

    }

    listCompare.length === 0 ? $('#compareFooter').addClass('d-none') : $('#compareFooter').removeClass('d-none');

    generateListCompare();

  });



  $('.spec-contents').scroll(function() {

    $('.spec-contents').scrollLeft($(this).scrollLeft());

  });

});



$(function() {

  function getCounterData(obj) {

    var hours = parseInt($('.e-m-hours', obj).text());

    var minutes = parseInt($('.e-m-minutes', obj).text());

    var seconds = parseInt($('.e-m-seconds', obj).text());

    return seconds + (minutes * 60) + (hours * 3600);

  }



  function setCounterData(s, obj) {

    var hours = Math.floor((s % (60 * 60 * 24)) / (3600));

    var minutes = Math.floor((s % (60 * 60)) / 60);

    var seconds = Math.floor(s % 60);





    $('.e-m-hours', obj).html(hours);

    $('.e-m-minutes', obj).html(minutes);

    $('.e-m-seconds', obj).html(seconds);

  }



  var count = getCounterData($(".counter"));



  var timer = setInterval(function() {

    count--;

    if (count == 0) {

      clearInterval(timer);

      return;

    }

    setCounterData(count, $(".counter"));

  }, 1000);

});



var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))

var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {

  return new bootstrap.Tooltip(tooltipTriggerEl)

})



$('.btn-filter').on('click', function() {

  const input = $(this).find('input[type=checkbox]')

        filterTitle = input.attr('title'),

        filterValue = input.val(),

        filterWrap = $(this).data('target'),

        filterBadge = /*html*/`

          <span class="badge badge-filter"

            data-value="${filterValue}">

            ${filterTitle}

          </span>`;



  if ($(input).is(':checked')) {

    $(filterWrap).append(filterBadge)

  } else {

    $(filterWrap).find(`span[data-value="${filterValue}"]`).remove()

  }

})



$('#filterReview').on('click', '.badge-filter', function() {

  const value = $(this).data('value'),

        btnFilter = $(`.btn-filter input[value=${value}]`);



  $(this).remove();

  $(btnFilter).prop('checked', false);

})

var isMobile = false;
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
    isMobile  = true;
}  

if(isMobile == false) {
  const detailMoreHeight = $('#productSpecs').innerHeight() -
                          (parseInt($('#productOverview .section-header').css('margin-top')) +
                          parseInt($('#productOverview .section-header').css('margin-bottom')) +
                          $('#productOverview .section-header').outerHeight());

  $('#detailMore').css('height', `${detailMoreHeight}px`);
  $('#detailMore').on('hidden.bs.collapse', function() {
    $(this).height(detailMoreHeight);
  });
}

$('.btn-scroll-top').click(() => {

  window.scrollTo(0, 0)

});

