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
    // Input filtering for #num
    $('#num').on('input', function () {
        const filtered = onlyNumber($(this).val());
        if (filtered !== $(this).val()) {
            $(this).val(filtered);
        }
        updateHuobi(); // update hotcoin when quantity changes
    });

    // Update #huobi and #mini_money when #standardSelect changes
    $('#standardSelect').on('change', function () {
        updateHuobi();
    });

    // Function to update the hotcoin needed field and hidden mini_money
    function updateHuobi() {
        let emoney = parseFloat($('#standardSelect option:selected').attr('emoney')) || 0;
        let quantity = parseInt($('#num').val()) || 0;

        let total = emoney * quantity;
        $('#huobi').val(RetainedDecimalPlaces(total, 2));
        $('#mini_money').val(emoney);

        // Optional: validate if user balance is enough (assuming you have user balance in JS)
        // let userBalance = parseFloat(...);
        // if (total > userBalance) {
        //     alert('余额不足');
        // }
    }

    // jQuery validation setup
    $('#form').validate({
        rules: {
            mini_money: { required: true, number: true },
            number: { required: true, digits: true }  // fixed here to match input name
        },
        messages: {
            mini_money: { required: '请输入金额', number: '请输入正确金额' },
            number: { required: '请输入数量', digits: '请输入正确数量' }
        },
        submitHandler: function (form) {
            $.ajax({
                url: $(form).attr('action'),
                type: $(form).attr('method'),
                data: $(form).serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.code === 0) {
                        alert(res.msg);
                        window.location.href = $(form).data('list-url');
                    } else {
                        alert(res.msg || '请求失败');
                    }
                },
                error: function () {
                    alert('服务器错误');
                }
            });
            return false;
        }
    });

    // Optional: If you have a batch_generate button, enable it here
    // $('#batch_generate').on('click', function () {
    //     $('#form').submit();
    // });
});

window.onlyNumber = onlyNumber;
