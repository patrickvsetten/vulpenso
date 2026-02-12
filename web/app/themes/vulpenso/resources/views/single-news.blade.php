@extends('layouts.app')

@section('content')
  {{-- Hero Header --}}
  <x-section pt="pt-0" pb="pb-0" class="mt-4 overflow-clip mx-4 rounded-2xl md:rounded-3xl">
    <div class="container relative z-20">
      <div class="pb-28 pt-48 md:pb-40 md:pt-24 lg:pt-32 xl:pb-40 xl:pt-48 2xl:pb-48 2xl:pt-56 w-full text-center max-w-2xl mx-auto">
        @if($first_category)
          <span class="inline-block bg-white/10 backdrop-blur-sm text-white text-sm font-medium px-4 py-1.5 rounded-lg mb-6">
            {!! $first_category->name !!}
          </span>
        @endif
        <x-content.title
          :title="get_the_title()"
          heading="h1"
          headingWeight="light"
          background="image"
          class="leading-[1.25] text-4xl lg:text-5xl xl:text-6xl"
        />
        <p class="text-white/60 mt-6">{{ $date }}</p>
      </div>
    </div>
    <div class="absolute inset-0 z-0">
      <div class="absolute inset-0 w-full h-full bg-black/[0.45] z-10"></div>
      @if($thumbnail_id)
        {!! wp_get_attachment_image($thumbnail_id, 'full', false, ['class' => 'w-full h-full absolute inset-0 object-cover']) !!}
      @else
        <div class="absolute inset-0 bg-dark"></div>
      @endif
    </div>
  </x-section>

  {{-- Content --}}
  <x-section>
    <div class="container">
      <article class="prose prose-lg max-w-3xl mx-auto prose-headings:text-white prose-p:text-white prose-a:text-primary prose-strong:text-white prose-li:text-white">
        @php the_content() @endphp
      </article>
    </div>
  </x-section>

  {{-- Related News --}}
  @if(count($related_posts) > 0)
    <x-section background_color="bg-dark">
      <div class="bg-light py-12 md:py-16 lg:py-20 xl:py-24 relative overflow-clip lg:mx-4 rounded-xl md:rounded-3xl">
        <div class="container">
          <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8 md:mb-12">
            <div class="flex flex-col gap-2">
              <x-content.title
                title="Gerelateerde berichten"
                heading="h2"
                :split="false"
                background="bg-light"
              />
            </div>
            <div class="flex-shrink-0 flex justify-start">
              <x-content.button
                type="primary"
                :href="get_post_type_archive_link('news')"
                title="Bekijk alle berichten"
              />
            </div>
          </div>

          <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($related_posts as $related_post)
              @php
                $related_categories = get_the_terms($related_post->ID, 'news_category');
                $related_category = ($related_categories && !is_wp_error($related_categories)) ? $related_categories[0]->name : '';
              @endphp
              <x-cards.news
                :title="$related_post->post_title"
                :date="get_the_date('j F Y', $related_post->ID)"
                :category="$related_category"
                :thumbnail="get_post_thumbnail_id($related_post->ID)"
                :permalink="get_permalink($related_post->ID)"
              />
            @endforeach
          </div>
        </div>
      </div>
    </x-section>
  @endif
@endsection
