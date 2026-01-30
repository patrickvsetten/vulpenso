@props([
    'title' => '',
    'link' => '',
    'image' => '',
    'date' => '',
])

<div class="group relative">
    @if ($link)
        <a href="{{ $link }}" class="w-full h-full absolute inset-0 z-20"></a>
    @endif
    <div class="relative grid overflow-hidden aspect-[16/12] rounded-2xl">
        <div class="grid place-items-center transition-all duration-500 bg-white text-primary">
            @include('components.skeletons.image')
            @if ($image)
              <div class="absolute inset-0 flex w-full h-full ease-in-out duration-300 group-hover:scale-110">
                  {!! wp_get_attachment_image( $image, $size ?? 'full', false, ["class" => "absolute inset-0 w-full h-full object-cover object-center"] ) !!}
              </div>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
            @endif
        </div>
    </div>
    <div class="bg-light relative z-10 inline-block transform xl:translate-y-[-4.5rem] left-0 lg:max-w-xs pt-2 pr-10 rounded-tr-lg lg:rounded-tr-xl">
      <div class="absolute left-0 -top-5 z-10 text-light size-5">
        <svg xmins="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M-0.184601 0.18457C-0.184601 11.7046 10.4821 15.6513 15.8154 16.1846L-0.184602 16.1846L-0.184601 0.18457Z" fill="currentColor"></path></svg>
      </div>
      <div class="absolute -right-5 top-[3.25rem] z-10 text-light size-5">
        <svg xmins="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M-0.184601 0.18457C-0.184601 11.7046 10.4821 15.6513 15.8154 16.1846L-0.184602 16.1846L-0.184601 0.18457Z" fill="currentColor"></path></svg>
      </div>
      <div
          class="grid gap-1 mt-4 transition-all duration-300"
          wire:loading.class="opacity-0"
      >
        <h3 class="font-serif font-normal text-xl md:text-2xl xl:text-3xl">{!! $title !!}</h3>
        <div class="relative mt-3">
          <div class="bg-yellow size-10 grid place-items-center relative overflow-hidden rounded-xl group">
              <x-icon-arrow-right class="size-3 transition-all duration-300 transform group-hover:translate-x-[300%]" />
              <x-icon-arrow-right class="size-3 absolute top-1/2 left-1/2 transform -translate-y-1/2 translate-x-[-300%] transition-all duration-300 group-hover:-translate-x-1/2" />
          </div>
        </div>
      </div>
    </div>
    {{-- <div class="relative">
        @include('components.skeletons.text')
        <div
            class="grid gap-2 mt-4 transition-all duration-300"
            wire:loading.class="opacity-0"
        >
            <h3 class="font-serif font-semibold text-primary text-xl">{!! $title !!}</h3>
        </div>
    </div> --}}
</div>
