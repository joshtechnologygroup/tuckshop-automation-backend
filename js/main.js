(function ($) {
    $(".toggle-password").click(function () {
        $(this).toggleClass("zmdi-eye zmdi-eye-off");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
})(jQuery);

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#custom-image-holder').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function validateAndClone(form)
{
    if ($("#product-form #product_image1").length) {
        $("#product-form #product_image1").remove();
    }
    var $this = $('#product_image'), $clone = $this.clone();
    $this.after($clone).appendTo($('#product-form')).hide().prop('id', 'product_image1');

    $('.custom-error-message').remove();
    $('.login-alert-error').remove();
    $('.form-control').removeClass('error-field');

    var success = true;
    if ($('input[name="product[name]"]').val() == '') {
        $('input[name="product[name]"]').addClass('error-field');
        $('input[name="product[name]"]').after('<span class="custom-error-message text-danger">Please enter product name</span>');
        success = false;
    }
    var regex  = /(\d+(\.\d+)?)/;
    if ($('input[name="product[price]"]').val() == '') {
        $('input[name="product[price]"]').addClass('error-field');
        $('input[name="product[price]"]').after('<span class="custom-error-message text-danger">Please enter price</span>');
        success = false;
    } else if (!regex.test($('input[name="product[price]"]').val())) {
        $('input[name="product[price]"]').addClass('error-field');
        $('input[name="product[price]"]').after('<span class="custom-error-message text-danger">Only numeric value</span>');
        success = false;
    }
    
    if ($('input[name="product[barcode]"]').val() == '') {
        $('input[name="product[barcode]"]').addClass('error-field');
        $('input[name="product[barcode]"]').after('<span class="custom-error-message text-danger">Please enter product barcode</span>');
        success = false;
    }
    
    return success;
}

function validateImageAndUpdatePreview(ele)
{
    var ext = $('#product_image').val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
        alert('Image type not allowed!');
    } else {
        readURL(ele);
    }
}

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function validateLogin(ele)
{
    $('.custom-error-message').remove();
    $('.form-input').removeClass('error-field');
    var success = true;
    if ($('input[name="email"]').val() == '') {
        $('input[name="email"]').addClass('error-field');
        $('input[name="email"]').after('<span class="custom-error-message text-danger">Please enter your email</span>');
        success = false;
    } else if (!isEmail($('input[name="email"]').val())) {
        $('input[name="email"]').addClass('error-field');
        $('input[name="email"]').after('<span class="custom-error-message text-danger">Please enter valid email</span>');
        success = false;
    }

    if ($('input[name="password"]').val() == '') {
        $('input[name="password"]').addClass('error-field');
        $('input[name="password"]').after('<span class="custom-error-message text-danger">Please enter your password</span>');
        success = false;
    }
    
    return success;
}