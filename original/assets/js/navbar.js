// let storeLocationTpl = /* html */ `
//   <h4>Location Name</h4>
//   <div class="row">
//     <div class="col">
//       <p>
//         <b class="text-green text-uppercase">open</b><br>
//         <b>Close 10pm</b><br>
//         <span class="text-green">Curbside Available</span>
//       </p>

//       <p>
//         295 Chalan Pasaheru Route 10a<br>
//         Tamuning, GU 96913<br>
//         (671)648-0440
//       </p>

//       <small>
//         <a class="text-grey" href="#">View Local Ad</a><br>
//         <a class="text-grey" href="#">View Store Details</a>
//       </small>
//     </div>
//     <div class="bg-grey-light col"></div>
//   </div>
//   <div class="popover-footer">
//     <a class="btn btn-outline-primary d-block">Find Others Stores</a>
//   </div>
// `

// var popover = new bootstrap.Popover(document.querySelector('.btn-store-location'), {
//   container: 'body',
//   content: storeLocationTpl,
//   html: true,
//   trigger: "focus",
//   placement: "bottom"
// })

$(document).ready(function() {
  $('.scroll-to').on('click', function(e) {
    e.preventDefault();
    let navheight      = $('nav.navbar-main').height(),
        target         = $(this).data('target'),
        targetPosition = $(target).offset().top;

    window.scrollTo(0, targetPosition - navheight);
  })

  const $btnMenu = $('.btn-menu'),
        $mainMenu = $("#mainMenu");

  if($(window).width() > 992) {
    // handle nav behavior on desktop screen
    $('body').on('mouseover', function(e) {
      if (!$('.nav-item').is(e.target) && $('.nav-item').has(e.target).length === 0) {
        $('.nav-item').removeClass('active')
      }
    })
    $('#mainMenu .nav-link').hover(function() {
      let target = $(this).data('bs-target'),
          parent = $(this).data('bs-parent'),
          toggle = $(this).data('bs-toggle'),
          elementTop = this.getBoundingClientRect().bottom;

      if(toggle == 'collapse') {
        $('nav').find(`.collapse.show:not(${target})`).removeClass('show');
        $(target).addClass('show');
        $('#collapseMenuWrapper').addClass('show');
        $('body').addClass('nav-open');
      } else {
        $('nav').find('.collapse.show').removeClass('show');
        $('#collapseMenuWrapper').removeClass('show');
        $('body').removeClass('nav-open');
      }

      $('#collapseMenuWrapper').css(`top`, `${elementTop}px`);
    })

    $('nav a').hover(function() {
      let target = $(this).data('bs-target'),
          toggle = $(this).data('bs-toggle'),
          parent = $(this).data('bs-parent'),
          showmItem = $(parent).find(`.collapse.show:not(${target})`);

      $(this).closest('li').siblings('li').removeClass('active');
      $(this).closest('li').addClass('active');

      if (toggle == 'collapse') {
        showmItem.removeClass('show');
        $(target).addClass('show');
      } else {
        $(this).closest('.collapse').find('.collapse.show').removeClass('show');
      }
    });

    function closeAllMenu () {
      $('#collapseMenuWrapper').removeClass('show');
      $('#collapseMenuWrapper').find('.collapse.show').removeClass('show');
      $('#collapseMenuWrapper').find('li.active').removeClass('active');
      $('body').removeClass('nav-open');
      $('#mainMenu').find('li.active').removeClass('active');
    }

    $('#collapseMenuWrapper .collapse-overlay').hover(function() {
      closeAllMenu()
    })

    $('#collapseMenuWrapper *').hover(function(e) {
      if(e.target.classList.contains('collapse-menu-wrapper') || !e.target) closeAllMenu();
    })

    $(window).on('scroll', function() {closeAllMenu()});

  } else {
    // handle nav behavior on mobile screen

    $('#collapseMenuWrapper').appendTo('#mainMenu');
    $('#mainMenu-wrapper [data-bs-toggle]').on('click', function(e) {
      e.preventDefault();

      $('#collapseMenuWrapper').addClass('show');
    })

    $('#mainMenu-wrapper .btn-back').on('click', function(e) {
      const target = $(this).data('bs-target');

      $(target).collapse('hide');
    })

    $('#collapseMenuWrapper .collapse').on('hidden.bs.collapse', function() {
      if($('#collapseMenuWrapper .show').length <= 0) {
        $('#collapseMenuWrapper').removeClass('show');
      }
    })

    $btnMenu.on('click', function(e) {
      const target = $(this).data('bs-target'),
            targetTopPosition = document.getElementById(target.replace('#', '')).getBoundingClientRect().top;
            
      $(':root').get(0).style.setProperty('--navbar-top-position', `${targetTopPosition}px`);

      if($(this).hasClass('collapsed')) {
        $('body').removeClass('nav-open');
      } else {
        $('body').addClass('nav-open');
      }

      $(`#mainMenu-wrapper .collapse:not(${target})`).collapse('hide')

      $('#collapseMenuWrapper').removeClass('show');
      $('#collapseMenuWrapper .show').removeClass('show');
    })

    $mainMenu.on('shown.bs.collapse', function() {
      $('body').on('click',function(e) {
        // if the target of the click isn't the mainMenu nor a descendant of the mainMenu
        if ($mainMenu.hasClass('show')){
          if (!$mainMenu.is(e.target) && $mainMenu.has(e.target).length === 0) {
            $('body').removeClass('nav-open');
            $('#mainMenu-wrapper .collapse').collapse('hide');
          }
        }
      });
    })
  }
})
