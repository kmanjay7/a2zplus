$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var beneficiaryTable = $('#beneficiary-table').DataTable({
        pageLength: 5,
        lengthMenu: [5, 10, 20, 50, 100],
        processing: true,
        serverSide: true,
        ajax: `${BASE_URL}/admin/dmt/ben-list/${$('input[name="sender_id"]').val()}`,
        columns: [
            { data: 'beneficiary_name', name: 'beneficiary_name' },
            { data: 'account_number', name: 'account_number' }
        ],
        columnDefs: [{
            targets: 0,
            render: function render(data, type, row, meta) {
                return `<div class="text-left">
                            <p><b>${row.beneficiary_name}</b></p>
                            <p>Account No: <span>${row.account_number}</span></p>
                            <p>IFSC Code: <span>${row.ifsc_code}</span></p>
                        </div>`;
            }
        }, {
            targets: 1,
            render: function render(data, type, row, meta) {
                return `<div class="text-center">
                            <button type="button" data-id="${row.id}" data-beneId="${row.beneId}" class="btn btn-success btn-sm w-25 ms-2 verified">Verified</button>
                            <button type="button" data-id="${row.id}" data-beneId="${row.beneId}" class="btn btn-primary btn-sm w-25 ms-2 transfer">Transfer</button>
                            <button type="button" data-id="${row.id}" data-beneId="${row.beneId}" class="btn btn-danger btn-sm w-25 ms-2 delete">Delete</button>
                        </div>`;
            }
        }]
    });
    $('.add-beneficiary-form select[name="bank_id"]').on('change', function() {
        if ($(this).find('option:selected').val()) {
            $('.add-beneficiary-form input[name="bank_name"]').val($(this).find('option:selected').text());
            $('.add-beneficiary-form input[name="ifsc_code"]').val($(this).find('option:selected').data('ifsc_code'));
        }
    });
    $('.add-beneficiary-form .verification-btn').on('click', function() {
        $(this).text('Verifying...');
        var form = $('.add-beneficiary-form');
        var data = form.serializeArray().filter(function(item) {
            return item.name != '_method';
        });
        $.post(`${BASE_URL}/admin/dmt/ben-verification`, data, function(res) {
            $(this).text('Verify');
            if (res.status == 'success') {
                $('.add-beneficiary-form input[name="beneficiary_name"]').val(res.data.beneName);
                toastr.success(res.message, 'Success');
            } else {
                toastr.error(res.message, 'Error');
            }
        });
    });
    $('.add-beneficiary-form').parsley().on('form:submit', function() {
        $('.beneficiary-btn').text('Saving...');
        var form = $('.add-beneficiary-form');
        $.post(form.attr('action'), form.serializeArray(), function(res) {
            $('.beneficiary-btn').text('Add Beneficiary');
            if (res.status == 'success') {
                beneficiaryTable.ajax.reload();
                toastr.success(res.message, 'Success');
            } else {
                toastr.error(res.message, 'Error');
            }
        });
        return false;
    });
    $(document).on('click', '.delete', function() {
        $(this).text('Deleting...');
        var id = $(this).data('id');
        var beneId = $(this).data('beneid');
        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "Yes, delete it!",
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.post(`${BASE_URL}/admin/dmt/ben-delete`, { beneId: beneId }, function(res) {
                    if (res.status == 'success') {
                        Swal.fire({
                            title: res.message,
                            input: 'text',
                            inputPlaceholder: 'OTP',
                            showCancelButton: true,
                            confirmButtonText: 'Confirm',
                            showLoaderOnConfirm: true,
                            confirmButtonColor: "#34c38f",
                            cancelButtonColor: "#f46a6a",
                            allowOutsideClick: false,
                            preConfirm: function(otp) {
                                $.post(`${BASE_URL}/admin/dmt/confirm-ben/${id}`, { otp: otp }, function(res) {
                                    if (res.status == 'success') {
                                        beneficiaryTable.ajax.reload();
                                        toastr.success(res.message, 'Success');
                                    } else {
                                        beneficiaryTable.ajax.reload();
                                        toastr.error(res.message, 'Error');
                                    }
                                });
                            }
                        });
                    } else {
                        beneficiaryTable.ajax.reload();
                        toastr.error(res.message, 'Error');
                    }
                });
            } else {
                beneficiaryTable.ajax.reload();
            }
        });
    });
    $(document).on('click', '.transfer', function() {
        var button = $(this);
        button.text('Searching...');
        var id = button.data('id');
        $.get(`${BASE_URL}/admin/dmt/get-ben/${id}`, function(res) {
            if (res.status == 'success') {
                $('.transfer-fund-form input[name="beneId"]').val(res.data.beneId);
                $('.transfer-fund-form input[name="beneficiary_id"]').val(res.data.id);
                $('.transfer-fund-form input[name="ifsc_code"]').val(res.data.ifsc_code);
                $('.transfer-fund-form input[name="bank_name"]').val(res.data.bank_name);
                $('.transfer-fund-form input[name="account_number"]').val(res.data.account_number);
                $('.transfer-fund-form input[name="beneficiary_name"]').val(res.data.beneficiary_name);
                $(`.transfer-fund-form select[name="bank_id"] option[value="${res.data.bank_id}"]`).attr('selected', 'selected');
                $('#fundTransfer #fund-accordion').addClass('show');
                toastr.success(res.message, 'Success');
            } else {
                toastr.error(res.message, 'Error');
            }
            button.text('Transfer');
        });
    });
    $('.transfer-fund-form input[name="amount"]').keyup(function() {
        $('.transfer-fund-form input[name="debit_amount"]').val($(this).val());
    });
    $('.transfer-fund-form').parsley().on('form:submit', function() {
        $('.transfer-btn').text('Transfering...');
        var form = $('.transfer-fund-form');
        $.post(form.attr('action'), form.serializeArray(), function(res) {
            $('.transfer-btn').text('Transfer Fund');
            if (res.status == 'success') {
                $('.transfer-fund-form')[0].reset();
                $('#fundTransfer #fund-accordion').removeClass('show');
                toastr.success(res.message, 'Success');
            } else {
                toastr.error(res.message, 'Error');
            }
        });
        return false;
    });
    $('.transfer-fund-form .verification-btn').on('click', function() {
        $(this).text('Verifying...');
        var form = $('.transfer-fund-form');
        var data = form.serializeArray().filter(function(item) {
            return item.name != '_method';
        });
        $.post(`${BASE_URL}/admin/dmt/ben-verification`, data, function(res) {
            $(this).text('Verify');
            if (res.status == 'success') {
                $('.transfer-fund-form input[name="beneficiary_name"]').val(res.data.beneName);
                toastr.success(res.message, 'Success');
            } else {
                toastr.error(res.message, 'Error');
            }
        });
    });
});