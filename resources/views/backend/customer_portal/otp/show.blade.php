@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-6 offset-lg-3">
		<div class="card">
			<div class="card-header">
				<h4 class="header-title text-center">{{ _lang('Verify OTP') }}</h4>
			</div>
			<div class="card-body">
			    <form method="post" class="validate" autocomplete="off" action="{{ session('action') }}" enctype="multipart/form-data">
					{{ csrf_field() }}

					<div class="alert alert-info" role="alert">
						{{ _lang('OTP has been sent to your email address. Please check your email') }}
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('ENTER OTP') }}</label>
								<input type="text" class="form-control" name="otp" value="{{ old('otp') }}" required>
							</div>
						</div>

						<div class="col-md-12 mt-4">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icofont-check-circled"></i> {{ _lang('Submit') }}</button>
							</div>
						</div>

						<div class="col-12 text-center">
							<a href="{{ route('otp.resend') }}" class="btn-link">{{ _lang('Resend OTP Code') }}</a>
						</div>
					</div>
			    </form>
			</div>
		</div>
    </div>
</div>
@endsection


