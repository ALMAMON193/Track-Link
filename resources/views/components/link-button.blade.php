@props([
    'href' => '#',
    'color' => 'blue',
    'icon' => null,
    'navigate' => false,
])

<a
    href="{{ $href }}"
    @if($navigate) wire:navigate @endif
    {{ $attributes->merge([
        'class' => "flex items-center justify-center px-4 py-2 lg:py-2.5 bg-{$color}-600 text-white text-sm rounded-lg hover:bg-{$color}-700 transition-colors whitespace-nowrap"
    ]) }}
>
    @if($icon)
        <i class="fas fa-{{ $icon }} mr-2"></i>
    @endif
    {{ $slot }}
</a>
