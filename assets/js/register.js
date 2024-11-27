$(function () { // jQuery ready
  // On blur validation listener for form elements
  $('.needs-validation').find('input,select,textarea').on('input', function () {
      // check element validity and change class
      $(this).removeClass('is-valid is-invalid')
             .addClass(this.checkValidity() ? 'is-valid' : 'is-invalid');
  });
});

$('.input-password a').on('click', function() {
const input = $(this).siblings('input');

if($(input).attr("type") == "text") {
  $(input).attr('type', 'password');
  $(this).find('.bi').addClass( "bi-eye-slash" );
  $(this).find('.bi').removeClass( "bi-eye" );
}else if($('#show_hide_password input').attr("type") == "password"){
  $(input).attr('type', 'text');
  $(this).find('.bi').removeClass( "fa-eye-slash" );
  $(this).find('.bi').addClass( "bi-eye" );
}
})
