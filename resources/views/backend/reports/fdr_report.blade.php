@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<span class="panel-title">{{ _lang('FDR Report') }}</span>
			</div>

			<div class="card-body">

				<div class="report-params">
					<form class="validate" method="post" action="{{ route('reports.fdr_report') }}">
						<div class="row">
              				{{ csrf_field() }}

							<div class="col-xl-2 col-lg-4">
								<div class="form-group">
									<label class="control-label">{{ _lang('Start Date') }}</label>
									<input type="text" class="form-control datepicker" name="date1" id="date1" value="{{ isset($date1) ? $date1 : old('date1') }}" readOnly="true" required>
								</div>
							</div>

							<div class="col-xl-2 col-lg-4">
								<div class="form-group">
									<label class="control-label">{{ _lang('End Date') }}</label>
									<input type="text" class="form-control datepicker" name="date2" id="date2" value="{{ isset($date2) ? $date2 : old('date2') }}" readOnly="true" required>
								</div>
							</div>

							<div class="col-xl-2 col-lg-4">
								<div class="form-group">
								<label class="control-label">{{ _lang('FDR Plans') }}</label>
									<select class="form-control auto-select" data-selected="{{ isset($fdr_plan) ? $fdr_plan : old('fdr_plan') }}" name="fdr_plan">
										<option value="">{{ _lang('All') }}</option>
										{{ create_option('fdr_plans','id','name', array('status=' => 1)) }}
									</select>
								</div>
							</div>

                            <div class="col-xl-2 col-lg-4">
								<div class="form-group">
								<label class="control-label">{{ _lang('Status') }}</label>
									<select class="form-control auto-select" data-selected="{{ isset($status) ? $status : old('status') }}" name="status">
										<option value="">{{ _lang('All') }}</option>
										<option value="0">{{ _lang('Pending') }}</option>
										<option value="1">{{ _lang('Active') }}</option>
										<option value="3">{{ _lang('Completed') }}</option>
										<option value="2">{{ _lang('Cancelled') }}</option>
									</select>
								</div>
							</div>

							<div class="col-xl-2 col-lg-4">
								<div class="form-group">
									<label class="control-label">{{ _lang('User Account') }}</label>
									<input type="text" class="form-control" name="user_account" value="{{ isset($user_account) ? $user_account : old('user_account') }}">
								</div>
							</div>

							<div class="col-xl-2 col-lg-4">
								<button type="submit" class="btn btn-light btn-sm btn-block mt-26"><i class="icofont-filter"></i> {{ _lang('Filter') }}</button>
							</div>
						</form>

					</div>
				</div><!--End Report param-->

				@php $date_format = get_option('date_format','Y-m-d'); @endphp
				@php $currency = currency(); @endphp

				<div class="report-header">
				   <h4>{{ _lang('FDR Report') }}</h4>
				   <h5>{{ isset($date1) ? date($date_format, strtotime($date1)).' '._lang('to').' '.date($date_format, strtotime($date2)) : '----------  '._lang('to').'  ----------' }}</h5>
				</div>

				<table class="table table-bordered report-table">
					<thead>
						<th>{{ _lang('Plan') }}</th>
						<th>{{ _lang('User') }}</th>
						<th>{{ _lang('AC Number') }}</th>
						<th>{{ _lang('Currency') }}</th>
						<th>{{ _lang('Deposit Amount') }}</th>
						<th>{{ _lang('Return Amount') }}</th>
						<th>{{ _lang('Status') }}</th>
						<th>{{ _lang('Mature Date') }}</th>
						<th class="text-center">{{ _lang('Details') }}</th>
					</thead>
					<tbody>
					@if(isset($report_data))
						@foreach($report_data as $fixeddeposit)
							<tr>
								<td>{{ $fixeddeposit->plan->name }}</td>
								<td>{{ $fixeddeposit->user->name }}<br>{{ $fixeddeposit->user->email }}</td>
								<td>{{ $fixeddeposit->user->account_number }}</td>
								<td>{{ $fixeddeposit->currency->name }}</td>
								<td>{{ $fixeddeposit->deposit_amount }}</td>
								<td>{{ $fixeddeposit->return_amount }}</td>
								<td>{!! xss_clean(fdr_status($fixeddeposit->status)) !!}</td>
								<td>{{ $fixeddeposit->mature_date }}</td>
								<td class="text-center"><a href="{{ action('FixedDepositController@show', $fixeddeposit->id) }}" target="_blank" class="btn btn-outline-primary btn-sm">{{ _lang('View') }}</a></td>
							</tr>
						@endforeach
					@endif
				    </tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection