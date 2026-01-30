@props([
    'title' => '',
    'link' => '',
    'image' => '',
    'categories' => [],
    'location' => null,
    'background_color' => 'bg-light',
])

<div class="group relative">
    @if ($link)
        <a href="{{ $link }}" class="w-full h-full absolute inset-0 z-20"></a>
    @endif
    <div class="relative grid items-end overflow-hidden aspect-[4/3] rounded-2xl">
        @if($categories)
            <div class="absolute z-20 grid place-items-start top-6 left-6">
                <div class="flex flex-wrap gap-2">
                  @foreach($categories as $category)
                    <div class="bg-white grid place-items-center font-bold size-10 rounded-xl">
                        @if(isset($category->term_id))
                            <x-lordicon
                                :src="get_field('icon', 'category_' . $category->term_id)"
                                class="size-6"
                                stroke="bold"
                                primary="#000000"
                                secondary="#000000"
                            />
                        @elseif(isset($category['id']))
                            <x-lordicon
                                :src="get_field('icon', 'category_' . $category['id'])"
                                class="size-6"
                                stroke="bold"
                                primary="#000000"
                                secondary="#000000"
                            />
                        @endif
                    </div>
                @endforeach
                </div>
            </div>
        @endif
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
        <div class="absolute grid place-items-end right-4 bottom-4 p-2">
          <div class="bg-yellow size-10 grid place-items-center relative overflow-hidden rounded-xl group">
              <x-icon-arrow-right class="size-3 transition-all duration-300 transform group-hover:translate-x-[300%]" />
              <x-icon-arrow-right class="size-3 absolute top-1/2 left-1/2 transform -translate-y-1/2 translate-x-[-300%] transition-all duration-300 group-hover:-translate-x-1/2" />
          </div>
        </div>
      </div>
      <div @class([
        'relative z-10 inline-block transform xl:translate-y-[-3.5rem] left-0 lg:max-w-sm pt-2 pr-10 rounded-tr-lg lg:rounded-tr-xl',
        $background_color => $background_color,
      ])>
        <div @class([
          'absolute left-0 -top-5 z-10 size-5',
          'text-light' => $background_color === 'bg-light',
          'text-white' => $background_color === 'bg-white',
          'text-dark' => $background_color === 'bg-dark',
        ])>
          <svg xmins="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M-0.184601 0.18457C-0.184601 11.7046 10.4821 15.6513 15.8154 16.1846L-0.184602 16.1846L-0.184601 0.18457Z" fill="currentColor"></path></svg>
        </div>
        <div @class([
          'absolute -right-5 top-9 z-10 size-5',
          'text-light' => $background_color === 'bg-light',
          'text-white' => $background_color === 'bg-white',
          'text-dark' => $background_color === 'bg-dark',
        ])>
          <svg xmins="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M-0.184601 0.18457C-0.184601 11.7046 10.4821 15.6513 15.8154 16.1846L-0.184602 16.1846L-0.184601 0.18457Z" fill="currentColor"></path></svg>
        </div>
        <div
            class="grid gap-2 mt-4 transition-all duration-300"
            wire:loading.class="opacity-0"
        >
            <h3
              data-split
              data-animate-words
              class="font-serif font-normal text-xl md:text-2xl xl:text-3xl"
            >
              {!! $title !!}
            </h3>
            <p class="text-xs opacity-50">{!! $location !!}</p>
        </div>
    </div>
</div>
