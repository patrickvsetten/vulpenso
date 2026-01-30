@props([
    'title' => '',
    'permalink' => '',
    'thumbnail' => '',
    'date' => '',
    'category' => '',
])

<div class="group relative bg-white rounded-2xl p-4 pb-8">
    @if ($permalink)
        <a href="{{ $permalink }}" class="w-full h-full absolute inset-0 z-20 rounded-2xl"></a>
    @endif
    <div class="relative overflow-hidden aspect-[4/3] rounded-xl">
        <div class="grid place-items-center transition-all duration-500 bg-white text-primary h-full">
            @include('components.skeletons.image')
            @if ($thumbnail)
              <div class="absolute inset-0 flex w-full h-full ease-in-out duration-300 group-hover:scale-105">
                {!! wp_get_attachment_image( $thumbnail, 'large', false, ["class" => "absolute inset-0 w-full h-full object-cover object-center"] ) !!}
              </div>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
            @endif
        </div>
        @if ($category)
            <span class="absolute top-4 left-4 z-10 bg-dark/70 backdrop-blur-sm border border-white/5 text-white text-sm font-medium px-4 py-1.5 rounded-lg isolate will-change-transform [transform:translate3d(0,0,0)]">
                {!! $category !!}
            </span>
        @endif
    </div>
    <div class="p-4 pt-8 flex flex-col gap-4">
        @if ($date)
          <p class="text-sm text-dark/50">{{ $date }}</p>
        @endif
        <x-content.title
          :title="$title"
          background="bg-white"
          heading="h4"
          :split="false"
        />
        <div class="mt-4">
          <x-arrow-button type="outline" />
        </div>
    </div>
</div>
