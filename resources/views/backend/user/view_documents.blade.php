@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-12">
		@if(Session::has('varified_success'))
			<div class="alert alert-success">
				<span>{{ session('varified_success') }}</span>
			</div>  
		@endif
		<div class="card">
			<div class="card-header d-flex align-items-center">
                <h4 class="header-title">{{ _lang('Documents of').' '.$user->name }}</h4>
				<div class="ml-auto">
					@if($user->document_verified_at == null)
						<div class="btn-group" role="group">
							<a href="{{ route('users.documents.varify', $user->id) }}" class="btn btn-success btn-sm float-right"><i class="icofont-check-circled"></i> {{ _lang('Approve') }}</a>
							<a href="{{ route('users.documents.unvarify', $user->id) }}" class="btn btn-danger btn-sm float-right"><i class="icofont-check-circled"></i> {{ _lang('Reject') }}</a>
						</div>
					@else
						<a href="{{ route('users.documents.unvarify', $user->id) }}" class="btn btn-danger btn-sm float-right"><i class="icofont-close-circled"></i> {{ _lang('Click to Unverify') }}</a>
					@endif	
				</div>
            </div>
			<div class="card-body">
			
				<table class="table table-bordered data-table">
					<thead>
						<tr>
							<th>{{ _lang('Document Name') }}</th>
							<th>{{ _lang('Document File') }}</th>
							<th>{{ _lang('Submitted At') }}</th>
						</tr>
					</thead>
					<tbody>		
					@foreach($documents as $document)
						<tr>
							<td>{{ $document->document_name }}</td>
							<td><a target="_blank" href="{{ asset('public/uploads/media/'.$document->document ) }}">{{ $document->document }}</a></td>
							<td>{{ date('d M, Y H:i:s',strtotime($document->created_at)) }}</td>																			
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection


