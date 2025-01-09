@props(['heading'])

<h2 class="text-2xl uppercase font-medium mb-1">{{ $heading }}</h2>
<p class="text-gray-600 mb-6 text-sm">
    {{ $slot }}
</p>
