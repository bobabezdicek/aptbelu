@component('mail::message')
    # Díky za rezervaci!

    Tvoji poptávku jsme přijali. Brzy se ozveme s finálním potvrzením.

    **Termín:** {{ $r->from_date->format('d.m.Y') }} – {{ $r->to_date->format('d.m.Y') }}
    **Počet osob:** {{ $r->guests }}

    Pokud je v datech chyba, odpověz na tento e-mail.

    Hezký den,
    {{ config('app.name') }}
@endcomponent