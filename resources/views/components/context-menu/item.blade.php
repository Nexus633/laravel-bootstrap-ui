@props([
    'label' => null,
    'divider' => null,
    'icon' => null,
    'danger' => false,
    'action' => null,
    'dispatch' => null,
    'confirm' => false,
    'confirmText' => __('bs::bootstrap-ui.context-menu.confirm'),
    'params' => []
])

@php
    use Illuminate\View\ViewException;
    if(!$divider){
        if(!$label){
            throw new ViewException('Das Attribut "label" fehlt für das Context-Menu Item.');
        }
    }

    $textColorClass = '';
    if($danger){
        $textColorClass = 'text-danger';
    }
@endphp

@if($divider)
    <div class="dropdown-divider"></div>
@else
    <a href="#"
       class="dropdown-item cursor-pointer"
       @click.prevent="
            close(); {{-- Menü schließen --}}

            {{-- Fall 1: Modal Bestätigung --}}
            @if($dispatch)
                @if($params)
                    $wire.dispatch('{{ $dispatch }}', @js($params));
                @else
                    $wire.dispatch('{{ $dispatch }}');
                @endif
                return;
            @endif

            {{-- Fall 2: Browser Alert Bestätigung --}}
            @if($confirm)
                if(!confirm('{{ $confirmText }}')) return;
            @endif



            {{-- Fall 3: Sofortige Ausführung (wenn kein Modal) --}}
            @if($action)
                {{-- Spread Operator (...) nutzen, um Parameter sauber an die Funktion zu übergeben --}}
                $wire.{{ $action }}(@js($params))
            @endif
       "
    >
        @if($icon)
            <x-bs::icon name="{{ $icon }}" :variant="$danger ? 'danger' : ''" class="me-2"/>
        @endif
        <span class="{{ $textColorClass }}">
            {{ $label }}
        </span>
    </a>
@endif
