<?php

namespace App\Http\Controllers\Auth;

use App\Utilities\Overrider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\TwoFactorCode;

class TwoFactorController extends Controller {

	public function index() {
		return view('auth.twoFactor');
	}

	public function store(Request $request) {
		$request->validate([
			'two_factor_code' => 'integer|required',
		]);

		$user = auth()->user();

		if ($request->input('two_factor_code') == $user->two_factor_code) {
			$user->resetTwoFactorCode();

			return redirect()->route('dashboard.index');
		}

		return redirect()->back()
			->withErrors(['two_factor_code' =>
				_lang('OTP you have entered does not match')]);
	}

	public function resend() {
		Overrider::load("Settings");
		$user = auth()->user();
		$user->generateTwoFactorCode();
		$user->notify(new TwoFactorCode());

		return redirect()->back()->withMessage(_lang('OTP has been sent again'));
	}
}