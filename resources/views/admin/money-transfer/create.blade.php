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
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="#" class="sender-mobile-form" novalidate>
                    <div class="row">
                        <div class="col-md-3 offset-md-3">
                            <div class="mb-3">
                                <label class="form-label" for="mobile">Sender Mobile Number</label>
                                <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile Number" data-parsley-type="number" data-parsley-length="[10,11]" required />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3" style="margin-top: 1.7rem;">
                                <button type="submit" class="btn btn-primary waves-effect waves-light w-100 continue-btn">Continue</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade registration-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sender Registeration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="#" class="registration-form" novalidate>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="first_name">First Name</label>
                                                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="last_name">Last Name</label>
                                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="pincode">Pincode</label>
                                                <input type="text" name="pincode" id="pincode" class="form-control" placeholder="Pincode" data-parsley-type="number" data-parsley-length="[5,6]" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3" style="margin-top: 1.7rem;">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light w-100 register-btn">Register</button>
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
</div>
<div class="modal fade verify-otp-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="verifyOtpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify Sender</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="#" class="verify-otp-form" novalidate>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <div class="mb-3">
                                                <label class="form-label" for="otp">OTP</label>
                                                <input type="text" name="otp" id="otp" class="form-control" placeholder="4 digit OTP" data-parsley-type="number" style="text-align: center;" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <button type="button" class="btn btn-success waves-effect waves-light w-100 resend-otp-btn">Resend OTP</button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light w-100 verify-otp-btn">Verify</button>
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
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/admin/libs/toastr/build/toastr.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/admin/libs/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/toastr/build/toastr.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/sender.js') }}"></script>
@endpush