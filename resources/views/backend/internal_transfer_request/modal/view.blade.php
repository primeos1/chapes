<table class="table table-bordered">
	<tr><td>{{ _lang('Sender Name') }}</td><td>{{ $transaction->user->name }}</td></tr>
	<tr><td>{{ _lang('Sender Email') }}</td><td>{{ $transaction->user->email }}</td></tr>
	<tr><td>{{ _lang('Send Amount') }}</td><td>{{ decimalPlace($transaction->amount,currency($transaction->currency->name)) }}</td></tr>

	<tr><td>{{ _lang('Receiver Name') }}</td><td>{{ $transaction->child_transaction->user->name }}</td></tr>
	<tr><td>{{ _lang('Receiver Email') }}</td><td>{{ $transaction->child_transaction->user->email }}</td></tr>
	<tr><td>{{ _lang('Received Amount') }}</td><td>{{ decimalPlace($transaction->child_transaction->amount,currency($transaction->child_transaction->currency->name)) }}</td></tr>

    <tr><td>{{ _lang('DR/CR') }}</td><td>{{ strtoupper($transaction->dr_cr) }}</td></tr>
	<tr><td>{{ _lang('Type') }}</td><td>{{ str_replace("_"," ",$transaction->type) }}</td></tr>
	<tr><td>{{ _lang('Status') }}</td><td>{!! xss_clean(transaction_status($transaction->status)) !!}</td></tr>
	<tr><td>{{ _lang('Note') }}</td><td>{{ $transaction->note }}</td></tr>
	<tr><td>{{ _lang('Created At') }}</td><td>{{ $transaction->created_at}}</td></tr>
	<tr><td>{{ _lang('Created By') }}</td><td>{{ $transaction->created_by->name}}</td></tr>
</table>

