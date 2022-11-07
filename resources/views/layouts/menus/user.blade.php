@php $permissions = permission_list(); @endphp
<div class="sb-sidenav-menu-heading">{{ _lang('NAVIGATIONS') }}</div>

<a class="nav-link" href="{{ route('dashboard.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-dashboard-web"></i></div>
	{{ _lang('Dashboard') }}
</a>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#users" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-users-alt-3"></i></div>
	{{ _lang('Users') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="users" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		@if (in_array('users.create',$permissions))
		<a class="nav-link" href="{{ route('users.create') }}">{{ _lang('Add New') }}</a>
		@endif

		@if (in_array('users.index',$permissions))
		<a class="nav-link" href="{{ route('users.index') }}">{{ _lang('All Users') }}</a>
		@endif

		@if (in_array('users.documents',$permissions))
		@if(get_option('enable_kyc') == 'yes')
			<a class="nav-link" href="{{ route('users.documents') }}">
				{{ _lang('KYC Documents') }}
				{!! xss_clean(kyc_count()) !!}
			</a>
		@endif
		@endif

		@if (in_array('users.filter',$permissions))
		<a class="nav-link" href="{{ route('users.filter') }}/email_verified">{{ _lang('Email Verified') }}</a>
		<a class="nav-link" href="{{ route('users.filter') }}/sms_verified">{{ _lang('SMS Verified') }}</a>
		<a class="nav-link" href="{{ route('users.filter') }}/email_unverified">{{ _lang('Email Unverified') }}</a>
		<a class="nav-link" href="{{ route('users.filter') }}/sms_unverified">{{ _lang('SMS Unverified') }}</a>
		@endif
	</nav>
</div>

@if (in_array('internal_transfer_requests.index',$permissions))
@if(get_option('send_money_action', 0) == 1 || get_option('exchange_money_action', 0) == 1)
<a class="nav-link" href="{{ route('internal_transfer_requests.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-info-square"></i></div>
	{{ _lang('Transfer Requests') }}
	{!! xss_clean(request_count('internal_transfer_requests',true)) !!}
</a>
@endif
@endif

@if (in_array('transfer_requests.index',$permissions))
<a class="nav-link" href="{{ route('transfer_requests.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-bank-transfer-alt"></i></div>
	{{ _lang('Transfer Request') }}
	{!! xss_clean(request_count('wire_transfer_requests',true)) !!}
</a>
@endif

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#deposit" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-plus-square"></i></div>
	{{ _lang('Deposit') }}
	{!! xss_clean(request_count('deposit_requests',true)) !!}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="deposit" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		@if (in_array('deposit_requests.index',$permissions))
		<a class="nav-link" href="{{ route('deposit_requests.index') }}">{{ _lang('Deposit Request') }}</a>
		@endif

		@if (in_array('deposits.create',$permissions))
		<a class="nav-link" href="{{ route('deposits.create') }}">{{ _lang('Make Deposit') }}</a>
		@endif

		@if (in_array('deposits.index',$permissions))
		<a class="nav-link" href="{{ route('deposits.index') }}">{{ _lang('Deposit History') }}</a>
		@endif
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#withdraw" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-minus-square"></i></div>
	{{ _lang('Withdraw') }}
	{!! xss_clean(request_count('withdraw_requests',true)) !!}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="withdraw" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		@if (in_array('withdraw_requests.index',$permissions))
		<a class="nav-link" href="{{ route('withdraw_requests.index') }}">{{ _lang('Withdraw Request') }}</a>
		@endif

		@if (in_array('withdraw.create',$permissions))
		<a class="nav-link" href="{{ route('withdraw.create') }}">{{ _lang('Make Withdraw') }}</a>
		@endif

		@if (in_array('withdraw.index',$permissions))
		<a class="nav-link" href="{{ route('withdraw.index') }}">{{ _lang('Withdraw History') }}</a>
		@endif
	</nav>
</div>

@if (in_array('transactions.index',$permissions))
<a class="nav-link" href="{{ route('transactions.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-listing-number"></i></div>
	{{ _lang('All Transactions') }}
</a>
@endif

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#loans" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-dollar-minus"></i></div>
	{{ _lang('Loan Management') }}
	{!! xss_clean(request_count('pending_loans',true)) !!}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="loans" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		@if (in_array('loans.index',$permissions))
		<a class="nav-link" href="{{ route('loans.index') }}">{{ _lang('All Loans') }}</a>
		@endif

		@if (in_array('loans.calculator',$permissions))
		<a class="nav-link" href="{{ route('loans.admin_calculator') }}">{{ _lang('Loan Calculator') }}</a>
		@endif

		@if (in_array('loans.create',$permissions))
		<a class="nav-link" href="{{ route('loans.create') }}">{{ _lang('Add New Loan') }}</a>
		@endif

		@if (in_array('loan_products.index',$permissions))
		<a class="nav-link" href="{{ route('loan_products.index') }}">{{ _lang('Loan Products') }}</a>
		@endif

		@if (in_array('loan_payments.index',$permissions))
		<a class="nav-link" href="{{ route('loan_payments.index') }}">{{ _lang('Loan Repayments') }}</a>
		@endif
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#fdr" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-money"></i></div>
	{{ _lang('Fixed Deposit') }}
	{!! xss_clean(request_count('fdr_requests',true)) !!}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="fdr" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		@if (in_array('fixed_deposits.create',$permissions))
		<a class="nav-link" href="{{ route('fixed_deposits.create') }}">{{ _lang('Add New') }}</a>
		@endif

		@if (in_array('fixed_deposits.index',$permissions))
		<a class="nav-link" href="{{ route('fixed_deposits.index') }}">{{ _lang('All FDR') }}</a>
		@endif

		@if (in_array('fdr_plans.index',$permissions))
		<a class="nav-link" href="{{ route('fdr_plans.index') }}">{{ _lang('FDR Packages') }}</a>
		@endif
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#gift_card" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-gift"></i></div>
	{{ _lang('Gift Cards') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="gift_card" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		@if (in_array('gift_cards.index',$permissions))
		<a class="nav-link" href="{{ route('gift_cards.index') }}">{{ _lang('Gift Cards') }}</a>
		@endif

		@if (in_array('gift_cards.filter',$permissions))
		<a class="nav-link" href="{{ route('gift_cards.filter','used_gift_card') }}">{{ _lang('Used Gift Card') }}</a>
		@endif
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#tickets" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-live-support"></i></div>
	{{ _lang('Support Tickets') }}
	{!! xss_clean(request_count('pending_tickets',true)) !!}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="tickets" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		@if (in_array('support_tickets.index',$permissions))
		<a class="nav-link" href="{{ route('support_tickets.index',['status' => 'active']) }}">{{ _lang('Active Tickets') }}</a>
		<a class="nav-link" href="{{ route('support_tickets.index',['status' => 'pending']) }}">{{ _lang('Pending Tickets') }}</a>
		<a class="nav-link" href="{{ route('support_tickets.index',['status' => 'closed']) }}">{{ _lang('Closed Tickets') }}</a>
		@endif
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#reports" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-chart-line-alt"></i></div>
	{{ _lang('Reports') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="reports" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		@if (in_array('reports.transactions_report',$permissions))
		<a class="nav-link" href="{{ route('reports.transactions_report') }}">{{ _lang('Transactions Reports') }}</a>
		@endif

		@if (in_array('reports.loan_report',$permissions))
		<a class="nav-link" href="{{ route('reports.loan_report') }}">{{ _lang('Loan Reports') }}</a>
		@endif

		@if (in_array('reports.fdr_report',$permissions))
		<a class="nav-link" href="{{ route('reports.fdr_report') }}">{{ _lang('FDR Reports') }}</a>
		@endif

		@if (in_array('reports.bank_revenues',$permissions))
		<a class="nav-link" href="{{ route('reports.bank_revenues') }}">{{ _lang('Bank Revenues') }}</a>
		@endif
	</nav>
</div>