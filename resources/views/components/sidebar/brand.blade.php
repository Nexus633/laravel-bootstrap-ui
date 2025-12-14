@props(['href' => '#', 'logo' => null, 'name' => null])

<a href="{{ $href }}" class="d-flex align-items-center text-decoration-none text-body-emphasis gap-2">
    @if($logo)
        <img src="{{ $logo }}" alt="Logo" style="height: 24px; width: auto;">
    @else
        <div class="bg-primary rounded text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 28px; height: 28px;">
            {{ substr($name ?? 'A', 0, 1) }}
        </div>
    @endif

    @if($name)
        <span class="fw-semibold tracking-tight" style="font-size: 0.95rem;">{{ $name }}</span>
    @endif
</a>
