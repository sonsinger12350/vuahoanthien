<div class="modal fade" id="modal-deal" tabindex="-1" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content rounded">

      <div class="modal-header">

        <h5 class="modal-title">Trả giá ngay</h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

      </div>

      <div class="modal-body pt-3">

        <p class="d-none message-loading">Đang gửi giá mong muốn</p>

        <p class="d-none message-success">Đã gửi giá mong muốn</p>

        <form class="deal-form" action="<?php the_permalink();?>" method="post">

          <div class="mb-3">

            <label for="price" class="form-label">Giá mong muốn <span class="text-danger">*</span></label>

            <input type="text" class="form-control" id="price" name="price" placeholder="Giá mong muốn từ quý khách" required>

          </div>

          <div class="mb-3">

            <label for="tel" class="form-label">Điện thoại liên hệ <span class="text-danger">*</span></label>

            <input type="tel" class="form-control" id="tel" name="phone" placeholder="Điện thoại liên hệ" required>

          </div>

          <div class="mb-3 text-center">

            <button type="submit" class="btn btn-primary btn-submit rounded">Gửi trả giá</button>

          </div>

          <?php wp_nonce_field( 'dealtoken', 'detoken' );?>

          <input type="hidden" name="post_id" value="<?php the_ID();?>">

        </form>

      </div>

    </div>

  </div>

</div>