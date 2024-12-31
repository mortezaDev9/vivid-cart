<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $pageTitle ?? config('app.name') }}</title>

        <style>
            @import url('https://cdn.jsdelivr.net/npm/tailwindcss@2.0.1/dist/tailwind.min.css');
        </style>
    </head>
    <body class="bg-gray-100 font-sans">
    <div class="max-w-lg mx-auto my-10 bg-white rounded-lg shadow-lg overflow-hidden">
        <x-mail.section.header heading="{{ $heading }}">
            {{ $title ?? '' }}
        </x-mail.section.header>

        <main>
            {{ $slot }}
        </main>

        <x-mail.section.footer />
    </div>
    </body>
</html>
