<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-home::section.meta />
        <title>{{ config('app.name') }} - {{ $title ?? 'Login' }}</title>

        <x-home::section.css />
    </head>
    <body>
        <main>
            {{ $slot }}
        </main>

        <x-home::section.js />
        <x-home::toastr />
    </body>
</html>
