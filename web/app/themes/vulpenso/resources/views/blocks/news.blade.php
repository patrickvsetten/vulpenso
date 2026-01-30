<x-section
  :id="$id"
  :pt="$pt"
  :pb="$pb"
  background_color="bg-dark"
>
  <div @class([
    $background_color => $background_color && $background_color !== 'bg-dark',
    'py-12 md:py-16 lg:py-20 xl:py-24 relative overflow-clip lg:mx-4 rounded-xl md:rounded-3xl' => $background_color && $background_color !== 'bg-dark',
  ])>
    <div class="container">
      <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8 md:mb-12">
        <div class="flex flex-col gap-2" data-reveal-group>
          <x-content.subtitle
            :subtitle="$subtitle"
            :background="$background_color"
            :contentItems="$content_items"
          />
          <x-content.title
            :title="$title"
            :heading="$heading"
            split="false"
            :background="$background_color"
            :contentItems="$content_items"
          />
        </div>
        @if ($archive_link)
          <div class="flex-shrink-0 flex justify-start" data-reveal-group>
            <x-content.button
              type="primary"
              :href="$archive_link['url']"
              :target="$archive_link['target'] ?? '_self'"
              :title="$archive_link['title']"
            />
          </div>
        @endif
      </div>

      @if ($posts && count($posts) > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" data-reveal-group>
          @foreach ($posts as $post)
            <x-cards.news
              :title="$post['title']"
              :date="$post['date']"
              :category="$post['category']"
              :thumbnail="$post['thumbnail']"
              :permalink="$post['permalink']"
            />
          @endforeach
        </div>
      @else
        <p class="text-gray-500">Nog geen nieuwsberichten beschikbaar.</p>
      @endif
    </div>
  </div>
</x-section>
