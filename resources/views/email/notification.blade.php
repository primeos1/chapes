@component('mail::message')

{!! xss_clean($message) !!}<br>

{{ _lang('Thanks') }},<br>
{{ get_option('site_title', config('app.name')) }}
@endcomponent