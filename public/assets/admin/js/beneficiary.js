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
                            <p>A/C No: <span>${row.account_number}</span></p>
                            <p>IFSC Code: <span>${row.ifsc_code}</span></p>
                            <p>Bank Name: <span>${row.bank_name}</span></p>
                        </div>`;
            }
        }, {
            targets: 1,
            render: function render(data, type, row, meta) {
                var className, buttonText = '';
                if (row.status) {
                    className = 'success';
                    buttonText = 'Verified';
                } else {
                    className = 'warning';
                    buttonText = 'Not Verified';
                }
                return `<div class="text-center">
                            <button type="button" data-id="${row.id}" data-beneId="${row.beneId}" class="btn btn-${className} btn-sm w-30 ms-2">${buttonText}</button>
                            <button type="button" data-id="${row.id}" data-beneId="${row.beneId}" class="btn btn-primary btn-sm w-25 ms-2 transfer">Transfer</button>
                            <button type="button" data-id="${row.id}" data-beneId="${row.beneId}" class="btn btn-danger btn-sm w-25 ms-2 delete">Delete</button>
                        </div>`;
            }
        }]
    });
    var transactionTable = $('#transaction-table').DataTable({
        pageLength: 5,
        lengthMenu: [5, 10, 20, 50, 100],
        processing: true,
        serverSide: true,
        ajax: `${BASE_URL}/admin/dmt/trans-list/${$('input[name="sender_id"]').val()}`,
        columns: [
            { data: 'txnId', name: 'txnId', className: 'text-center' },
            { data: 'sender_name', name: 'sender_name' },
            { data: 'beneficiary_name', name: 'beneficiary_name' },
            { data: 'channel', name: 'channel', className: 'text-center' },
            { data: 'debit_amount', name: 'debit_amount', className: 'text-center' },
            { data: 'date_time', name: 'date_time', className: 'text-center' },
            { data: 'trans_status', name: 'trans_status', className: 'text-center' }
        ],
        columnDefs: [{
            targets: 1,
            render: function render(data, type, row, meta) {
                return `<div class="text-left">
                            <p><b>${row.sender_name}</b></p>
                            <p><span>${row.sender_mobile}</span></p>
                        </div>`;
            }
        }, {
            targets: 2,
            render: function render(data, type, row, meta) {
                return `<div class="text-left">
                            <p><b>${row.beneficiary_name}</b></p>
                            <p>A/C No: <span>${row.account_number}</span></p>
                            <p>IFSC Code: <span>${row.ifsc_code}</span></p>
                            <p>Bank Name: <span>${row.bank_name}</span></p>
                        </div>`;
            }
        }, {
            targets: 6,
            render: function render(data, type, row, meta) {
                var className = '';
                if (row.trans_status == 'SUCCESS') {
                    className = 'success';
                } else if (row.trans_status == 'ACCEPTED') {
                    className = 'info';
                } else if (row.trans_status == 'PENDING') {
                    className = 'danger';
                } else {
                    className = 'dark';
                }
                return `<button type="button" class="btn btn-${className} btn-sm w-100">${row.trans_status}</button>`;
            }
        }]
    });
    $('.add-beneficiary-form select[name="bank_id"]').on('change', function() {
        if ($(this).find('option:selected').val()) {
            $('.add-beneficiary-form input[name="bank_name"]').val($(this).find('option:selected').text());
            $('.add-beneficiary-form input[name="ifsc_code"]').val($(this).find('option:selected').data('ifsc_code'));
        }
    });
    $('.add-beneficiary-form').parsley().on('form:submit', function() {
        var button = $('.beneficiary-btn');
        var form = $('.add-beneficiary-form');
        button.text('Saving...');
        $.post(form.attr('action'), form.serializeArray(), function(res) {
            button.text('Add Beneficiary');
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
        var id = button.data('id');
        button.text('Searching...');
        $.get(`${BASE_URL}/admin/dmt/get-ben/${id}`, function(res) {
            button.text('Transfer');
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
        });
    });
    $('.transfer-fund-form input[name="amount"]').keyup(function() {
        $('.transfer-fund-form input[name="debit_amount"]').val($(this).val());
    });
    $('.transfer-fund-form').parsley().on('form:submit', function() {
        var button = $('.transfer-btn');
        var form = $('.transfer-fund-form');
        button.text('Transfering...');
        $.post(form.attr('action'), form.serializeArray(), function(res) {
            button.text('Transfer Fund');
            if (res.status == 'success') {
                $('.transfer-fund-form')[0].reset();
                $('#fundTransfer #fund-accordion').removeClass('show');
                transactionTable.ajax.reload();
                toastr.success(res.message, 'Success');
            } else {
                toastr.error(res.message, 'Error');
            }
        });
        return false;
    });
    $.get(`${BASE_URL}/admin/dmt/trans-check/${$('input[name="sender_id"]').val()}`, function(res) {
        transactionTable.ajax.reload();
        if (res.status == 'success') {
            toastr.success(res.message, 'Success');
        } else {
            toastr.error(res.message, 'Error');
        }
    });
    $('.add-beneficiary-form .verification-btn').on('click', function() {
        var button = $(this);
        button.text('Verifying...');
        var data = {
            'benif_id': $('.transfer-fund-form input[name="beneId"]').val(),
            'bank_name': $('.add-beneficiary-form input[name="bank_name"]').val(),
            'accountno': $('.add-beneficiary-form input[name="account_number"]').val(),
            'ifsc_code': $('.add-beneficiary-form input[name="ifsc_code"]').val()
        };
        $.post(`https://mydigicash.in/ag/Kyc_nonkyc_users/accountdetails`, data, function(res) {
            button.text('Verify');
            console.log(res);
            // if (res.status == 'success') {
            //     $('.add-beneficiary-form input[name="status"]').val(1);
            //     $('.add-beneficiary-form input[name="beneficiary_name"]').val(res.data.beneName);
            //     toastr.success(res.message, 'Success');
            // } else {
            //     toastr.error(res.message, 'Error');
            // }
        });
    });
    // $('.transfer-fund-form .verification-btn').on('click', function() {
    //     $(this).text('Verifying...');
    //     var form = $('.transfer-fund-form');
    //     var data = form.serializeArray().filter(function(item) {
    //         return item.name != '_method';
    //     });
    //     $.post(`${BASE_URL}/admin/dmt/ben-verification`, data, function(res) {
    //         $(this).text('Verify');
    //         if (res.status == 'success') {
    //             $('.transfer-fund-form input[name="beneficiary_name"]').val(res.data.beneName);
    //             toastr.success(res.message, 'Success');
    //         } else {
    //             toastr.error(res.message, 'Error');
    //         }
    //     });
    // });
});