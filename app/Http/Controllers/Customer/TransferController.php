<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\OtherBank;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\OTP;
use App\Utilities\Overrider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
    }

    public function send_money(Request $request, $otp = '') {
        if ($request->isMethod('get')) {
            $alert_col = 'col-lg-8 offset-lg-2';
            return view('backend.customer_portal.send_money', compact('alert_col'));
        } else if ($request->isMethod('post')) {
            @ini_set('max_execution_time', 0);
            @set_time_limit(0);

            if (auth()->user()->allow_withdrawal == 0) {
                return back()->with('error', _lang('Sorry, Withdraw action is disabled in your account !'));
            }

            if ($otp == 'otp' && get_option('send_money_otp', 0) == 1) {
                if ($request->otp != auth()->user()->otp || auth()->user()->otp_expires_at->lt(now())) {
                    return back()->with('error', 'OTP Code is expired or invalid !');
                }
                $request->merge(session('transaction_data'));
            }

            $validator = Validator::make($request->all(), [
                'user_account' => 'required',
                'currency_id'  => 'required',
                'amount'       => 'required|numeric|min:1.00',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return back()
                        ->withErrors($validator)
                        ->withInput();
                }
            }

            $user = User::whereRaw('email = ? OR account_number = ?', [$request->user_account, $request->user_account])
                ->where('user_type', 'customer')
                ->where('id', '!=', auth()->id())
                ->first();

            if (!$user) {
                return back()->with('error', _lang('User Account not found !'))->withInput();
            }

            $charge = 0;

            if (get_option('transfer_fee_type') == 'percentage') {
                $charge = (get_option('transfer_fee', 0) / 100) * $request->amount;
            } else if (get_option('transfer_fee_type') == 'fixed') {
                $charge = convert_currency(base_currency_id(), $request->currency_id, get_option('transfer_fee', 0));
            }

            //Check Available Balance
            if (get_account_balance($request->currency_id) < $request->amount + $charge) {
                if (!$request->ajax()) {
                    return back()->with('error', _lang('Insufficient balance !'))->withInput();
                } else {
                    return response()->json(['result' => 'error', 'message' => _lang('Insufficient balance !')]);
                }
            }

            //OTP Operations
            if (get_option('send_money_otp', 0) == 1 && $otp == '') {
                session(['transaction_data' => $request->all()]);
                session(['action' => route('transfer.send_money', 'otp')]);

                Overrider::load("Settings");
                auth()->user()->generateOTP();
                auth()->user()->notify(new OTP());
                return redirect()->route('otp.generate');
            }

            DB::beginTransaction();

            $status = get_option('send_money_action', 0) == 1 ? 1 : 2;

            //Create Debit Transactions
            $debit                  = new Transaction();
            $debit->user_id         = auth()->id();
            $debit->currency_id     = $request->input('currency_id');
            $debit->amount          = $request->input('amount') + $charge;
            $debit->fee             = $charge;
            $debit->dr_cr           = 'dr';
            $debit->type            = 'Transfer';
            $debit->method          = 'Online';
            $debit->status          = $status;
            $debit->note            = $request->input('note');
            $debit->created_user_id = auth()->id();
            $debit->branch_id       = auth()->user()->branch_id;

            $debit->save();

            //Create Credit Transactions
            $credit                  = new Transaction();
            $credit->user_id         = $user->id;
            $credit->currency_id     = $request->input('currency_id');
            $credit->amount          = $request->input('amount');
            $credit->dr_cr           = 'cr';
            $credit->type            = 'Transfer';
            $credit->method          = 'Online';
            $credit->status          = $status;
            $credit->note            = $request->input('note');
            $credit->created_user_id = auth()->id();
            $credit->branch_id       = auth()->user()->branch_id;
            $credit->parent_id       = $debit->id;

            $credit->save();

            $request->session()->forget(['transaction_data', 'action']);

            DB::commit();

            if ($status == 2) {
                return redirect()->route('transfer.send_money')->with('success', _lang('Money Transfered Successfully'));
            } else if ($status == 1) {
                return redirect()->route('transfer.send_money')->with('success', _lang('Transfer Request Submitted. It will deposit recipient account once approved by authority.'));
            }
        }
    }

    public function exchange_money(Request $request, $otp = '') {
        if ($request->isMethod('get')) {
            $alert_col = 'col-lg-8 offset-lg-2';
            return view('backend.customer_portal.exchange_money', compact('alert_col'));
        } else if ($request->isMethod('post')) {
            @ini_set('max_execution_time', 0);
            @set_time_limit(0);

            if (auth()->user()->allow_withdrawal == 0) {
                return back()->with('error', _lang('Sorry, Withdraw action is disabled in your account !'));
            }

            if ($otp == 'otp' && get_option('exchange_money_otp', 0) == 1) {
                if ($request->otp != auth()->user()->otp || auth()->user()->otp_expires_at->lt(now())) {
                    return back()->with('error', 'OTP Code is expired or invalid !');
                }
                $request->merge(session('transaction_data'));
            }

            $validator = Validator::make($request->all(), [
                'currency_from' => 'required',
                'currency_to'   => 'required',
                'amount'        => 'required|numeric|min:1.00',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return back()
                        ->withErrors($validator)
                        ->withInput();
                }
            }

            $charge = 0;

            if (get_option('exchange_fee_type') == 'percentage') {
                $charge = (get_option('exchange_fee', 0) / 100) * $request->amount;
            } else if (get_option('exchange_fee_type') == 'fixed') {
                $charge = convert_currency(base_currency_id(), $request->currency_from, get_option('exchange_fee', 0));
            }

            //Check Available Balance
            if (get_account_balance($request->currency_from) < $request->amount + $charge) {
                if (!$request->ajax()) {
                    return back()->with('error', _lang('Insufficient balance !'))->withInput();
                } else {
                    return response()->json(['result' => 'error', 'message' => _lang('Insufficient balance !')]);
                }
            }

            //OTP Operations
            if (get_option('exchange_money_otp', 0) == 1 && $otp == '') {
                session(['transaction_data' => $request->all()]);
                session(['action' => route('transfer.exchange_money', 'otp')]);

                Overrider::load("Settings");
                auth()->user()->generateOTP();
                auth()->user()->notify(new OTP());
                return redirect()->route('otp.generate');
            }

            DB::beginTransaction();

            $status = get_option('exchange_money_action', 0) == 1 ? 1 : 2;

            //Create Debit Transactions
            $debit                  = new Transaction();
            $debit->user_id         = auth()->id();
            $debit->currency_id     = $request->input('currency_from');
            $debit->amount          = $request->input('amount') + $charge;
            $debit->fee             = $charge;
            $debit->dr_cr           = 'dr';
            $debit->type            = 'Exchange';
            $debit->method          = 'Online';
            $debit->status          = $status;
            $debit->note            = $request->input('note');
            $debit->created_user_id = auth()->id();
            $debit->branch_id       = auth()->user()->branch_id;

            $debit->save();

            //Create Credit Transactions
            $credit                  = new Transaction();
            $credit->user_id         = auth()->id();
            $credit->currency_id     = $request->currency_to;
            $credit->amount          = convert_currency($request->currency_from, $request->currency_to, $request->amount);
            $credit->dr_cr           = 'cr';
            $credit->type            = 'Exchange';
            $credit->method          = 'Online';
            $credit->status          = $status;
            $credit->note            = $request->input('note');
            $credit->created_user_id = auth()->id();
            $credit->branch_id       = auth()->user()->branch_id;
            $credit->parent_id       = $debit->id;

            $credit->save();

            $request->session()->forget(['transaction_data', 'action']);

            DB::commit();

            if ($status == 2) {
                return redirect()->route('transfer.exchange_money')->with('success', _lang('Money Exchanged Successfully'));
            } else if ($status == 1) {
                return redirect()->route('transfer.exchange_money')->with('success', _lang('Exchange Request Submitted. It will deposit to your account once approved by authority.'));
            }
        }
    }

    public function wire_transfer(Request $request, $otp = '') {
        if ($request->isMethod('get')) {
            $alert_col = 'col-lg-8 offset-lg-2';
            return view('backend.customer_portal.wire_transfer', compact('alert_col'));
        } else if ($request->isMethod('post')) {
            @ini_set('max_execution_time', 0);
            @set_time_limit(0);

            if (auth()->user()->allow_withdrawal == 0) {
                return back()->with('error', _lang('Sorry, Withdraw action is disabled in your account !'));
            }

            if ($otp == 'otp' && get_option('wire_transfer_otp', 0) == 1) {
                if ($request->otp != auth()->user()->otp || auth()->user()->otp_expires_at->lt(now())) {
                    return back()->with('error', 'OTP Code is expired or invalid !');
                }
                $request->merge(session('transaction_data'));
            }

            $validator = Validator::make($request->all(), [
                'bank'                   => 'required',
                'amount'                 => 'required|numeric',
                'td.account_number'      => 'required',
                'td.account_holder_name' => 'required',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return back()
                        ->withErrors($validator)
                        ->withInput();
                }
            }

            $bank = OtherBank::find($request->bank);

            $charge = $bank->fixed_charge;
            $charge += ($bank->charge_in_percentage / 100) * $request->amount;

            //Check Minimum & Maximum Amount
            if ($request->amount < $bank->minimum_transfer_amount || $request->amount > $bank->maximum_transfer_amount) {
                return back()->with('error', _lang('Amount must be') . ' (' . $bank->currency->name . ' ' . $bank->minimum_transfer_amount . ' - ' . $bank->currency->name . ' ' . $bank->maximum_transfer_amount . ')')->withInput();
            }

            //Check Available Balance
            if (get_account_balance($bank->bank_currency) < $request->amount + $charge) {
                return back()->with('error', _lang('Insufficient balance !'))->withInput();
            }

            //OTP Operations
            if (get_option('wire_transfer_otp', 0) == 1 && $otp == '') {
                session(['transaction_data' => $request->all()]);
                session(['action' => route('transfer.wire_transfer', 'otp')]);

                Overrider::load("Settings");
                auth()->user()->generateOTP();
                auth()->user()->notify(new OTP());
                return redirect()->route('otp.generate');
            }

            //Create Debit Transactions
            $debit                      = new Transaction();
            $debit->user_id             = auth()->id();
            $debit->currency_id         = $bank->bank_currency;
            $debit->amount              = $request->input('amount') + $charge;
            $debit->fee                 = $charge;
            $debit->dr_cr               = 'dr';
            $debit->type                = 'Wire_Transfer';
            $debit->method              = 'Manual';
            $debit->status              = 1;
            $debit->note                = $request->input('note');
            $debit->other_bank_id       = $bank->id;
            $debit->created_user_id     = auth()->id();
            $debit->branch_id           = auth()->user()->branch_id;
            $debit->transaction_details = json_encode($request->td);

            $debit->save();

            $request->session()->forget(['transaction_data', 'action']);

            return redirect()->route('transfer.wire_transfer')->with('success', _lang('Your Transfer Request send sucessfully. You will notified after reviewing by authority.'));

        }
    }

    public function get_other_bank_details($id) {
        $bank = \App\Models\OtherBank::with('currency')->find($id);
        return response()->json($bank);
    }

    public function get_exchange_amount($from, $to, $amount){
        $amount = convert_currency($from, $to, $amount);
        return response()->json(['amount' => $amount]);
    }

    public function show_transaction($id) {
        if (request()->ajax()) {
            $transaction = \App\Models\Transaction::where('id', $id)->where('user_id', auth()->id())->first();
            return view('backend.customer_portal.transaction_details', compact('transaction'));
        }
        return back();
    }

}