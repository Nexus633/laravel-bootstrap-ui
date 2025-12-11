@props(['href' => '#'])

<a class="navbar-brand" href="{{ $href }}" {{ $attributes }}>
    {{ $slot }}
</a>
