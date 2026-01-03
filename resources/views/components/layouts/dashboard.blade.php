{{-- resources/views/components/layouts/dashboard.blade.php --}}
@props([
    'title' => null,
    'editor' => config('bootstrap-ui.scripts.editor'),
    'charts' => config('bootstrap-ui.scripts.charts'),
])

<!doctype html>
<html lang="de" class="h-100" xmlns:x-bs="http://www.w3.org/1999/html">
<head>
    <title>{{ $title ?? config('app.name') }}</title>

    {{-- Deine Head Komponente (Bootstrap CSS, Meta Tags etc.) --}}
    <x-bs::head editor="{{ $editor }}"/>

    @livewireStyles
</head>
<x-bs::body>
    <x-bs::sidebar>
        <x-bs::sidebar.header>
            <x-bs::sidebar.brand name="Test App" />
        </x-bs::sidebar.header>

        <x-bs::sidebar.nav>
            <x-bs::sidebar.item icon="house" href="/test" exact>Home</x-bs::sidebar.item>
            <x-bs::sidebar.group label="Demo" icon="box" prefix="*">
                <x-bs::sidebar.item href="/" badge="2" badge:variant="warning" name="Link A" />
                <x-bs::sidebar.item href="#">Link B</x-bs::sidebar.item>
            </x-bs::sidebar.group>
        </x-bs::sidebar.nav>
        <x-bs::sidebar.spacer />
        <x-bs::sidebar.item icon="house" href="/test" exact>Home</x-bs::sidebar.item>
    </x-bs::sidebar>

    <x-bs::body.main>
        <x-bs::body.header>
            <x-bs::navbar expand="lg" bg="body" container="fluid" class="border-0 shadow-none">
                <x-bs::navbar.collapse id="mainNav">
                    <x-bs::theme-toggle :variant="false"/>
                    {{-- Linke Seite --}}
                    <x-bs::navbar.nav>
                        <x-bs::navbar.item href="/dashboard" active>Dashboard</x-bs::navbar.item>
                        <x-bs::navbar.item href="/users">Benutzer</x-bs::navbar.item>
                    </x-bs::navbar.nav>

                    {{-- Rechte Seite (User Menü) --}}
                    <x-bs::navbar.nav end>
                        {{-- Dropdown als Navbar Item (nutzt 'nav' prop!) --}}
                        <x-bs::dropdown label="Mein Profil" nav>
                            <x-bs::dropdown.item href="/settings">Einstellungen</x-bs::dropdown.item>
                            <x-bs::dropdown.divider />
                            <x-bs::dropdown.item wire:click="logout" class="text-danger">
                                Logout
                            </x-bs::dropdown.item>
                        </x-bs::dropdown>

                    </x-bs::navbar.nav>

                </x-bs::navbar.collapse>

            </x-bs::navbar>
        </x-bs::body.header>


        <x-bs::body.content>
            {{ $slot }}
        </x-bs::body.content>

    </x-bs::body.main>

    <x-bs::scripts editor="{{ $editor }}" charts="{{ $charts }}" />
    @livewireScripts
</x-bs::body>
</html>
