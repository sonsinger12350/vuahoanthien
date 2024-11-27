$(document).ready(function() {
  function resetSlicks($slick_slider, settings) {
    $(window).on('resize', function() {
      if($(window).width() < 320) {
        if($slick_slider.hasClass('slick-initialize')){
          $slick_slider.slick('unslick');
        }
        return
      }
      if(!$slick_slider.hasClass('slick-initialize')) {
        return $slick_slider.slick(settings);
      }
    })
  }

  $('#homeCategory').slick({
    mobileFirst:true,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 5,
          slidesToScroll: 2,
        }
      },
      {
        breakpoint: 524,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 2,
        }
      },
      {
        breakpoint: 0,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  })

  $('.slide-product').slick({
    mobileFirst:true,
    responsive: [
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 6,
          slidesToScroll: 2,
          centerMode: false,
        }
      },
      {
        breakpoint: 524,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          centerMode: false,
        }
      },
      {
        breakpoint: 0,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          centerMode: true,
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  })

  $('.slide-featured-product').slick({
    mobileFirst:true,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 5,
          slidesToScroll: 1,
          centerMode: false,
        }
      },
      {
        breakpoint: 524,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          centerMode: false,
        }
      },
      {
        breakpoint: 0,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          centerMode: true,
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]

  })

  $('#slideTab').slick({
    mobileFirst:true,
    variableWidth: true,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 1,
          infinite: false
        }
      },
      {
        breakpoint: 524,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          infinite: false
        }
      },
      {
        breakpoint: 0,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          infinite: false
        }
      }
    ]
  });

  $('.related-product').slick({
    mobileFirst:true,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 2,
        }
      },
      {
        breakpoint: 524,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          centerMode: true,
        }
      },
      {
        breakpoint: 0,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          centerMode: true,
        }
      }
    ]
  });

  $('#collectionProduct').slick({
    mobileFirst:true,
    dots: true,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 4,
        }
      },
      {
        breakpoint: 524,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 4,
        }
      },
      {
        breakpoint: 0,
        settings: {
          slidesToShow: 2,
          rows: 2,
          slidesToScroll: 2,
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]

  });

  $('.slick-departments').slick({
    mobileFirst:true,
    dots: true,
    infinite: false,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 5,
          slidesToScroll: 2,
        }
      },
      {
        breakpoint: 524,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          centerMode: true,
        }
      },
      {
        breakpoint: 0,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          centerMode: true,
        }
      }
    ]
  });

  $('.slide-hotdeal').slick({
    mobileFirst:true,
    infinite: false,
    responsive: [
      {
        breakpoint: 769,
        settings: {
          slidesToShow: 5,
          slidesToScroll: 4,
        }
      },
      {
        breakpoint: 524,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        }
      },
      {
        breakpoint: 0,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          centerMode: true,
        }
      }
    ]
  });

  $('#customerImg').slick({
    mobileFirst:true,
    infinite: false,
    variableWidth: true,
    responsive: [
      {
        breakpoint: 769,
        settings: {
          slidesToShow: 6,
          slidesToScroll: 6,
        }
      },
      {
        breakpoint: 524,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 4,
        }
      },
      {
        breakpoint: 0,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        }
      }
    ]
  });

  $('#specialBuy').slick({
    mobileFirst:true,
    variableWidth: true,
    responsive: [
      {
        breakpoint: 1200,
        settings: "unslick"
      },
      {
        breakpoint: 769,
        settings: {
          slidesToShow: 6,
          slidesToScroll: 1,
        }
      },
      {
        breakpoint: 524,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 1,
        }
      },
      {
        breakpoint: 0,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          centerMode: true
        }
      }
    ]
  });

  $('.product-who-viewed').slick({
    mobileFirst:true,
    responsive: [
      {
        breakpoint: 0,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          centerMode: false,
        }
      }
    ]
  });

  $('.main-images video').hide();
  $('.slider-thumbnails .thumbnail').click(function(e) {
    e.preventDefault();
    let clicked = $(this);
    let newSelection = clicked.data('bg');
    let type = clicked.data('type');
    clicked.parent().find('.thumbnail').removeClass('selected');
    clicked.addClass('selected');
    if (type === 'video') {
      let $video = $('.main-images video');
      $video[0].src = newSelection;
      // $video.html(`<source src="${newSelection}">`)
      // $('source', $video).attr('src', newSelection);
      $('.main-images img').hide()
      $('.main-images video').empty($video.hide().fadeIn('slow'));
    } else {
      let $img = $('.main-images img').attr('src', newSelection);
      $('.main-images video').hide()
      $('.main-images img').empty().append($img.hide().fadeIn('slow'));
    }
  });

  var button = $('button[data-bs-toggle="tab"]'),
      tabSliderSettings = {
        mobileFirst:true,
        responsive: [
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1,
              infinite: false,
            }
          },
          {
            breakpoint: 524,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1,
              infinite: false,
            }
          },
          {
            breakpoint: 0,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              centerMode: true,
              infinite: false,
            }
          }
        ]
      };

  $('button[data-bs-toggle="tab"]').click(() => {
    button.removeClass('active');
  });

  $('#slideTabContent .tab-pane').each((index) => {
    $(`#tabSlideProduct${index + 1}`).slick(tabSliderSettings);
    resetSlicks($(`#tabSlideProduct${index + 1}`), tabSliderSettings);

    $(this).on('shown.bs.tab', function(e) {
      $(`#tabSlideProduct${index + 1}`).slick('setPosition');
    })
  });

  const tabSlider5LG = {
    mobileFirst:true,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 5,
          slidesToScroll: 5,
          infinite: false,
        }
      },
      {
        breakpoint: 524,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
          infinite: false,
        }
      },
      {
        breakpoint: 0,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          centerMode: true,
          infinite: false,
        }
      }
    ]
  };

  $('.slick-tab .tab-pane').each((index) => {
    $(`#collection${index}`).slick(tabSlider5LG);
    resetSlicks($(`#collection${index}`), tabSlider5LG);

    $(this).on('shown.bs.tab', function(e) {
      $(`#collection${index}`).slick('setPosition');
    })
  });

  const sliderForOptions = {
    slidesToShow: 1,
    slidesToScroll: 1,
    fade: true,
    asNavFor: '.slider-nav'
  }
  const sliderNavOptions = {
    slidesToShow: 2,
    slidesToScroll: 1,
    arrows: false,
    asNavFor: '.slider-for',
    centerMode: true,
    focusOnSelect: true
  }
  $('.slider-for').slick(sliderForOptions);
  resetSlicks($('.slider-for'), sliderForOptions)
  $('.slider-nav').slick(sliderNavOptions);
  resetSlicks($('.slider-nav'), sliderNavOptions)
  $('#mbProductImageModal').on('shown.bs.modal', function() {
    $('.slider-for').slick('setPosition');
    $('.slider-nav').slick('setPosition');
  });
});
