@props(['variant' => null])

<tr  {{ $attributes->merge(['class' => $variant ? 'table-' . $variant : null]) }}>
    {{ $slot }}
</tr>
