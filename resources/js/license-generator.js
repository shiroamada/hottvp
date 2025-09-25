import $ from 'jquery';
import 'jquery-validation'; // npm install jquery-validation

// Allow only numbers in input string and return filtered string
function onlyNumber(str) {
    if (typeof str !== 'string') return '';
    let val = str.replace(/\D/g, '');
    if (val.length > 1 && val.charAt(0) === '0') {
        val = val.substring(1);
    }
    return val;
}

// Retain decimal places
function RetainedDecimalPlaces(val, places) {
    if (!val) return '';
    return parseFloat(val).toFixed(places);
}

$(function () {
    // Input filtering for #number
    $('#number').on('input', function () {
        const filtered = onlyNumber($(this).val());
        if (filtered !== $(this).val()) {
            $(this).val(filtered);
        }
    });

    // jQuery validation setup
    $('#form').validate({
        ignore: [],
        rules: {
            number: { required: true, digits: true, min: 1 }
        },
        messages: {
            number: {
                required: $('#form').data('msg-quantity-required') || 'Quantity is required',
                digits: $('#form').data('msg-quantity-digits') || 'Quantity must be a number',
                min: $('#form').data('msg-quantity-min') || 'Quantity must be greater than 0'
            }
        },
        errorElement: 'div',
        errorClass: 'text-danger text-xs mt-1',
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function (error, element) {
            // Put the error right after the input
            error.insertAfter(element);
        },
        submitHandler: function (form) {
            $.ajax({
                url: $(form).attr('action'),
                type: $(form).attr('method'),
                data: $(form).serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.code === 0 && res.redirect) {
                        window.location.href = $(form).data('list-url');
                    } else {
                        // Inline show server message under number field if relevant
                        const validator = $(form).validate();
                        validator.showErrors({
                            number: res.msg || 'Request failed'
                        });
                    }
                },
                error: function (jqXHR) {
                    if (jqXHR && jqXHR.status === 422) {
                        const resp = jqXHR.responseJSON || {};
                        const errors = resp.errors || {};
                        const validator = $(form).validate();

                        const errorsMap = {};
                        Object.keys(errors).forEach(function (field) {
                            if (Array.isArray(errors[field]) && errors[field].length) {
                                errorsMap[field] = errors[field][0];
                            }
                        });

                        validator.showErrors(errorsMap);
                    } else {
                        alert('Server error');
                    }
                }
            });
            return false;
        }
    });
});

window.onlyNumber = onlyNumber;
