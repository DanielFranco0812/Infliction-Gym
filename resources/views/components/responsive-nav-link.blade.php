@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-red-500 bg-white/5 py-3 ps-3 pe-4 text-start text-base font-semibold text-white focus:outline-none focus:text-white transition duration-150 ease-in-out'
            : 'block w-full border-l-4 border-transparent py-3 ps-3 pe-4 text-start text-base font-medium text-gray-400 hover:border-white/20 hover:bg-white/5 hover:text-white focus:outline-none focus:text-white transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
