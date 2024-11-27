// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation'),
      isAllValid = false;

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()

          $(form).find('input').each(function () {
            // if(!$(this).val()) {
            //   this.setCustomValidity('Vui lòng điền thông tin.')
            // }
            $(this).siblings('.invalid-feedback').remove();
            $(this).after(`<div class="invalid-feedback">${this.validationMessage}</div>`)
          })
        }

        form.classList.add('was-validated')
      }, false)

      if (form.hasAttribute('disablednext')) {
        $(form).find('input,select,textarea').on('input blur', function() {
          isAllValid = form.checkValidity()
          $(form).find('[type="submit"]')[isAllValid ? 'removeClass' : 'addClass']('disabled');
        })
      }

      $('input[required], select[required], textarea[required]').on('invalid', function() {
        if (this.validity.valueMissing) {
          this.setCustomValidity('Vui lòng điền đầy đủ thông tin.')
        } else if (this.validity.typeMismatch) {
          this.setCustomValidity('Thông tin chưa chính xác, vui lòng kiểm tra lại')
        } else if (this.validity.patternMismatch) {
          this.setCustomValidity('Thông tin chưa chính xác, vui lòng kiểm tra lại')
        } else if (this.validity.maxValue) {
          this.setCustomValidity('Giá trị phải nhỏ hơn hay bằng %s.')
        } else if (this.validity.minValue) {
          this.setCustomValidity('Giá trị phải lớn hơn hay bằng %s.')
        } else if (this.validity.maxLength) {
          this.setCustomValidity('Số điện thoại phải bằng 10 số.')
        } else if (this.validity.minLength) {
          this.setCustomValidity('Số điện thoại phải bằng 10 số.')
        } else if (this.validity.tooLong) {
          this.setCustomValidity('Thông tin quá dài')
        } else if (this.validity.tooShort) {
          this.setCustomValidity('Mật khẩu cần tối thiểu 6 ký tự')
        } else {
          this.setCustomValidity('');
        }
      })
      
      // $(form).find('input,select,textarea').on('input blur', function() {
      //   if (!this.checkValidity()) {
      //     $(this).siblings('.invalid-feedback').remove();
      //     $(this).after(`<div class="invalid-feedback">${this.validationMessage}</div>`)
      //   }
      // })
    })

  // On blur validation listener for form elements
  $(forms).find('input,select,textarea').on('input blur', function () {
    // check element validity and change class
    // $(this).removeClass('is-valid is-invalid')
           // .addClass(this.checkValidity() ? 'is-valid' : 'is-invalid');

    // $(this).find('.invalid-feedback').html(this.validationMessage);
  });

  let confirmPassword = document.getElementById('confirmPassword');
  let newPassword = document.getElementById('newPassword');
  if (confirmPassword) {
    confirmPassword.addEventListener('input', function() {
      if (confirmPassword.value === newPassword.value) {
        confirmPassword.setCustomValidity('');
        $(forms).find('[type="submit"]').focus();
      } else {
        confirmPassword.setCustomValidity(true);
      }
    });
  }
})()

$('[show-password]').on('click', function(event) {
  event.preventDefault();
  const input = $(this).siblings('input');

  if($(input).attr("type") == "text") {
    $(input).attr('type', 'password');
    $(this).find('.bi').removeClass("bi-eye-slash");
    $(this).find('.bi').addClass("bi-eye");
  }else if($(input).attr("type") == "password"){
    $(input).attr('type', 'text');
    $(this).find('.bi').addClass("bi-eye-slash");
    $(this).find('.bi').removeClass("bi-eye");
  }
})

$('#passwordInput').focus(function() {
  $('#passwordHint').collapse('show');
})

$('#passwordInput').focusout(function() {
  if (this.checkValidity()) {
    $('#passwordHint').collapse('hide');
    $('#passwordHint .temp-hint').hide();
  }
})

$('input[type="password"]').on('keyup', function() {
  $('#passwordHint .temp-hint').hide();

  const minlength        = $(this).attr("minlength"),
        uppercaseRegex   =  /(?=.*[A-Z])/,
        lowercaseRegex   = /(?=.*[a-z])/,
        numberRegex      = /(?=.*\d)/,
        specialCharRegex = /(?=.*[!@#$%^&*])/,
        value            = $(this).val();

  $('#passwordHint .validate').each(function() {
    const validateType = $(this).data('validate');

    if (validateType === 'min-length') {
      $(this)[value.length >= minlength     ? 'addClass' : 'removeClass']('valid');
      $(this)[value.length  < minlength     ? 'addClass' : 'removeClass']('invalid');
    }
    if (validateType === 'number') {
      $(this)[ numberRegex.test(value)      ? 'addClass' : 'removeClass']('valid');
      $(this)[!numberRegex.test(value)      ? 'addClass' : 'removeClass']('invalid');
    }
    if (validateType === 'lowercase') {
      $(this)[ lowercaseRegex.test(value)   ? 'addClass' : 'removeClass']('valid');
      $(this)[!lowercaseRegex.test(value)   ? 'addClass' : 'removeClass']('invalid');
    }
    if (validateType === 'uppercase') {
      $(this)[ uppercaseRegex.test(value)   ? 'addClass' : 'removeClass']('valid');
      $(this)[!uppercaseRegex.test(value)   ? 'addClass' : 'removeClass']('invalid');
    }
    if (validateType === 'special-chars') {
      $(this)[ specialCharRegex.test(value) ? 'addClass' : 'removeClass']('valid');
      $(this)[!specialCharRegex.test(value) ? 'addClass' : 'removeClass']('invalid');
    }
  })

  let initialStrength  = 0,
      addedWidth       = $('#passwordHint .progress').width() / 4,
      numberOfSuccess  = $('#passwordHint .validate.valid').length;

  if ($('.validate.valid').data('validate') !== 'min-length' && numberOfSuccess >= 3) {
    numberOfSuccess = 3;
  } else {
    numberOfSuccess = numberOfSuccess;
  }

  if (numberOfSuccess == 1) {
    $('#passwordHint .progress-bar').css('background', 'var(--color-red)');
    $('#passwordHint .strength-status').html('Yếu');
  } else if (numberOfSuccess == 2) {
    $('#passwordHint .progress-bar').css('background', 'var(--color-yellow)');
    $('#passwordHint .strength-status').html('Vừa');
  } else if (numberOfSuccess == 3) {
    $('#passwordHint .progress-bar').css('background', 'var(--color-primary)');
    $('#passwordHint .strength-status').html('Mạnh');
  } else if (numberOfSuccess >= 4) {
    $('#passwordHint .progress-bar').css('background', 'var(--color-green)');
    $('#passwordHint .strength-status').html('Rất mạnh');
  } else {
    $('#passwordHint .strength-status').html('Quá mạnh');
  }
  
  let strength = initialStrength + (numberOfSuccess * addedWidth);
  $('#passwordHint .progress-bar').css('width', `${strength}px`);

})

$('input[type="tel"]').on('input', function() {
  this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
})
// $("#confirmPassword").on("keyup", function () {
//   if (
//     $("#newPassword").val() != "" &&
//     $("#confirmPassword").val() != "" &&
//     $("#newPassword").val() == $("#confirmPassword").val()
//   ) {
//     console.log($("#newPassword").val() == $("#confirmPassword").val());
//     $("#confirmPassword").setCustomValidity('')
//   } else {
//     $("#confirmPassword").setCustomValidity('aaaaa')
//   }
//   $("#confirmPassword").reportValidity();
// });

// $('#changePassword').on('input', function() {
//   if (
//     $("#newPassword").val() != "" &&
//     $("#confirmPassword").val() != "" &&
//     $("#confirmPassword").val() == $("#newPassword").val()
//   ) {
//     confirmPassword.setCustomValidity('Mật khẩu không trùng khớp')
//   }
// })
