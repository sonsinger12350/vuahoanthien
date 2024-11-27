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

  $('.btn-increase').on('click', function() {
    let value = $('.quantity').val();
    $('.quantity').val(parseInt(value) + 1);
  });

  $('.btn-decrease').on('click', function() {
    let value = $('.quantity').val();
    if (value > 1) {
      $('.quantity').val(parseInt(value) - 1);
    }
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

  $('.multiple-scroll').scroll(function() {
    $('.multiple-scroll').scrollLeft($(this).scrollLeft());
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

const detailMoreHeight = $('#productSpecs').innerHeight() -
                        (parseInt($('#productOverview .section-header').css('margin-top')) +
                        parseInt($('#productOverview .section-header').css('margin-bottom')) +
                        $('#productOverview .section-header').outerHeight());

$('#detailMore').css('height', `${detailMoreHeight}px`);
$('#detailMore').on('hidden.bs.collapse', function() {
  $(this).height(detailMoreHeight);
});

$('.btn-scroll-top').click(() => {
  window.scrollTo(0, 0)
});
