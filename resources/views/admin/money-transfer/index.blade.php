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
                                <label class="form-label" for="bank_name">Bank Name</label>
                                <select name="bank_name" id="bank_name" class="form-select" required>
                                    <option value="">-- Select Bank Name --</option>
                                    <option value="1" data-ifsc_code="SBIN0004659">SBI</option>
                                    <option value="2" data-ifsc_code="PNB0004659">PNB</option>
                                    <option value="3" data-ifsc_code="CB0004659">City Bank</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->bankId }}" data-ifsc_code="{{ $bank->ifscCode }}">{{ $bank->bankName }}</option>
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
                                <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Account Number" data-parsley-type="number" data-parsley-length="[9,18]" required />
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
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label" for="verification">Verification Charge Rs. 4</label>
                                <button type="button" id="verification" class="btn btn-success waves-effect waves-light w-100">Verify</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3" style="margin-top: 1.7rem;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary waves-effect waves-light w-100 beneficiary-btn">Add Beneficiary</button>
                            </div>
                        </div>
                    </div>
                </form>
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
                    <tbody>
                        <tr>
                            <td>
                                <p><b>Manjay Kumar</b></p>
                                <p>A/C: <span>915010065900111</span></p>
                                <p>IFSC:<span>UTI000001</span></p>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-sm w-25 ms-2">Verified</button>
                                <button type="button" class="btn btn-primary btn-sm w-25 ms-2">Transfer</button>
                                <button type="button" class="btn btn-danger btn-sm w-25 ms-2">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><b>Sanjay Kumar</b></p>
                                <p>A/C: <span>915010065900111</span></p>
                                <p>IFSC:<span>UTI000001</span></p>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-sm w-25 ms-2">Verified</button>
                                <button type="button" class="btn btn-primary btn-sm w-25 ms-2">Transfer</button>
                                <button type="button" class="btn btn-danger btn-sm w-25 ms-2">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><b>Manjay Kumar</b></p>
                                <p>A/C: <span>915010065900111</span></p>
                                <p>IFSC:<span>UTI000001</span></p>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-sm w-25 ms-2">Verified</button>
                                <button type="button" class="btn btn-primary btn-sm w-25 ms-2">Transfer</button>
                                <button type="button" class="btn btn-danger btn-sm w-25 ms-2">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><b>Sanjay Kumar</b></p>
                                <p>A/C: <span>915010065900111</span></p>
                                <p>IFSC:<span>UTI000001</span></p>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-sm w-25 ms-2">Verified</button>
                                <button type="button" class="btn btn-primary btn-sm w-25 ms-2">Transfer</button>
                                <button type="button" class="btn btn-danger btn-sm w-25 ms-2">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><b>Sanjay Kumar</b></p>
                                <p>A/C: <span>915010065900111</span></p>
                                <p>IFSC:<span>UTI000001</span></p>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-sm w-25 ms-2">Verified</button>
                                <button type="button" class="btn btn-primary btn-sm w-25 ms-2">Transfer</button>
                                <button type="button" class="btn btn-danger btn-sm w-25 ms-2">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link href="{{ asset('assets/admin/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
<script src="{{ asset('assets/admin/libs/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/beneficiary.js') }}"></script>
@endpush