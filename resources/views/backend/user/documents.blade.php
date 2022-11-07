@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
                <h4 class="header-title">{{ _lang('User Documents') }}</h4>
            </div>
			<div class="card-body">
				<table class="table table-bordered data-table">
					<thead>
						<tr>
							<th>{{ _lang('Name') }}</th>
							<th>{{ _lang('Email') }}</th>
							<th class="text-center">{{ _lang('Account Status') }}</th>
							<th class="text-center">{{ _lang('Total Document') }}</th>
							<th class="text-center">{{ _lang('View') }}</th>
						</tr>
					</thead>
					<tbody>					
						@foreach($users as $user)
						<tr id="row_{{ $user->id }}">
							<td class='first_name'>{{ $user->name }}</td>
							<td class='email'>{{ $user->email }}</td>																	
							<td class='text-center'>{!! $user->document_verified_at != null ? show_status(_lang('Verified'), 'success') : show_status(_lang('Unverified'), 'danger') !!}</td>					
							<td class='text-center'>{{ $user->documents->count() }}</td>					
							<td class="text-center">
								<a href="{{ route('users.view_documents', $user->id) }}" class="btn btn-primary btn-sm">{{ _lang('View Documents') }}</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection


