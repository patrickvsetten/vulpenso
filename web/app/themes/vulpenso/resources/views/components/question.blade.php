@props([
    'question' => '',
    'answer' => '',
    'bg' => '',
    'isFirst' => false,
    'openFirst' => false,
    'iteration' => null,
    'backgroundColor' => '',
])

<div
    x-data="{ open: @js($isFirst && $openFirst) }"
    @click="
        if (!open) {
            $dispatch('close-others', { id: $id('faq') })
        }
        open = !open
    "
    @close-others.window="if ($event.detail.id !== $id('faq')) open = false"
    role="button"
    tabindex="0"
    :aria-pressed="open"
    @class([
      'faq__item text-base lg:text-lg p-4 md:px-8 flex items-start justify-between cursor-pointer group gap-4 md:gap-8 relative w-full border-b',
      'bg-white/3 border-white/7 text-white last:border-none', $bg === 'bg-dark',
      'bg-white border-light text-dark' => $bg === 'bg-light',
      'bg-light border-white text-dark' => $bg === 'bg-white',
    ])
>
    <div class="flex flex-col w-full">
        <div class="flex items-center justify-between gap-4 md:gap-6">
            <span class="question font-medium block list-none">
                {!! $question !!}
            </span>
            <div
              class="flex-shrink-0 overflow-hidden faq-arrow transition-all duration-300 ease-in-out transform size-12 grid place-items-center rounded-xl"
              :class="open ? 'bg-primary text-white' : '{{ $bg === 'bg-dark' ? 'bg-primary/10 text-primary' : 'bg-light text-primary' }}'"
            >
                <svg class="relative z-10 size-4 transition-transform duration-300 ease-in-out group-hover:scale-110 text-current" :class="{ 'rotate-45': open }" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 1.5V16.5M17 9H1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        <div
            x-show="open"
            x-collapse.duration.300ms
        >
            <div
                @class([
                  'prose max-w-full pt-4',
                  'text-white', $bg === 'bg-dark',
                  'text-dark' => $bg === 'bg-light' || $bg === 'bg-white',
                ])
            >
                {!! $answer !!}
            </div>
        </div>
    </div>
</div>
