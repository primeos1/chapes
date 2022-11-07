<table class="table table-bordered">
    <tr><td>{{ _lang('User Name') }}</td><td>{{ $depositrequest->user->name }}</td></tr>
    <tr><td>{{ _lang('Account Number') }}</td><td>{{ $depositrequest->user->account_number }}</td></tr>
    <tr><td>{{ _lang('User Email') }}</td><td>{{ $depositrequest->user->email }}</td></tr>
    <tr><td>{{ _lang('Deposit Method') }}</td><td>{{ $depositrequest->method->name }}</td></tr>
    <tr><td>{{ _lang('Amount') }}</td><td>{{ decimalPlace($depositrequest->amount, currency($depositrequest->method->currency->name)) }}</td></tr>
    <tr><td>{{ _lang('Description') }}</td><td>{{ $depositrequest->description }}</td></tr>
    @if($depositrequest->requirements)
    @foreach($depositrequest->requirements as $key => $value)
    <tr>
        <td>{{ ucwords(str_replace('_',' ',$key)) }}</td>
        <td>{{ $value }}</td>
    </tr>
    @endforeach
    @endif
    <tr>
        <td>{{ _lang('Attachment') }}</td>
        <td>
            {!! $depositrequest->attachment == "" ? '' : '<a href="'. asset('public/uploads/media/'.$depositrequest->attachment) .'" target="_blank">'._lang('View Attachment').'</a>' !!}
        </td>
    </tr>
    <tr><td>{{ _lang('Status') }}</td><td>{!! xss_clean(transaction_status($depositrequest->status)) !!}</td></tr>
</table>