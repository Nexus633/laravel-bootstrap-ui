@props([
    'label',
    'action',
    'icon' => null, // Icon Name (z.B. 'house')
    'description' => null,
    'keywords' => null,
    'group' => 'Aktionen',
    'params' => [],
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;

    // Wir bereiten das Payload-Paket vor
    $payload = [
        'id' => md5($label . $action . rand()),
        'label' => $label,
        'action' => $action,
        'description' => $description,
        'keywords' => $keywords,
        'params' => $params,
        // Icon Klasse direkt hier auflösen, damit JS fertig 'bi bi-...' bekommt
        'icon' => $icon ? Icon::toClass($icon) : null,
    ];
@endphp

{{--
    Unsichtbarer Container.
    Alpine "collectDeclarativeItems" sucht nach [data-cp-declarative-item].
--}}
<span
    data-cp-declarative-item
    data-group="{{ $group }}"
    data-payload="{{ json_encode($payload) }}"
    style="display: none;"
></span>
