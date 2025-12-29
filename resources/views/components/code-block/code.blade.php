@props([
    'height' => null,
    'language' => 'blade',
    'lineVariant' => 'warning'
])

@php
    if($lineVariant) $lineVariant = 'text-' . $lineVariant;
@endphp

<div class="p-0 code-block-container bg-body-tertiary position-relative">
    <pre
        class="m-0 text-body d-flex overflow-auto code-block-pre"
        style="@if($height) max-height: {{ $height }}; @endif"
    ><div
        x-show="showLineNumbers"
        class="flex-shrink-0 text-end pe-2 ps-3 py-3 border-end border-secondary-subtle text-secondary user-select-none bg-body-tertiary code-block-line-numbers"
        aria-hidden="true"
    ><template x-for="i in lineCount" :key="i"><div x-text="i" :class="isMarked(i) ? '{{ $lineVariant }} fw-bold opacity-100' : 'opacity-75'"></div></template></div><code
            class="hljs language-{{ $language }} font-monospace bg-transparent flex-grow-1 ps-3 py-3 pe-3 code-block-template"
            x-html="currentHtml"
    ></code></pre>
</div>
