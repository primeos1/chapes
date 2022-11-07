<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Utilities\Overrider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
        Overrider::load("Settings");
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        config(['recaptchav3.sitekey' => get_option('recaptcha_site_key')]);
        config(['recaptchav3.secret' => get_option('recaptcha_secret_key')]);

        return Validator::make($data, [
            'name'                 => ['required', 'string', 'max:191'],
            'email'                => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'country_code'         => ['required'],
            'phone'                => ['required', 'string', 'unique:users'],
            'password'             => ['required', 'string', 'min:6', 'confirmed'],
            'agree'                => ['required'],
            'g-recaptcha-response' => get_option('enable_recaptcha', 0) == 1 ? 'required|recaptchav3:register,0.5' : '',
        ], [
            'agree.required'                   => _lang('You must agree with our privacy policy and terms of use'),
            'g-recaptcha-response.recaptchav3' => _lang('Recaptcha error!'),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data) {
        $account_number = next_account_number();

        $max_account_number = User::max('account_number');

        if ($max_account_number >= $account_number) {
            $account_number = increment_account_number($max_account_number + 1);
        }

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'account_number'    => $account_number,
            'country_code'      => $data['country_code'],
            'phone'             => $data['phone'],
            'password'          => Hash::make($data['password']),
            'user_type'         => 'customer',
            'email_verified_at' => get_option('email_verification', 'disabled') == 'disabled' ? now() : null,
            'status'            => 1,
            'profile_picture'   => 'default.png',
        ]);

        //Increment Account Number
        increment_account_number();

        return $user;
    }
}
