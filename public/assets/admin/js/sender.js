$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.sender-mobile-form').parsley().on('form:submit', function() {
        $('.continue-btn').text('Processing...');
        var mobile = $('input[name="mobile"]').val();
        $.get(`${BASE_URL}/admin/money-transfer/${mobile}`, function(res) {
            if (res.status == 'success') {
                toastr.success(`Welcome ${res.fullname}`, 'Success');
                setTimeout(function() {
                    window.location.href = `${BASE_URL}/admin/money-transfer`;
                }, 2000);
            } else if (res.status == 'registration') {
                $('.registration-modal').modal('show');
                toastr.warning(res.message);
            } else if (res.status == 'verification') {
                $('.verify-otp-modal').modal('show');
                toastr.success(res.message, 'Success');
            } else {
                $('.continue-btn').text('Continue');
                toastr.error(res.message, 'Error');
            }
        });
        return false;
    });
    $(document).on('click', '.btn-close', function() {
        $('.continue-btn').text('Continue');
        $('.registration-form')[0].reset();
        $('.sender-mobile-form')[0].reset();
        $('.registration-modal').modal('hide');
    });
    $('.registration-form').parsley().on('form:submit', function() {
        $('.register-btn').text('Processing...');
        $.post(`${BASE_URL}/admin/money-transfer`, {
            mobile: $('input[name="mobile"]').val(),
            pincode: $('input[name="pincode"]').val(),
            last_name: $('input[name="last_name"]').val(),
            first_name: $('input[name="first_name"]').val(),
        }, function(res) {
            if (res.status == 'success') {
                $('.continue-btn').text('Continue');
                $('.register-btn').text('Register');
                $('.registration-form')[0].reset();
                $('.registration-modal').modal('hide');
                $('.verify-otp-modal').modal('show');
                toastr.success(res.message, 'Success');
            } else {
                $('.register-btn').text('Register');
                toastr.error(res.message, 'Error');
            }
        });
        return false;
    });
    $('.verify-otp-form').parsley().on('form:submit', function() {
        $('.verify-otp-btn').text('Processing...');
        $.post(`${BASE_URL}/admin/dmt/verifyOtp`, {
            otp: $('input[name="otp"]').val(),
            mobile: $('input[name="mobile"]').val()
        }, function(res) {
            if (res.status == 'success') {
                toastr.success(`Welcome ${res.fullname}, ${res.message}`, 'Success');
                setTimeout(function() {
                    window.location.href = `${BASE_URL}/admin/money-transfer`;
                }, 2000);
            } else {
                $('.verify-otp-btn').text('Verify');
                toastr.error(res.message, 'Error');
            }
        });
        return false;
    });
    $(document).on('click', '.resend-otp-btn', function() {
        $('.resend-otp-btn').text('Sending...');
        $.post(`${BASE_URL}/admin/dmt/resendOtp`, {
            mobile: $('input[name="mobile"]').val()
        }, function(res) {
            $('.resend-otp-btn').text('Resend OTP');
            if (res.status == 'success') {
                toastr.success(res.message, 'Success');
            } else {
                toastr.error(res.message, 'Error');
            }
        });
    });
});