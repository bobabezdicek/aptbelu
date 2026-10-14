@component('mail::message')
    # Nová rezervace

    **Termín:** {{ $r->from_date->format('d.m.Y') }} – {{ $r->to_date->format('d.m.Y') }} ({{$r->from_date->diffInDays($r->to_date)}} nocí)
    **Hosté:** {{ $r->guests }}

    **Jméno:** {{ $r->first_name }} {{ $r->last_name }}
    **E-mail:** {{ $r->email }}
    **Telefon:** {{ $r->phone ?? '—' }}

    @isset($r->message)
        **Zpráva:**
        {{ $r->message }}
    @endisset

    @component('mail::button', ['url' => url('/admin/reservations/'.$r->id)])
        Otevřít v administraci
    @endcomponent

    Díky,
    {{ config('app.name') }}
@endcomponent
