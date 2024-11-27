$(window).on('resize', function(){
  // let highlightHeight = $('nav .highlight').height(),
  //   navWrapperHeight = $('nav .nav-wrapper').height(),
  //   totalHeaderHeight = highlightHeight + navWrapperHeight;

  let navheight = $('nav.navbar-main').height();
  $(':root').get(0).style.setProperty('--body-padding-top', `${navheight}px`);

  if($(window).width() > 768){
    $('.btn-seemore').on('click', function(){
      const relatedProdItem = $(this).closest('.slick-slide');
      relatedProdItem.addClass('show');
      relatedProdItem.siblings().addClass('opacity-0');
    });

    $('.related-product-item .btn-close').on('click', function() {
      $(this).closest('.slick-slide').removeClass('show')
      $('.slick-slide').removeClass('opacity-0');
    })

    const relatedProductHeight = $('.related-product .slick-track').height()

    $('.related-product .slick-track').height(relatedProductHeight);
  } else {

    $('.btn-seemore').on('click', function(){
      const relatedProdItem = $(this).closest('.related-product-item').find('.product-body');

      let relatedProductModal = /* html */ `
        <div class="related-product-modal modal fade" id="relatedProductModal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button class="btn-close" data-bs-dismiss="modal">
                </button>
              </div>

              <div class="modal-body">
                ${$(relatedProdItem).html()}
              </div>
            </div>
          </div>
        </div>
      `

      $('body').append(relatedProductModal);
      $('#relatedProductModal').modal('show');

      $('#relatedProductModal').on('hidden.bs.modal', function() {
        $(this).remove();
      })
    });
  }

  if ($(window).width() < 992) {
    const qnaSection = $('#sectionQnA').html(),
          qnaModal   = /*html*/`
            <div class="modal fade" id="qnaSectionModal">
              <div class="modal-dialog modal-fullscreen-m-down modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" data-bs-dismiss="modal" class="btn">
                      <i class="bi bi-chevron-left"></i> Back
                    </button>
                  </div>
                  <div class="modal-body bg-grey-lightest">
                    ${qnaSection}
                    <button type="button" class="btn btn-outline-primary bg-light w-100 mt-3"
                      data-bs-toggle="modal" data-bs-target="#modalAskQuestion">
                      Ask a Question
                    </button>
                  </div>
                </div>
              </div>
            </div>
          `;

    $('body').append(qnaModal);
    $('#qnaSectionModal').find('.qna-question-count').remove();

    $('#sectionQnA').on('click', function() {
      $('#qnaSectionModal').modal('show');
    })

    $('.modal-sm-down').each(function() {
      const modalId = $(this).data('modal-id'),
            modalContent = $(this).html(),
            modalTmp = /*html*/`
            <div class="modal fade" id="${modalId}">
              <div class="modal-dialog modal-fullscreen-m-down modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" data-bs-dismiss="modal" class="btn">
                      <i class="bi bi-chevron-left"></i> Back
                    </button>
                  </div>
                  <div class="modal-body">
                    ${modalContent}
                  </div>
                </div>
              </div>
            </div>
          `;

      $('body').append(modalTmp);
      $(`#${modalId}`).find('.block-fade-sm-down').removeClass('block-fade-sm-down');
      $(this).on('click', function() {
        $(`#${modalId}`).modal('show');
      })
    })
  }
})

$(window).trigger('resize');
