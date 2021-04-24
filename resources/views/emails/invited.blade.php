@component('mail::message')
# You've been invited to join a team

You've been invited to join {{ $team->name }} on Provisioning.

@component('mail::button', ['url' => $url])
Accept invitation
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
