<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-home::section.meta />
         <title>{{ config('app.name') }} - {{ $title ?? 'Home' }}</title>

        <x-home::section.css />
        @stack('styles')
    </head>
    <body>
        <x-home::section.header />
        @if(Route::currentRouteNamed('home'))
            <x-home::section.navbar :categories="$categories" />
        @endif

        <main>
            {{ $slot }}
        </main>

        <x-home::section.footer />

        <hr />

        <x-home::section.copyright />
        <x-home::section.js />
        <x-home::toastr />
        @stack('scripts')
    </body>
</html>
