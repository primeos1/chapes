@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-signin my-5">
                <div class="card-header text-center">{{ _lang('2FA Verification') }}</div>
                <div class="card-body">

                    @if(session()->has('message'))
                        <p class="alert alert-info">
                            {{ session()->get('message') }}
                        </p>
                    @endif

                    <form method="POST" class="form-signin" action="{{ route('verify.store') }}" autocomplete="off">
                        @csrf

                        <div class="alert alert-info" role="alert">
                            {{ _lang('One time password has been sent to your email address.') }}
                            {{ _lang('If you did not receive the email') }}
                            <a href="{{ route('verify.resend') }}">{{ _lang('Click here') }}</a>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="two_factor_code" type="text" class="form-control{{ $errors->has('two_factor_code') ? ' is-invalid' : '' }}" name="two_factor_code" placeholder="{{ _lang('Enter OTP') }}" required>

                                @if ($errors->has('two_factor_code'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('two_factor_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ _lang('Verify') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection