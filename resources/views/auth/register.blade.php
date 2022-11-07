@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-10">
            <div class="card card-signin my-5 p-3">
				<div class="card-body">
				    <img class="logo" src="{{ get_logo() }}">

					<h5 class="text-center py-4">{{ _lang('Create Your Account Now') }}</h4>

                    <form method="POST" class="form-signup" autocomplete="off" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
							<div class="col-md-12">
                                <input id="name" type="text" placeholder="{{ _lang('Name') }}" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-md-12">
                                <input id="email" type="email" placeholder="{{ _lang('E-Mail Address') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6 mb-sm-3 mb-lg-0 pr-lg-1">
                                <select class="form-control select2" name="country_code" required>
                                    <option value="">{{ _lang('Country Code') }}</option>
                                    @foreach(get_country_codes() as $key => $value)
                                    <option value="{{ $value['dial_code'] }}" {{ old('country_code') == $value['dial_code'] ? 'selected' : '' }}>{{ $value['country'].' (+'.$value['dial_code'].')' }}</option>
                                    @endforeach
                                </select>
                            </div>

							<div class="col-lg-6 pl-lg-0">
                                <input id="phone" type="text" placeholder="{{ _lang('Phone') }}" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" required>

                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-md-12">
                                <input id="password" type="password" placeholder="{{ _lang('Password') }}" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                           <div class="col-md-12">
                                <input id="password-confirm" type="password" class="form-control" placeholder="{{ _lang('Confirm Password') }}" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="hidden" class="{{ $errors->has('g-recaptcha-response') ? ' is-invalid' : '' }}" name="g-recaptcha-response" id="recaptcha">
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="checkbox" class="{{ $errors->has('agree') ? ' is-invalid' : '' }}" name="agree" id="agree">
                                <label class="d-inline" for="agree">
                                    &nbsp;I agree with <a href="{{ url('/' . get_option('privacy_policy_page')) }}" target="blank">{{ _lang('Privacy Policy') }}</a>
                                        &amp;
                                        <a href="{{ url('/' . get_option('terms_condition_page')) }}" target="blank">{{ _lang('Terms & Condition') }}</a>
                                </label>
                                @if ($errors->has('agree'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('agree') }} !</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

						<div class="form-group row mt-5">
							<div class="col-md-12 text-center">
								<button type="submit" class="btn btn-primary btn-login" id="create-account-btn" disabled>
								{{ _lang('Create My Account') }}
                                </button>
							</div>
						</div>
                        <div class="form-group row mt-5">
							<div class="col-md-12 text-center">
							   {{ _lang('Already Have An Account?') }}
                               <a href="{{ route('login') }}">{{ _lang('Log In Here') }}</a>
							</div>
						</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('public/backend/assets/js/jquery-3.6.0.min.js') }}"></script>

@if(get_option('enable_recaptcha', 0) == 1)
<script src="https://www.google.com/recaptcha/api.js?render={{ get_option('recaptcha_site_key') }}"></script>
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('{{ get_option('recaptcha_site_key') }}', {action: 'register'}).then(function(token) {
        if (token) {
            document.getElementById('recaptcha').value = token;
        }
        });
    });
</script>
@endif

<script>
    (function ($) {
        "use strict";

        $(this).is(":checked") ? $("#create-account-btn").prop('disabled', false) : $("#create-account-btn").prop('disabled', true);

        $(document).on('click','#agree', function(){
            $(this).is(":checked") ? $("#create-account-btn").prop('disabled', false) : $("#create-account-btn").prop('disabled', true);
        });
    })(jQuery);
</script>
@endsection
