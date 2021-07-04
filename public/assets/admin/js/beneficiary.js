$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var beneficiaryTable = $('#beneficiary-table').DataTable({
        pageLength: 5,
        lengthChange: false
    });
    $('select[name="bank_name"]').on('change', function() {
        if ($(this).find('option:selected').val()) {
            $('input[name="ifsc_code"]').val($(this).find('option:selected').data('ifsc_code'));
        }
    });
    $('.add-beneficiary-form').parsley().on('form:submit', function() {
        $('.beneficiary-btn').text('Saving...');
        var form = $('.add-beneficiary-form');
        $.post(form.attr('action'), {
            _method: $('input[name="_method"]').val(),
            ifsc_code: $('input[name="ifsc_code"]').val(),
            account_number: $('input[name="account_number"]').val(),
            beneficiary_name: $('input[name="beneficiary_name"]').val(),
            bank_id: $('select[name="bank_name"]').find('option:selected').val(),
            bank_name: $('select[name="bank_name"]').find('option:selected').val()
        }, function(res) {
            $('.beneficiary-btn').text('Add Beneficiary');
            if (res.status == 'success') {
                toastr.success(res.message, 'Success');
            } else {
                toastr.error(res.message, 'Error');
            }
        });
        return false;
    });
});