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
		<a class="nav-link" href="{{ route('users.create') }}">{{ _lang('Add New') }}</a>
		<a class="nav-link" href="{{ route('users.index') }}">{{ _lang('All Users') }}</a>
		@if(get_option('enable_kyc') == 'yes')
		<a class="nav-link" href="{{ route('users.documents') }}">
			{{ _lang('KYC Documents') }}
			{!! xss_clean(kyc_count()) !!}
		</a>
		@endif
		<a class="nav-link" href="{{ route('users.filter') }}/email_verified">{{ _lang('Email Verified') }}</a>
		<a class="nav-link" href="{{ route('users.filter') }}/sms_verified">{{ _lang('SMS Verified') }}</a>
		<a class="nav-link" href="{{ route('users.filter') }}/email_unverified">{{ _lang('Email Unverified') }}</a>
		<a class="nav-link" href="{{ route('users.filter') }}/sms_unverified">{{ _lang('SMS Unverified') }}</a>
	</nav>
</div>

@if(get_option('send_money_action', 0) == 1 || get_option('exchange_money_action', 0) == 1)
<a class="nav-link" href="{{ route('internal_transfer_requests.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-info-square"></i></div>
	{{ _lang('Transfer Requests') }}
	{!! xss_clean(request_count('internal_transfer_requests',true)) !!}
</a>
@endif

<a class="nav-link" href="{{ route('transfer_requests.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-bank-transfer-alt"></i></div>
	{{ _lang('Wire Transfers') }}
	{!! xss_clean(request_count('wire_transfer_requests',true)) !!}
</a>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#deposit" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-plus-square"></i></div>
	{{ _lang('Deposit') }}
	{!! xss_clean(request_count('deposit_requests',true)) !!}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="deposit" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('deposit_requests.index') }}">{{ _lang('Deposit Request') }}</a>
		<a class="nav-link" href="{{ route('deposits.create') }}">{{ _lang('Make Deposit') }}</a>
		<a class="nav-link" href="{{ route('deposits.index') }}">{{ _lang('Deposit History') }}</a>
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
		<a class="nav-link" href="{{ route('withdraw_requests.index') }}">{{ _lang('Withdraw Request') }}</a>
		<a class="nav-link" href="{{ route('withdraw.create') }}">{{ _lang('Make Withdraw') }}</a>
		<a class="nav-link" href="{{ route('withdraw.index') }}">{{ _lang('Withdraw History') }}</a>
	</nav>
</div>

<a class="nav-link" href="{{ route('transactions.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-listing-number"></i></div>
	{{ _lang('All Transactions') }}
</a>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#loans" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-dollar-minus"></i></div>
	{{ _lang('Loan Management') }}
	{!! xss_clean(request_count('pending_loans',true)) !!}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="loans" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('loans.index') }}">{{ _lang('All Loans') }}</a>
		<a class="nav-link" href="{{ route('loans.admin_calculator') }}">{{ _lang('Loan Calculator') }}</a>
		<a class="nav-link" href="{{ route('loans.create') }}">{{ _lang('Add New Loan') }}</a>
		<a class="nav-link" href="{{ route('loan_products.index') }}">{{ _lang('Loan Products') }}</a>
		<a class="nav-link" href="{{ route('loan_payments.index') }}">{{ _lang('Loan Repayments') }}</a>
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
		<a class="nav-link" href="{{ route('fixed_deposits.create') }}">{{ _lang('Add New') }}</a>
		<a class="nav-link" href="{{ route('fixed_deposits.index') }}">{{ _lang('All FDR') }}</a>
		<a class="nav-link" href="{{ route('fdr_plans.index') }}">{{ _lang('FDR Packages') }}</a>
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#gift_card" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-gift"></i></div>
	{{ _lang('Gift Cards') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="gift_card" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('gift_cards.index') }}">{{ _lang('Gift Cards') }}</a>
		<a class="nav-link" href="{{ route('gift_cards.filter','used_gift_card') }}">{{ _lang('Used Gift Card') }}</a>
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
		<a class="nav-link" href="{{ route('support_tickets.index',['status' => 'active']) }}">{{ _lang('Active Tickets') }}</a>
		<a class="nav-link" href="{{ route('support_tickets.index',['status' => 'pending']) }}">{{ _lang('Pending Tickets') }}</a>
		<a class="nav-link" href="{{ route('support_tickets.index',['status' => 'closed']) }}">{{ _lang('Closed Tickets') }}</a>
	</nav>
</div>

<div class="sb-sidenav-menu-heading">{{ _lang('System Settings') }}</div>

<a class="nav-link" href="{{ route('branches.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-site-map"></i></div>
	{{ _lang('Branches') }}
</a>

<a class="nav-link" href="{{ route('other_banks.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-bank"></i></div>
	{{ _lang('Other Banks') }}
</a>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#reports" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-chart-line-alt"></i></div>
	{{ _lang('Reports') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="reports" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('reports.transactions_report') }}">{{ _lang('Transactions Reports') }}</a>
		<a class="nav-link" href="{{ route('reports.loan_report') }}">{{ _lang('Loan Reports') }}</a>
		<a class="nav-link" href="{{ route('reports.fdr_report') }}">{{ _lang('FDR Reports') }}</a>
		<a class="nav-link" href="{{ route('reports.bank_revenues') }}">{{ _lang('Bank Revenues') }}</a>
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#systemUsers" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-users-alt-4"></i></div>
	{{ _lang('System Users') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="systemUsers" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('system_users.index') }}">{{ _lang('All Users') }}</a>
		<a class="nav-link" href="{{ route('roles.index') }}">{{ _lang('User Roles') }}</a>
		<a class="nav-link" href="{{ route('permission.index') }}">{{ _lang('Access Control') }}</a>
	</nav>
</div>

<a class="nav-link" href="{{ route('currency.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-euro"></i></div>
	{{ _lang('Currency List') }}
</a>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#deposit_settings" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-credit-card"></i></div>
	{{ _lang('Transactions Settings') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="deposit_settings" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('payment_gateways.index') }}">{{ _lang('Deposit Gateways') }}</a>
		<a class="nav-link" href="{{ route('deposit_methods.index') }}">{{ _lang('Deposit Methods') }}</a>
		<a class="nav-link" href="{{ route('withdraw_methods.index') }}">{{ _lang('Withdraw Methods') }}</a>
		<a class="nav-link" href="{{ route('settings.system_settings') }}/transactions_fee">{{ _lang('Transactions Fee') }}</a>
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#websiteManagement" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-monitor"></i></div>
	{{ _lang('Website Management') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="websiteManagement" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('services.index') }}">{{ _lang('Services') }}</a>
		<a class="nav-link" href="{{ route('faqs.index') }}">{{ _lang('FAQ') }}</a>
		<a class="nav-link" href="{{ route('testimonials.index') }}">{{ _lang('Testimonials') }}</a>
		<a class="nav-link" href="{{ route('teams.index') }}">{{ _lang('Teams') }}</a>
		<a class="nav-link" href="{{ route('pages.index') }}">{{ _lang('Pages') }}</a>
		<a class="nav-link" href="{{ route('navigations.index') }}">{{ _lang('Menu Management') }}</a>
		<a class="nav-link" href="{{ route('theme_option.update') }}">{{ _lang('Theme Options') }}</a>
	</nav>
</div>


<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#administration" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-settings-alt"></i></div>
	{{ _lang('Administration') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>

<div class="collapse" id="administration" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('settings.update_settings') }}">{{ _lang('General Settings') }}</a>
		<a class="nav-link" href="{{ route('email_templates.index') }}">{{ _lang('Email Templates') }}</a>
		<a class="nav-link" href="{{ route('sms_templates.index') }}">{{ _lang('SMS Templates') }}</a>
		<a class="nav-link" href="{{ route('database_backups.list') }}">{{ _lang('Database Backup') }}</a>
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#languages" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-world"></i></div>
	{{ _lang('Languages') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="languages" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('languages.index') }}">{{ _lang('All Language') }}</a>
		<a class="nav-link" href="{{ route('languages.create') }}">{{ _lang('Add New') }}</a>
	</nav>
</div>