{{--
  Template Name: Single landingspages
--}}

@extends('layouts.app')

@section('content')

<section class="relative text-white">
  <div class="container ">
    <div class="relative z-30">
        <div class="grid gap-8 md:gap-12 grid-cols-1 md:grid-cols-3 items-start">
          <div class="md:col-span-2 prose lg:prose-lg prose-invert prose-strong:text-white prose-headings:text-white prose-headings:font-medium prose-em:text-sm prose-em:leading-[1] text-white prose-h1:text-6xl">
            <h1 class="mb-0">{!! $title !!}</h1>
            @php the_content() @endphp
          </div>
          <article class="sticky top-32 md:col-span-1">
            <div class="cta-card grid h-full relative rounded-2xl md:rounded-3xl bg-white/3 border text-white border-white/7">
              @if($cta_image)
                <div class="p-4 pb-0">
                  <div class="relative overflow-hidden aspect-[4/3] rounded-xl">
                    {!! wp_get_attachment_image($cta_image['ID'], 'medium', false, ['class' => 'absolute inset-0 w-full h-full object-cover']) !!}
                  </div>
                </div>
              @endif
              <div class="p-6 xl:p-8 flex flex-col gap-4">
                @if($cta_title)
                  <x-content.title
                    heading="h3"
                    :title="$cta_title"
                    background="bg-dark"
                    class="text-xl md:text-2xl"
                  />
                @endif
                @if(count($cta_links) > 0)
                  <div class="flex flex-col divide-y divide-white/10">
                    @foreach($cta_links as $link)
                      @if(empty($link['link']['url'])) @continue @endif
                      <a
                        href="{{ $link['link']['url'] }}"
                        target="{{ $link['link']['target'] ?: '_self' }}"
                        class="group inline-flex items-center justify-between gap-4 py-3 md:py-4 text-sm lg:text-base font-bold leading-relaxed text-white"
                      >
                        <span>{!! $link['link']['title'] !!}</span>
                        <x-arrow-button size="xs" type="outline" />
                      </a>
                    @endforeach
                  </div>
                @endif
              </div>
            </div>
          </article>  
        </div>
    </div>
  </div>
</section>

@endsection
