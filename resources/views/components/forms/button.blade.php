@props(['style' => 'default'])

@php
    $baseClasses = 'text-center text-white bg-primary border border-primary hover:bg-transparent hover:text-primary transition duration-300';

    $styles = [
        'default' => 'py-3 px-4 rounded-md font-medium',
        'wide'    => 'block w-full py-2 rounded uppercase font-roboto font-medium',
    ];

    $classes = $styles[$style];
@endphp

<button type="submit" {{ $attributes->merge(['class' => $baseClasses . ' ' . $classes]) }}>
    {{ $slot }}
</button>
