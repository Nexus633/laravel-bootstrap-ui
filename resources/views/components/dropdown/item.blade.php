@props([
    'icon' => null,
    'href' => null,
    'active' => false,
    'disabled' => false,
    'danger' => false, // Für "Löschen" Aktionen rot färben
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $ui = BootstrapUi::make();
    $ui->addClass('dropdown-item', 'd-flex', 'align-items-center')
       ->addClassWhen($active, 'active')
       ->addClassWhen($disabled, 'disabled')
       ->addClassWhen($danger, 'text-danger');

    $icon = Icon::toClass($icon);
    $tag = $href ? 'a' : 'button';
@endphp

<li>
    <{{ $tag }}
        @if($href) href="{{ $href }}" @else type="button" @endif
        {{ $attributes->class($ui->getClasses()) }}
    >
        @if($icon)
            <x-bs::icon :name="$icon" class="me-2 opacity-75" />
        @endif

        <span>{{ $slot }}</span>
    </{{ $tag }}>
</li>
