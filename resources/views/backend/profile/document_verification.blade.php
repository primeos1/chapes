@extends('layouts.app')

@section('content')
<div class="row">
	
	@if(Auth::user()->user_type == 'customer' && Auth::user()->document_submitted_at != null)	
		<div class="col-lg-12">
			<div class="alert alert-danger text-center">
				<span>{{ _lang('You have already submit your documents ! You will be notified soon after reviewing your documents.') }}</span>
			</div>
		</div>
	@else

	<div class="col-lg-6 offset-lg-3">
		<div class="card">
			<div class="card-header text-center">
				{{ _lang('KYC Verification') }}
			</div>

			<div class="card-body">
				<form action="{{ route('profile.document_verification') }}" autocomplete="off" method="post" enctype="multipart/form-data">
					@csrf
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('IDENTITY VERIFICATION') }}</label>
								<input type="file" class="form-control dropify" name="identity_verification" required>
							</div>
						</div>

            			<div class="col-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('ADDRESS VERIFICATION') }}</label>
								<input type="file" class="form-control dropify" name="address_verification" required>
							</div>
						</div>

						<div class="col-12">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block"><i class="icofont-check-circled"></i> {{ _lang('Submit') }}</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	@endif
</div>
@endsection

