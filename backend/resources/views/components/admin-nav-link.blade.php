@props(['active' => false, 'href' => '#'])

@php
$classes = ($active ?? false)
            ? 'bg-gray-900 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md'
            : 'text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes])->except('href') }}>
    {{ $slot }}
</a>
