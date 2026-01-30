@props([
    'current' => 1,
    'pages' => 1,
    'type' => 'default',
])

@if($pages > 1)
    @if($type == 'compact')
        @php
            $arrowClass = "size-10 md:size-12 rounded-xl bg-yellow grid place-items-center cursor-pointer transition duration-300 hover:bg-light";
            $pageNumberClass = "size-10 md:size-12 rounded-xl bg-white grid place-items-center transition duration-300 hover:bg-light";
            $currentPageNumberClass = "size-10 md:size-12 rounded-xl bg-yellow text-black grid place-items-center transition duration-300";
        @endphp
        <div {!! $attributes->merge(['class' => 'col-span-full w-full']) !!}>
            <div class="inline-flex justify-center items-center gap-2 md:gap-4 p-2 bg-white rounded-2xl">
                <div @class(['opacity-50 pointer-events-none' => $current <= 1])>
                    {{-- @if($current != 1) --}}
                        <div wire:click="previousPage" @click="$dispatch('reset-scroll')" @class([$arrowClass])>
                            <x-icon-arrow-left class="size-3" />
                        </div>
                    {{-- @endif --}}
                </div>
                <div class="bg-white grid place-items-center h-10 md:h-12 rounded-xl px-4">
                    <span class="text-base md:text-lg">{{ $current }} / {{ $pages }}</span>
                </div>
                <div @class(['opacity-50 pointer-events-none' => $current >= $pages])>
                    {{-- @if($current < $pages) --}}
                        <div wire:click="nextPage" @click="$dispatch('reset-scroll')" @class([$arrowClass])>
                            <x-icon-arrow-right class="size-3" />
                        </div>
                    {{-- @endif --}}
                </div>
            </div>
        </div>
    @else
        @php
            $arrowClass = "size-10 md:size-12 rounded-xl bg-white grid place-items-center cursor-pointer transition duration-300 hover:bg-yellow";
            $pageNumberClass = "size-10 md:size-12 rounded-xl bg-white grid place-items-center transition duration-300";
            $currentPageNumberClass = "size-10 md:size-12 rounded-xl bg-yellow text-black grid place-items-center transition duration-300";
        @endphp
        <div {!! $attributes->merge(['class' => 'col-span-full mx-auto']) !!}>
            <div class="w-full inline-flex justify-center gap-2 md:gap-4 items-center">
                <div @class(['opacity-50 pointer-events-none' => $current <= 1])>
                    {{-- @if($current != 1) --}}
                        <button wire:click="previousPage" @click="$dispatch('reset-scroll')" @class([$arrowClass])>
                            <x-icon-arrow-left class="size-3" />
                        </button>
                    {{-- @endif --}}
                </div>
                <div class="bg-white rounded-2xl p-2 flex gap-1">
                    
                    @if($current > 3)
                        <button wire:click="gotoPage(1)" @click="$dispatch('reset-scroll')" @class([$pageNumberClass, 'hover:bg-light focus:bg-yellow'])>
                            1
                        </button>
                    @endif
                    @if($current > 4)
                        <span @class([$pageNumberClass, 'select-none'])>...</span>
                    @endif
                    @foreach(range(1, $pages) as $i)
                        @if($i >= $current - 2 && $i <= $current + 2)
                            <span wire:key="paginator-page{{ $i }}">
                                @if ($i == $current)
                                    <span aria-current="page" @class([$currentPageNumberClass])>{{ $i }}</span>
                                @else
                                    <button wire:click="gotoPage({{ $i }})" @click="$dispatch('reset-scroll')" @class([$pageNumberClass, 'hover:bg-light focus:bg-yellow'])>
                                        {{ $i }}
                                    </button>
                                @endif
                            </span>
                        @endif
                    @endforeach
                    @if($current < $pages - 3)
                        <span @class([$pageNumberClass, 'select-none'])>...</span>
                    @endif
    
                    @if($current < $pages - 2)
                        <button wire:click="gotoPage({{$pages}})" @click="$dispatch('reset-scroll')" @class([$pageNumberClass, 'hover:bg-light focus:bg-yellow'])>
                            {{ $pages }}
                        </button>
                    @endif
                </div>
                <div @class(['opacity-50 pointer-events-none' => $current >= $pages])>
                    {{-- @if($current < $pages) --}}
                        <button wire:click="nextPage" @click="$dispatch('reset-scroll')" @class([$arrowClass])>
                            <x-icon-arrow-right class="size-3" />
                        </button>
                    {{-- @endif --}}
                </div>
            </div>
        </div>
    @endif
@endif
