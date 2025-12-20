@props([
    'label',
    'value' => null,
    'href' => null,
    'target' => '_blank',
    'icon' => null,
    
    // Layout
    'vertical' => false,
    'border' => false,
    'py' => '2',

    // Grid Konfiguration für das Label (Standardwerte)
    'labelSm' => '4',
    'labelMd' => null,
    'labelLg' => '3',
    'labelXl' => null,
    'labelXxl' => null,

    // Funktion
    'copy' => false,
])

@php
    $valueId = $attributes->get('id') ?? 'kvb-' . uniqid();

    $labelSm = $attributes->get('label:sm') ?? $labelSm;
    $labelMd = $attributes->get('label:md') ?? $labelMd;
    $labelLg = $attributes->get('label:lg') ?? $labelLg;
    $labelXl = $attributes->get('label:xl') ?? $labelXl;
    $labelXxl = $attributes->get('label:xxl') ?? $labelXxl;

    $attributes = $attributes->except([
        'label:sm', 'label:md', 'label:lg', 'label:xl', 'label:xxl'
    ]);

    $classes = [];
    if($py) $classes[] = 'py-' . $py;
    if($border) $classes[] = 'border-bottom';

@endphp

<div {{ $attributes->class($classes) }}>

    @if($vertical)
        {{--
            VERTIKALER MODUS
            Hier brauchen wir kein Grid/Row, sondern stapeln einfach div-Blöcke.
            Das ist sauberer als eine Row mit 1 Spalte zu erzwingen.
        --}}
        <x-bs::text div class="mb-1" variant="body-secondary" medium truncate>
            @if($icon)
                <x-bs::icon :name="$icon" class="me-1 opacity-75"/>
            @endif
            {{ $label }}
        </x-bs::text>

        <div class="d-flex align-items-center gap-2">
            @if($href)
                <x-bs::link :href="$href" :target="$target" no-underline>{{ $value ?? $slot }}</x-bs::link>
            @else
                <x-bs::text div id="{{ $valueId }}" break>
                    {{ $value ?? $slot }}
                </x-bs::text>
            @endif
            @if($copy)
                <x-bs::button.copy
                    target="#{{ $valueId }}"
                    variant="link"
                    size="sm"
                    class="p-0 text-body-tertiary text-decoration-none align-self-center"
                    icon="copy"
                    x-bs-tooltip="'{{ __('bs::bootstrap-ui.kvb.tooltip.copy') }}'"
                />
            @endif
        </div>

    @else
        {{--
            HORIZONTALER MODUS
            Hier nutzen wir deine Row & Col Komponenten.
        --}}
        <x-bs::row>

            {{-- A. LABEL SPALTE --}}
            <x-bs::row.col
                :sm="$labelSm"
                :md="$labelMd"
                :lg="$labelLg"
                :xl="$labelXl"
                :xxl="$labelXxl"
            >
                <x-bs::text div variant="body-secondary" medium truncate>
                    @if($icon)
                        <x-bs::icon :name="$icon" class="me-1 opacity-75"/>
                    @endif
                    {{ $label }}
                </x-bs::text>
            </x-bs::row.col>

            {{-- B. VALUE SPALTE (Nimmt automatisch den Restplatz) --}}
            <x-bs::row.col class="d-flex align-items-center gap-2">
                @if($href)
                    <x-bs::link :href="$href" :target="$target" no-underline body>{{ $value ?? $slot }}</x-bs::link>
                @else
                    <x-bs::text div id="{{ $valueId }}" break>
                        {{ $value ?? $slot }}
                    </x-bs::text>
                @endif

                @if($copy)
                    <x-bs::button.copy
                        target="#{{ $valueId }}"
                        variant="link"
                        size="sm"
                        class="p-0 text-body-tertiary text-decoration-none align-self-center"
                        icon="copy"
                        x-bs-tooltip="'{{ __('bs::bootstrap-ui.kvb.tooltip.copy') }}'"
                    />
                @endif

            </x-bs::row.col>
        </x-bs::row>
    @endif
</div>
