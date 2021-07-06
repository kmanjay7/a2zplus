<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sender;
use App\Models\Beneficiary;
use App\Models\Transaction;
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
        $current_mobile = $request->session()->get('current_mobile');

        if (!$current_mobile) {

            return redirect()->route('admin.money-transfer.create')->withError('Please Login First');
        }

        $sender = Sender::where(['mobile' => $current_mobile, 'status' => 1])->first();

        $banks = json_decode(A2zSuvidhaa::getResponse('v3/get-bank-list', []), true);

        if (!empty($banks)) {

            $banks = [
                ['id' => 2, 'ifsc' => 'SBIN0008079', 'name' => 'STATE BANK OF INDIA (SBI)'],
                ['id' => 3, 'ifsc' => 'BKID0007109', 'name' => 'BANK OF INDIA (BOI)']
            ];
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
                'errors' => $validator->errors(),
                'message' => 'Please Send Validated Data'
            ]);

        } else {

            $data = $request->all();

            $response = A2zSuvidhaa::getResponse('v3/dmt/a2z-remitter-register', [
                'walletType' => 0,
                'mobile' => $data['mobile'],
                'fname' => $data['first_name'],
                'lname' => $data['last_name']
            ]);
            
            if (!empty($response['status']) && $response['status'] == 12) { 

                Sender::create($data);

                return response()->json([
                    'status' => 'success',
                    'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
                ]);

            } else {

                return response()->json([
                    'status' => 'error',
                    'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $mobile
     * @return \Illuminate\Http\Response
     */
    public function show($mobile, Request $request)
    {
        $sender = Sender::where(['mobile' => $mobile])->first();

        if ($sender) {

            $response = A2zSuvidhaa::getResponse('v3/dmt/a2z-mobile-verification', [
                'mobile' => $mobile
            ]);

            if (!empty($response['status']) && $response['status'] == 12) {

                return response()->json([
                    'status' => 'verification',
                    'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
                ]);
    
            } elseif (!empty($response['status']) && $response['status'] == 13) {
    
                // update remaining balance.
                $sender->update(['rem_bal' => $response['message']['rem_bal']]);
    
                $request->session()->put('current_mobile', $mobile);
    
                return response()->json([
                    'status' => 'success',
                    'fullname' => $sender->fullname
                ]);
    
            } else {
    
                return response()->json([
                    'status' => 'error',
                    'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
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
            'account_number' => 'bail|required|numeric|digits_between:8,25'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Please Send Validated Data'
            ]);

        } else {

            $data = $request->all();

            $current_mobile = $request->session()->get('current_mobile');

            $response = A2zSuvidhaa::getResponse('v3/dmt/a2z-add-beneficiary', [
                'mobile' => $current_mobile,
                'ifscCode' => $data['ifsc_code'],
                'bankName' => $data['bank_name'],
                'beneName' => $data['beneficiary_name'],
                'accountNumber' => $data['account_number']
            ]);
    
            if (!empty($response['status']) && $response['status'] == 35) {

                $data['sender_id'] = $id;

                $data['beneId'] = $response['beneId'];

                Beneficiary::create($data);
    
                return response()->json([
                    'status' => 'success',
                    'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
                ]);
    
            } else {
    
                return response()->json([
                    'status' => 'error',
                    'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
                ]);
            }
        }
    }

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function logout(Request $request)
    {
        $request->session()->forget('current_mobile');

        return redirect()->route('admin.money-transfer.create')->withSuccess('You are successfully logout');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyOtp(Request $request)
    {
        $response = A2zSuvidhaa::getResponse('v3/dmt/a2z-mobile-verification-with-otp', [
            'otp' => $request->input('otp'),
            'mobile' => $request->input('mobile')
        ]);

        if (!empty($response['status']) && $response['status'] == 17) {

            $balance_response = A2zSuvidhaa::getResponse('v3/dmt/a2z-mobile-verification', [
                'mobile' => $request->input('mobile')
            ]);

            $sender = Sender::where(['mobile' => $request->input('mobile')])->first();

            // update total balance
            $sender->update(['total_bal' => $balance_response['message']['rem_bal'], 'status' => 1]);

            $request->session()->put('current_mobile', $request->input('mobile'));

            return response()->json([
                'status' => 'success',
                'fullname' => $sender->fullname,
                'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
            ]);

        } else {

            return response()->json([
                'status' => 'error',
                'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resendOtp(Request $request)
    {
        $response = A2zSuvidhaa::getResponse('v3/dmt/a2z-mobile-verification', [
            'mobile' => $request->input('mobile')
        ]);

        if (!empty($response['status']) && $response['status'] == 12) {

            return response()->json([
                'status' => 'success',
                'message' => 'OTP has been sent at entered mobile number'
            ]);

        } else {

            return response()->json([
                'status' => 'error',
                'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $sender_id
     * @return \Illuminate\Http\Response
     */
    public function benList($sender_id)
    {
        return Datatables::of(Beneficiary::where('sender_id', $sender_id))->make(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getBen($id)
    {
        $beneficiary = Beneficiary::find($id);

        if ($beneficiary) {

            return response()->json([
                'status' => 'success',
                'data' => $beneficiary,
                'message' => 'Enter amount to be transferable'
            ]);

        } else {

            return response()->json([
                'status' => 'error',
                'message' => 'Beneficiary Not Found'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function benDelete(Request $request)
    {
        $response = A2zSuvidhaa::getResponse('v3/dmt/a2z-bene-delete-request', [
            'beneId' => $request->input('beneId')
        ]);

        if (!empty($response['status']) && $response['status'] == 37) {

            return response()->json([
                'status' => 'success',
                'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
            ]);

        } else {

            return response()->json([
                'status' => 'error',
                'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
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
    public function confirmBen($id, Request $request)
    {
        $beneficiary = Beneficiary::find($id);

        $response = A2zSuvidhaa::getResponse('v3/dmt/bene-delete-confirm-otp', [
            'otp' => $request->input('otp'),
            'beneId' => $beneficiary->beneId
        ]);

        if (!empty($response['status']) && $response['status'] == 38) {

            $beneficiary->delete();

            return response()->json([
                'status' => 'success',
                'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
            ]);

        } else {

            return response()->json([
                'status' => 'error',
                'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $sender_id
     * @return \Illuminate\Http\Response
     */
    public function transList($sender_id)
    {
        return Datatables::of(Transaction::where('sender_id', $sender_id))->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function benVerification(Request $request)
    {
        $current_mobile = $request->session()->get('current_mobile');

        $response = A2zSuvidhaa::getResponse('v3/dmt/verify-account-number', [
            'mobile' => $current_mobile,
            'clientId' => 'ACVR'.date('Ymd').''.rand(10000, 100000),
            'ifscCode' => $request->input('ifsc_code'),
            'bankName' => $request->input('bank_name'),
            'accountNumber' => $request->input('account_number')
        ]);

        if (!empty($response['status']) && $response['status'] == 1) {

            return response()->json([
                'status' => 'success',
                'data' => $response,
                'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
            ]);

        } else {

            return response()->json([
                'status' => 'error',
                'data' => $response,
                'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function transactionInit($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'bail|required|numeric',
            'debit_amount' => 'bail|required|numeric',
            'channel' => 'bail|required|numeric|min:1',
            'beneId' => 'bail|required|string|max:255',
            'ifsc_code' => 'bail|required|alpha_num|max:25',
            'beneficiary_id' => 'bail|required|numeric|min:1',
            'beneficiary_name' => 'bail|required|string|max:255',
            'account_number' => 'bail|required|numeric|digits_between:8,25',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Please Send Validated Data'
            ]);

        } else {

            $data = $request->all();

            $sender = Sender::find($id);

            $data['clientId'] = 'ACVR'.date('Ymd').''.rand(10000, 100000);

            $response = A2zSuvidhaa::getResponse('v3/dmt/a2z-transaction', [
                'walletType' => 0,
                'mobile' => $sender->mobile,
                'beneId' => $data['beneId'],
                'amount' => $data['amount'],
                'channel' => $data['channel'],
                'clientId' => $data['clientId'],
                'accountNumber' => $data['account_number']
            ]);
            
            if (!empty($response['status']) &&  $response['status'] == 3 ||  $response['status'] == 34) { 

                $data['sender_id'] = $id;

                $data['trans_status'] = str_replace('Transaction ', '', $response['message']);

                Transaction::create($data);

                return response()->json([
                    'status' => 'success',
                    'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
                ]);

            } else {

                return response()->json([
                    'status' => 'error',
                    'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
                ]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function instantPay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel' => 'bail|required|numeric|min:1',
            'beneId' => 'bail|required|string|max:255',
            'clientId' => 'bail|required|string|max:255',
            'ifsc_code' => 'bail|required|alpha_num|max:25',
            'beneficiary_id' => 'bail|required|numeric|min:1',
            'beneficiary_name' => 'bail|required|string|max:255',
            'account_number' => 'bail|required|numeric|digits_between:8,25',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Please Send Validated Data'
            ]);

        } else {

            $data = $request->all();

            $current_mobile = $request->session()->get('current_mobile');

            $response = A2zSuvidhaa::getResponse('v3/dmt/a2z-transaction', [
                'walletType' => 0,
                'mobile' => $current_mobile,
                'beneId' => $data['beneId'],
                'amount' => $data['amount'],
                'channel' => $data['channel'],
                'clientId' => $data['clientId'],
                'accountNumber' => $data['account_number']
            ]);
            
            // if (!empty($response['status']) &&  $response['status'] == 3 ||  $response['status'] == 34) { 

            //     $data['sender_id'] = $id;

            //     $data['trans_status'] = str_replace('Transaction ', '', $response['message']);

            //     Transaction::create($data);

            //     return response()->json([
            //         'status' => 'success',
            //         'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
            //     ]);

            // } else {

            //     return response()->json([
            //         'status' => 'error',
            //         'message' => !empty($response['message']) ? $response['message'] : 'A2Z Server Not Responsed'
            //     ]);
            // }

            $response = A2zSuvidhaa::instantPayResponse('ws/imps/account_validate', [
                'remittermobile' => $current_mobile,
                'account' => 710000003704,
                'ifsc' => 'PYTM0123456'
            ]);

            return response()->json($response);
        }
    }
}
