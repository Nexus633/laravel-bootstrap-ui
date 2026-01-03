@props([
    'code' => null,
    'language' => 'blade',
    'copy' => true,
    'height' => null,
    'lineVariant' => 'warning',
    'lineNumbers' => false,
    'lineMarks' => [],
    'noCard' => false,
    'iconExpand' => 'arrows-expand',
    'iconCollapse' => 'arrows-collapse'
])

@php
    use Nexus633\BootstrapUi\Facades\CodeHighlighter;
    use Illuminate\Support\Str;

    $lineNumbers = $attributes->pluck('line:numbers', $lineNumbers);
    $lineMarks = $attributes->pluck('line:marks', $lineMarks);
    $lineVariant = $attributes->pluck('line:variant', $lineVariant);

    $iconExpand = $attributes->pluck('icon:expand', $iconExpand);
    $iconCollapse = $attributes->pluck('icon:collapse', $iconCollapse);

    $content = $code ?? (string) $slot;
    $uniqueId =  $attributes->getOrCreateId('code-');

    // Daten via Service holen
    $processed = CodeHighlighter::process($content, $language);
@endphp

<div
    class="code-block-wrapper"
    wire:ignore
    x-data="bsCodeBlock({
        initialHtml: @js($processed['highlightedCode']),
        initialRaw: @js($processed['currentRaw']),
        prettyHtml: @js($processed['jsonPrettyHtml']),
        minifiedHtml: @js($processed['jsonMinifiedHtml']),
        rawPretty: @js($processed['rawPretty']),
        rawMinified: @js($processed['rawMinified']),
        isJson: @js($processed['isJson']),
        showLineNumbers: @js($lineNumbers),
        lineMarks: @js($lineMarks)
    })"
>
    {{-- Hidden Textarea --}}
    <textarea id="raw-{{ $uniqueId }}" style="display: none;" x-text="currentRaw" aria-hidden="true"></textarea>

    {{--
        ========================================
        MODUS 1: CARD VIEW (Standard)
        Nutzung der x-bs::card Komponente
        ========================================
    --}}
    @if(!$noCard)
        <x-bs::card
            class="shadow-sm mb-3 overflow-hidden border-0"
            body="{{ false }}" {{-- WICHTIG: Body deaktivieren, damit wir p-0 nutzen können --}}
        >
            <x-slot:header>
                <div class="d-flex justify-content-between align-items-center py-1">
                    {{-- Nutzung der Text-Komponente --}}
                    <x-bs::text
                        variant="body-secondary"
                        small
                        bold
                        class="font-monospace ps-2"
                    >
                        {{ Str::upper($language) }}
                    </x-bs::text>

                    <div class="d-flex gap-2">
                        <x-bs::code-block.actions
                            :unique-id="$uniqueId"
                            :copy="$copy"
                            :floating="false"
                            :icon-expand="$iconExpand"
                            :icon-collapse="$iconCollapse"
                        />
                    </div>
                </div>
            </x-slot:header>

            {{-- Inhalt (Code) --}}
            <x-bs::code-block.code />

        </x-bs::card>
    @endif

    {{--
        ========================================
        MODUS 2: NO-CARD VIEW (Floating)
        ========================================
    --}}
    @if($noCard)
        <div class="mb-3 overflow-hidden rounded border border-secondary-subtle position-relative">
            <div class="position-absolute top-0 end-0 p-2 z-2">
                <div class="d-flex gap-1 bg-body rounded border border-secondary-subtle shadow-sm px-1 py-0" style="--bs-bg-opacity: .9;">
                    <x-bs::code-block.actions
                        :unique-id="$uniqueId"
                        :copy="$copy"
                        :floating="true"
                        :icon-expand="$iconExpand"
                        :icon-collapse="$iconCollapse"
                    />
                </div>
            </div>
            {{-- Inhalt (Code) --}}
            <x-bs::code-block.code />
        </div>
    @endif
</div>
