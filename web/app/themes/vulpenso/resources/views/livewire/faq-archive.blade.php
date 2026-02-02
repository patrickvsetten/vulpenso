<div
    x-data="{
        shouldScrollToTop: false,
        requestScrollToTop() {
            this.shouldScrollToTop = true;
        },
        handleMorphUpdate() {
            if (this.shouldScrollToTop) {
                this.$el.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                this.shouldScrollToTop = false;
            }
        }
    }"
    x-on:livewire:morph-updated.window="$nextTick(() => handleMorphUpdate())"
    class="faq-archive flex items-start flex-col"
>

    {{-- Mobile Category Filter (horizontal scroll) --}}
    <div class="lg:hidden mb-8 -mx-4 px-4 overflow-x-auto scrollbar-hide">
        <div class="flex gap-2 pb-2">
            <button
                wire:click="setCategory(null)"
                x-on:click="requestScrollToTop()"
                @class([
                    'px-5 py-3 rounded-lg font-bold text-sm whitespace-nowrap transition-all duration-300 cursor-pointer flex-shrink-0',
                    'bg-yellow text-primary' => $activeCategory === null,
                    'bg-white/10 text-white hover:bg-white hover:text-primary' => $activeCategory !== null,
                ])
            >
                Alle categorieën
            </button>
            @foreach($categories as $category)
                <button
                    wire:click="setCategory('{{ $category['slug'] }}')"
                    x-on:click="requestScrollToTop()"
                    @class([
                        'px-5 py-3 rounded-lg font-bold text-sm whitespace-nowrap transition-all duration-300 cursor-pointer flex-shrink-0',
                        'bg-yellow text-primary' => $activeCategory === $category['slug'],
                        'bg-white/10 text-white hover:bg-white hover:text-primary' => $activeCategory !== $category['slug'],
                    ])
                >
                    {!! $category['name'] !!}
                    <span class="opacity-60 ml-1">({{ $category['count'] }})</span>
                </button>
            @endforeach
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8 lg:gap-12 items-start w-full">
        {{-- Desktop Sidebar Navigation --}}
        <aside class="hidden lg:block relative p-4 md:p-6 lg:p-8 rounded-lg border border-white/7 bg-white/3 text-white">
            {{-- Search Bar --}}
            <div class="mb-8 md:mb-12 flex flex-col">
                <div class="relative max-w-2xl">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Zoek je vraag..."
                        class="w-full px-6 py-4 pl-14 bg-white border-0 rounded-lg text-primary text-lg placeholder:text-primary/40 focus:ring-2 focus:ring-yellow focus:outline-none"
                    >
                    <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-6 h-6 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    @if($search)
                        <button
                            wire:click="$set('search', '')"
                            class="absolute right-5 top-1/2 -translate-y-1/2 text-primary/40 hover:text-primary transition-colors cursor-pointer"
                        >
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
        
                </div>
            <nav>
                <p class="mb-4 text-white/40 text-sm">Categorieën</p>
                <ul class="flex flex-col gap-2">
                    <li>
                        <button
                            wire:click="setCategory(null)"
                            x-on:click="requestScrollToTop()"
                            @class([
                                'flex items-center gap-4 w-full text-left px-4 py-3 rounded-lg transition-all duration-300 cursor-pointer font-bold',
                                'bg-primary/10 text-primary' => $activeCategory === null && !$search,
                                'text-white hover:bg-primary/5 hover:text-primary' => $activeCategory !== null || $search,
                            ])
                        >
                            <span>Alle vragen</span>
                            <div @class([
                                'ml-auto size-2 rounded-full bg-current transition-all duration-500',
                                'opacity-100 scale-100' => $activeCategory === null && !$search,
                                'opacity-0 scale-0' => $activeCategory !== null || $search,
                            ])></div>
                        </button>
                    </li>
                    @foreach($categories as $category)
                        <li>
                            <button
                                wire:click="setCategory('{{ $category['slug'] }}')"
                                x-on:click="requestScrollToTop()"
                                @class([
                                    'flex items-center gap-4 w-full text-left px-4 py-3 rounded-lg transition-all duration-300 cursor-pointer font-bold',
                                    'bg-primary/10 text-primary' => $activeCategory === $category['slug'],
                                    'text-white hover:bg-primary/5 hover:text-primary' => $activeCategory !== $category['slug'],
                                ])
                            >
                                <span>{!! $category['name'] !!}</span>
                                <div @class([
                                    'ml-auto size-2 rounded-full bg-current transition-all duration-500',
                                    'opacity-100 scale-100' => $activeCategory === $category['slug'],
                                    'opacity-0 scale-0' => $activeCategory !== $category['slug'],
                                ])></div>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </aside>

        {{-- Main Content --}}
        <main class="lg:col-span-2 min-w-0 w-full" wire:loading.class="opacity-50">
            @forelse($groupedFaqs as $groupIndex => $group)
                <section
                    data-faq-category="{{ $group['category']['slug'] }}"
                    class="mb-12 last:mb-0"
                    wire:key="category-{{ $group['category']['slug'] }}"
                >
                    {{-- Category Header --}}
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-primary font-bold text-lg">
                                {!!count($group['faqs']) !!}
                            </span>
                        </div>
                        <div>
                            <h2 class="text-white text-2xl font-bold">
                                {!! $group['category']['name'] !!}
                            </h2>
                            <p class="text-white/40 text-sm">
                                {!! count($group['faqs']) !!} {{ count($group['faqs']) === 1 ? 'vraag' : 'vragen' }}
                            </p>
                        </div>
                    </div>

                    {{-- FAQ Items --}}
                    <div class="rounded-lg overflow-hidden border border-white/7">
                        @foreach($group['faqs'] as $index => $faq)
                            <x-question
                                wire:key="faq-{{ $faq['id'] }}"
                                :question="$faq['title']"
                                :answer="$faq['content']"
                                bg="bg-dark"
                                :isFirst="$index === 0"
                                :openFirst="$groupIndex === 0 && $index === 0"
                                :iteration="$index"
                            />
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="w-20 h-20 rounded-3xl bg-white/10 flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-white text-xl font-bold mb-2">Geen vragen gevonden</h3>
                        <p class="text-white/50 mb-6">
                            @if($search)
                                Er zijn geen vragen gevonden voor "{{ $search }}". Probeer andere zoektermen.
                            @else
                                Er zijn nog geen veelgestelde vragen beschikbaar.
                            @endif
                        </p>
                        @if($hasFilters)
                            <button
                                wire:click="clearFilters"
                                class="inline-flex items-center gap-2 bg-yellow text-primary px-6 py-3 rounded-lg font-bold hover:bg-yellow/90 transition-colors cursor-pointer"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Filters wissen
                            </button>
                        @endif
                    </div>
                </div>
            @endforelse
        </main>
    </div>

    {{-- Loading Indicator --}}
    <div wire:loading.delay class="fixed bottom-8 right-8 bg-yellow text-primary px-5 py-3 rounded-lg font-bold z-50 flex items-center gap-3">
        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Laden...
    </div>
</div>
