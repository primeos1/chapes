<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Notifications\OTP;
use App\Utilities\Overrider;
use Illuminate\Http\Request;

class OTPController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
    }

    public function generateOtp(Request $request) {
        if (session('transaction_data') == null) {
            return back();
        }
        $alert_col = 'col-lg-6 offset-lg-3';
        return view('backend.customer_portal.otp.show', compact('alert_col'));
    }

    public function resendOtp(Request $request) {
        Overrider::load("Settings");
        auth()->user()->generateOTP();
        auth()->user()->notify(new OTP());
        return back();
    }

}