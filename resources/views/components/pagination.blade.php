@php

    $usePaginationIcons = $this->usePaginationIcons ?? config('bootstrap-ui.pagination.use_icons', true);
    $displayPaginationSummary = $this->displayPaginationSummary ?? config('bootstrap-ui.pagination.show_summary', true);
    $displayFirstAndLastPage = $this->displayFirstAndLastPage ?? config('bootstrap-ui.pagination.show_first_and_last_page', false);
@endphp

@if ($paginator->hasPages())
    <div class="d-flex justify-content-between align-items-center">

        {{-- TEIL 1: Info Text --}}
        @if($displayPaginationSummary)
            <div class="small text-muted">
                {{ __('bs::bootstrap-ui.pagination.showing') }}
                <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                {{ __('bs::bootstrap-ui.pagination.to') }}
                <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                {{ __('bs::bootstrap-ui.pagination.of') }}
                <span class="fw-semibold">{{ $paginator->total() }}</span>
                {{ __('bs::bootstrap-ui.pagination.results') }}
            </div>
        @else
            <div></div>
        @endif

        {{-- TEIL 2: Die Links (Jetzt als Buttons für Livewire) --}}
        <nav>
            <ul class="pagination mb-0">

                {{-- PREVIOUS PAGE LINK --}}
                @if ($paginator->onFirstPage())
                    @if ($displayFirstAndLastPage)
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link" aria-hidden="true">
                                @if($usePaginationIcons) &lsaquo;&lsaquo; @else {{ __('bs::bootstrap-ui.pagination.first') }} @endif
                            </span>
                        </li>
                    @endif
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" aria-hidden="true">
                            @if($usePaginationIcons) &lsaquo; @else {{ __('bs::bootstrap-ui.pagination.previous') }} @endif
                        </span>
                    </li>
                @else
                    @if ($displayFirstAndLastPage)
                        <li class="page-item">
                            <x-bs::button pagination wire:click="gotoPage(1)" wire:loading.attr="disabled" rel="prev">
                                @if($usePaginationIcons) &lsaquo;&lsaquo; @else {{ __('bs::bootstrap-ui.pagination.first') }} @endif
                            </x-bs::button>
                        </li>
                    @endif
                    <li class="page-item">
                        <x-bs::button pagination wire:click="previousPage" wire:loading.attr="disabled" rel="prev">
                            @if($usePaginationIcons) &lsaquo; @else {{ __('bs::bootstrap-ui.pagination.previous') }} @endif
                        </x-bs::button>
                    </li>
                @endif

                {{-- PAGINATION ELEMENTS (Zahlen) --}}
                @foreach ($elements as $element)

                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <x-bs::button pagination wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled" rel="next">
                                        {{ $page }}
                                    </x-bs::button>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- NEXT PAGE LINK --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <x-bs::button pagination wire:click="nextPage" wire:loading.attr="disabled" rel="next">
                            @if($usePaginationIcons) &rsaquo; @else {{ __('bs::bootstrap-ui.pagination.next') }} @endif
                        </x-bs::button>
                    </li>
                    @if ($displayFirstAndLastPage)
                        <li class="page-item">
                            <x-bs::button pagination wire:click="gotoPage({{ $paginator->lastPage() }})" wire:loading.attr="disabled" rel="next">
                                @if($usePaginationIcons) &rsaquo;&rsaquo; @else {{ __('bs::bootstrap-ui.pagination.last') }} @endif
                            </x-bs::button>
                        </li>
                    @endif
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" aria-hidden="true">
                            @if($usePaginationIcons) &rsaquo; @else {{ __('bs::bootstrap-ui.pagination.next') }} @endif
                        </span>
                    </li>
                    @if ($displayFirstAndLastPage)
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link" aria-hidden="true">
                                @if($usePaginationIcons) &rsaquo;&rsaquo; @else {{ __('bs::bootstrap-ui.pagination.last') }} @endif
                            </span>
                        </li>
                    @endif
                @endif
            </ul>
        </nav>
    </div>
@endif
