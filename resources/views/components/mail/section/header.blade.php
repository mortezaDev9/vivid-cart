@props(['heading'])

<div class="p-6 text-white text-center" style="background-color: #fd3d57;">
    <h1 class="text-3xl font-bold">{{ $heading }}</h1>
    <p class="mt-2">{{ $slot }}</p>
</div>
