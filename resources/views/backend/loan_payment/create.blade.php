@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-8 offset-lg-2">
		<div class="card">
			<div class="card-header text-center">
				{{ _lang('Add Loan Repayment') }}
			</div>
			<div class="card-body">
				<form method="post" class="validate" autocomplete="off" action="{{ route('loan_payments.store') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Payment Date') }}</label>						
								<input type="text" class="form-control datepicker" name="paid_at" id="paid_at" value="{{ old('paid_at') }}" required>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Loan ID') }}</label>						
								<select class="form-control auto-select select2" data-selected="{{ old('loan_id') }}" id="loan_id" name="loan_id" required>
									<option value="">{{ _lang('Select One') }}</option>
									@foreach(\App\Models\Loan::where('status',1)->get() as $loan)
										<option value="{{ $loan->id }}" data-user-id="{{ $loan->borrower_id }}" data-currency="{{ $loan->currency->name }}">{{ $loan->loan_id }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Due Amount for') }}</label>						
								<select class="form-control" name="due_amount_of" id="due_amount_of" required>
								</select>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Late Penalties').' ( '._lang('It will apply if payment date is over') }} )</label>						
								<div class="input-group">
									<input type="text" class="form-control float-field" name="late_penalties" id="late_penalties" value="{{ old('late_penalties',0) }}">
									<div class="input-group-append">
										<span class="input-group-text currency"></span>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Amount To Pay') }}</label>						
								<div class="input-group">
									<input type="text" class="form-control float-field" name="amount_to_pay" id="amount_to_pay" value="{{ old('amount_to_pay') }}" readonly="true" required>
									<div class="input-group-append">
										<span class="input-group-text currency"></span>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Remarks') }}</label>						
								<textarea class="form-control" name="remarks">{{ old('remarks') }}</textarea>
							</div>
						</div>

							
						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block">{{ _lang('Submit') }}</button>
							</div>
						</div>
					</div>			
				</form>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js-script')
<script>
	$(function() {

	"use strict";

	$(document).on('change','#loan_id',function(){

		var user_id = $(this).find(':selected').data('user-id');
		var currency = $(this).find(':selected').data('currency');
		var loan_id = $(this).val();

		if( loan_id != '' ){
			$.ajax({
				url: "{{ url('admin/loan_payments/get_repayment_by_loan_id') }}/" + loan_id,
				beforeSend: function(){
					$("#preloader").css("display","block"); 
				},success: function(data){
					$("#preloader").css("display","none");
					var json = JSON.parse(data);
					$("#due_amount_of").find('option').remove();
					$("#due_amount_of").append("<option value=''>{{ _lang('Select One') }}</option>");

					jQuery.each( json, function( i, val ) {
						$("#due_amount_of").append("<option value='" + val.id + "' data-penalty='" + val.penalty + "' data-amount='" + val.amount_to_pay + "'>" + val.repayment_date + "</option>");
					});
				}
			});

			$(".currency").html(currency);
		}
	});

	$(document).on('change','#due_amount_of',function(){
		if($("#paid_at").val() == ''){
			alert("Please Select Payment date first");
			$(this).val('');
			return;
		}
		var repayment_date = $(this).find(':selected').text();
		var penalty = $(this).find(':selected').data('penalty');
		var amount_to_pay = $(this).find(':selected').data('amount');

		var due = moment($("#paid_at").val()).diff( moment(repayment_date), 'days');

		if(due > 0){
			$("#late_penalties").val(penalty);
		}

		$("#amount_to_pay").val(amount_to_pay);
	});
});
</script>
@endsection


