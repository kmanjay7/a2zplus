@extends('admin.layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Money Transfer</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin') }}">Admin</a></li>
                    <li class="breadcrumb-item active">Money Transfer</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="row">
    @include ('admin.partials.notice')
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3 text-center">
                            <h4 class="h4 text-primary">Hi, {{ $sender->fullname }}</h4>
                            <h5 class="h5 text-success mt-3">{{ $sender->mobile }}</h5>
                            <input type="hidden" name="sender_id" value="{{ $sender->id }}">
                            <a href="{{ route('admin.dmt.logout') }}" class="btn btn-primary btn-sm waves-effect waves-light w-75 mt-3">Log Out</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 text-center">
                            <h4 class="h4"><strong class="text-danger">NON</strong> <strong class="text-dark">KYC</strong> <strong class="text-danger">USER</strong></h4>
                            <p class="mt-4">To Increase Your Wallet Limit</p>
                            <a class="btn btn-success btn-sm waves-effect waves-light w-75">Verify KYC</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 text-center">
                            <h4 class="h4 text-primary">Available Limit</h4>
                            <h5 class="h5 text-success">Rs {{ number_format($sender->rem_bal, 2) }}</h5>
                            <h4 class="h4 text-primary">Total Limit</h4>
                            <h5 class="h5 text-success">Rs {{ number_format($sender->total_bal, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Beneficiary</h4>
                <form method="post" action="{{ route('admin.money-transfer.update', $sender->id) }}" class="add-beneficiary-form mt-4" novalidate>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label" for="bank_id">Bank Name</label>
                                <select name="bank_id" id="bank_id" class="form-select" required>
                                    <option value="">-- Select Bank Name --</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank['id'] }}" data-ifsc_code="{{ $bank['ifsc'] }}">{{ $bank['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label" for="ifsc_code">IFSC Code</label>
                                <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" placeholder="IFSC Code" data-parsley-type="alphanum" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="account_number">Account Number</label>
                                <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Account Number" data-parsley-type="number" data-parsley-length="[8,25]" required />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label" for="beneficiary_name">Beneficiary Name</label>
                                <input type="text" name="beneficiary_name" id="beneficiary_name" class="form-control" placeholder="Beneficiary Name" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="verification">Verification Charge Rs. 4</label>
                                <button type="button" id="verification" class="btn btn-success waves-effect waves-light w-100 verification-btn">Verify</button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3" style="margin-top: 1.7rem;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="bank_name" value="">
                                <input type="hidden" name="status" value="0">
                                <button type="submit" class="btn btn-primary waves-effect waves-light w-100 beneficiary-btn">Add Beneficiary</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="accordion accordion-flush" id="fundTransfer">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button type="button" class="accordion-button h5" data-bs-toggle="collapse" data-bs-target="#fund-accordion" aria-expanded="false" aria-controls="fund-accordion">Transfer Fund</button>
                        </h2>
                        <div id="fund-accordion" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#fundTransfer">
                            <div class="accordion-body">
                                <form method="post" action="{{ route('admin.dmt.transactionInit', $sender->id) }}" class="transfer-fund-form" novalidate>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="mb-3">
                                                <label class="form-label" for="transaction_bank_id">Bank Name</label>
                                                <select name="bank_id" id="transaction_bank_id" class="form-select" disabled>
                                                    <option value="">-- Select Bank Name --</option>
                                                    @foreach ($banks as $bank)
                                                        <option value="{{ $bank['id'] }}" data-ifsc_code="{{ $bank['ifsc'] }}">{{ $bank['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="transaction_ifsc_code">IFSC Code</label>
                                                <input type="text" name="ifsc_code" id="transaction_ifsc_code" class="form-control" placeholder="IFSC Code" data-parsley-type="alphanum" required readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="transaction_account_number">Account Number</label>
                                                <input type="text" name="account_number" id="transaction_account_number" class="form-control" placeholder="Account Number" data-parsley-type="number" data-parsley-length="[8,25]" required readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="transaction_beneficiary_name">Beneficiary Name</label>
                                                <input type="text" name="beneficiary_name" id="transaction_beneficiary_name" class="form-control" placeholder="Beneficiary Name" required readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="channel">Mode</label>
                                                <select name="channel" id="channel" class="form-select" required>
                                                    <option value="">-- Select Mode --</option>
                                                    <option value="1">NEFT</option>
                                                    <option value="2">IMPS</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="transaction_verification">Verification Charge Rs. 4</label>
                                                <button type="button" id="transaction_verification" class="btn btn-success waves-effect waves-light w-100 verification-btn">Verify</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="amount">Amount</label>
                                                <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount" data-parsley-type="number" min="10" max="50000" required />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="debit_amount">Debit Amount</label>
                                                <input type="text" name="debit_amount" id="debit_amount" class="form-control" placeholder="Debit Amount" data-parsley-type="number" min="10" max="50000" required readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3" style="margin-top: 1.7rem;">
                                                @csrf
                                                <input type="hidden" name="bank_name" value="">
                                                <input type="hidden" name="beneficiary_id" value="">
                                                <input type="hidden" name="beneId" value="">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light w-100 transfer-btn">Transfer Fund</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Beneficiary List</h4>
                <table id="beneficiary-table" class="table table-striped table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>Details</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Transaction List</h4>
                <table id="transaction-table" class="table table-striped table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th class="text-center">Transaction ID</th>
                            <th>Sender</th>
                            <th>Beneficiary</th>
                            <th class="text-center">Mode</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Date & Time</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/admin/libs/toastr/build/toastr.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/admin/libs/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/toastr/build/toastr.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/beneficiary.js') }}"></script>
@endpush