<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sender;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use App\Services\A2zSuvidhaa;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MoneyTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $banks = [];
        
        $current_mobile = $request->session()->get('current_mobile');

        if (!$current_mobile) {

            return redirect()->route('admin.money-transfer.create')->withError('Please Login First');
        }

        $sender = Sender::where(['mobile' => $current_mobile, 'status' => 1])->first();

        $response = A2zSuvidhaa::getResponse('v3/get-bank-list', []);

        if ($response['status'] == 1) {

            $banks = $response['bankLists'];
        }

        return view('admin.money-transfer.index', compact('sender', 'banks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $current_mobile = $request->session()->get('current_mobile');

        if ($current_mobile) {

            $sender = Sender::where(['mobile' => $current_mobile, 'status' => 1])->first();

            return redirect()->route('admin.money-transfer.index')->withSuccess('You are already login: '.$sender->fullname);
        }

        return view('admin.money-transfer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'bail|required|string|max:255',
            'last_name' => 'bail|required|string|max:255',
            'mobile' => 'bail|required|numeric|digits:10|unique:senders',
            'pincode' => 'bail|required|numeric|digits:6'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 'error',
                'message' => 'Please Send Validated Data'
            ]);

        } else {

            $data = $request->all();

            $response = A2zSuvidhaa::getResponse('v3/dmt/a2z-remitter-register', [
                'fname' => $data['first_name'],
                'lname' => $data['last_name'],
                'mobile' => $data['mobile'],
                'walletType' => 0
            ]);
            
            if ($response['status'] == 12) { 

                Sender::create($data);

                return response()->json([
                    'status' => 'success',
                    'message' => $response['message']
                ]);

            } else {

                return response()->json([
                    'status' => 'error',
                    'message' => $response['message']
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($mobile, Request $request)
    {
        $sender = Sender::where(['mobile' => $mobile])->first();

        if ($sender) {

            $response = A2zSuvidhaa::getResponse('v3/dmt/a2z-mobile-verification', [
                'mobile' => $mobile
            ]);

            if ($response['status'] == 12) {

                return response()->json([
                    'status' => 'verification',
                    'message' => $response['message']
                ]);
    
            } elseif ($response['status'] == 13) {
    
                $sender->update(['rem_bal' => $response['message']['rem_bal']]);
    
                $request->session()->put('current_mobile', $mobile);
    
                return response()->json([
                    'status' => 'success',
                    'fullname' => $sender->fullname
                ]);
    
            } else {
    
                return response()->json([
                    'status' => 'error',
                    'message' => $response['message']
                ]);
            }

        } else {

            return response()->json([
                'status' => 'registration',
                'message' => 'Please fill registration form first'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bank_id' => 'bail|required|numeric|min:1',
            'bank_name' => 'bail|required|string|max:255',
            'ifsc_code' => 'bail|required|alpha_num|max:25',
            'beneficiary_name' => 'bail|required|string|max:255',
            'account_number' => 'bail|required|numeric|digits_between:9,18'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 'error',
                'message' => 'Please Send Validated Data'
            ]);

        } else {

            $data = $request->all();

            $current_mobile = $request->session()->get('current_mobile');

            $response = A2zSuvidhaa::getResponse('v3/dm/a2z-add-beneficiary', [
                'mobile' => $current_mobile,
                'ifscCode' => $data['ifsc_code'],
                'bankName' => $data['bank_name'],
                'beneName' => $data['beneficiary_name'],
                'accountNumber' => $data['account_number']
            ]);
    
            if ($response['status'] == 35) {

                $data['sender_id'] = $id;

                $data['beneId'] = $response['beneId'];

                Beneficiary::create($data);
    
                return response()->json([
                    'status' => 'success',
                    'message' => $response['message']
                ]);
    
            } else {
    
                return response()->json([
                    'status' => 'error',
                    'message' => $response['message']
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $beneficiary = Beneficiary::find($id);

        $beneficiary->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->session()->forget('current_mobile');

        return redirect()->route('admin.money-transfer.create')->withSuccess('You are successfully logout');
    }

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function verifyOtp(Request $request)
    {
        $response = A2zSuvidhaa::getResponse('v3/dmt/a2z-mobile-verification-with-otp', [
            'otp' => $request->input('otp'),
            'mobile' => $request->input('mobile')
        ]);

        if ($response['status'] == 17) {

            $balance_response = A2zSuvidhaa::getResponse('v3/dmt/a2z-mobile-verification', [
                'mobile' => $request->input('mobile')
            ]);

            $sender = Sender::where(['mobile' => $request->input('mobile')])->first();

            $sender->update(['total_bal' => $balance_response['message']['rem_bal'], 'status' => 1]);

            $request->session()->put('current_mobile', $request->input('mobile'));

            return response()->json([
                'status' => 'success',
                'fullname' => $sender->fullname,
                'message' => $response['message']
            ]);

        } else {

            return response()->json([
                'status' => 'error',
                'message' => $response['message']
            ]);
        }
    }

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function resendOtp(Request $request)
    {
        $response = A2zSuvidhaa::getResponse('v3/dmt/a2z-mobile-verification', [
            'mobile' => $request->input('mobile')
        ]);

        if ($response['status'] == 12) {

            return response()->json([
                'status' => 'success',
                'message' => 'OTP has been sent at entered mobile number'
            ]);

        } else {

            return response()->json([
                'status' => 'error',
                'message' => $response['message']
            ]);
        }
    }
}
