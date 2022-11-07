@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<span class="panel-title">{{ _lang('View Payment Details') }}</span>
			</div>
			
			<div class="card-body">
				<table class="table table-bordered">
					<tr>
						<td>{{ _lang('Loan ID') }}</td>
						<td><a href="{{ action('LoanController@show', $loanpayment->loan->id) }}" target="_blank">{{ $loanpayment->loan->loan_id }}</a></td>
					</tr>
					<tr><td>{{ _lang('Payment Date') }}</td><td>{{ $loanpayment->paid_at }}</td></tr>
					<tr><td>{{ _lang('Principal Amount') }}</td><td>{{ ($loanpayment->amount_to_pay - $loanpayment->interest) }}</td></tr>
					<tr><td>{{ _lang('Interest') }}</td><td>{{ $loanpayment->interest }}</td></tr>
					<tr><td>{{ _lang('Late Penalties') }}</td><td>{{ $loanpayment->late_penalties }}</td></tr>
					<tr><td>{{ _lang('Total Amount') }}</td><td>{{ decimalPlace($loanpayment->amount_to_pay + $loanpayment->interest, currency($loanpayment->loan->currency->name)) }}</td></tr>
					<tr><td>{{ _lang('Remarks') }}</td><td>{{ $loanpayment->remarks }}</td></tr>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection


