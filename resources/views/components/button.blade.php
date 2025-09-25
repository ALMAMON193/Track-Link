@props([
    'type' => 'submit',
    'color' => 'indigo',
    'icon' => null,
])

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => "flex items-center justify-center px-4 py-2 lg:py-2.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors whitespace-nowrap"
    ]) }}
>
    <i class="fas fa-{{ $icon }} mr-2.5 text-lg"></i>
    {{ $slot }}
</button>
