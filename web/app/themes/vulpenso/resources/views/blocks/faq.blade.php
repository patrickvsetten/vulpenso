<x-section
  :id="$id"
  :pt="$pt"
  :pb="$pb"
  background_color="bg-dark"
>
  <div @class([
    $background_color => $background_color && $background_color !== 'bg-dark',
    'py-12 md:py-16 lg:py-20 xl:py-24 relative overflow-clip lg:mx-4 rounded-xl md:rounded-3xl' => $background_color !== 'bg-dark',
  ])> 
    <div class="container">
        @if($input === 'custom')
            <div class="grid gap-4 max-w-5xl mx-auto" x-data="{
                closeOthers(id) {
                    $dispatch('close-others', { id })
                }
            }">
                <div data-reveal-group class="flex-col items-start flex gap-4">
                  <x-content.subtitle
                    :subtitle="$subtitle"
                    :background="$background_color"
                    :content-items="$content_items"
                  />
                  <x-content.title
                    :title="$title"
                    :heading="$heading"
                    class="max-w-xl xl:text-3xl"
                    :background="$background_color"
                    :content-items="$content_items"
                  />
                  <x-content.text
                    :content="$content"
                    class="xl:max-w-xl lg:prose-lg prose"
                    :background="$background_color"
                    :content-items="$content_items"
                  />
                  <div class="flex justify-start">
                    <x-content.buttons
                      :buttons="$buttons"
                      :content-items="$content_items"
                    />
                  </div>
                </div>
                <div data-reveal-group class="flex flex-col items-start w-full mt-4 md:mt-8 lg:mt-12">
                    @foreach ($faqs as $index => $item)
                        @include('components.question', [
                            'question' => $item['question'],
                            'answer' => $item['answer'],
                            'bg' => $background_color,
                            'counting' => true,
                            'index' => $index,
                            'iteration' => $loop->iteration,
                            'isFirst' => $loop->first,
                        ])
                    @endforeach
                </div>
            </div>
        @elseif($input === 'all')
          <div class="md:grid md:grid-cols-2 gap-8 lg:gap-16" x-data="{
            closeOthers(id) {
                $dispatch('close-others', { id })
            },
            activeCategory: null,
            scrollToCategory(categoryId) {
                const element = document.getElementById('category-' + categoryId);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    this.activeCategory = categoryId;
                }
            }
          }">
            <div data-reveal-group class="md:col-span-2 lg:col-span-1 flex flex-col gap-4 items-start">
              <x-content.subtitle
                :subtitle="$subtitle"
                :content-items="$content_items"
                :background="$background_color"
              />
              <x-content.title
                :title="$title"
                :heading="$heading"
                class="max-w-xl text-xl xl:text-3xl"
                :background="$background_color"
                :content-items="$content_items"
              />
              <x-content.text
                :content="$content"
                class="xl:max-w-xl lg:prose-lg prose"
                :background="$background_color"
                :content-items="$content_items"
              />
              <div class="flex justify-start">
                <x-content.buttons
                  :buttons="$buttons"
                  :content-items="$content_items"
                />
              </div>

              <!-- Navigatie sidebar -->
              <div class="sticky top-32 w-full">
                <h3 class="text-lg font-title font-medium mb-4">Categorieën</h3>
                <nav class="flex flex-col gap-2">
                    @foreach($faq as $category_group)
                        <button
                            @click="scrollToCategory('{{ $category_group['category']->term_id }}')"
                            :class="{ 'text-primary': activeCategory === '{{ $category_group['category']->term_id }}' }"
                            class="text-left hover:text-primary transition-colors duration-200"
                        >
                            {!! $category_group['category']->name !!}
                        </button>
                    @endforeach
                </nav>
              </div>
            </div>
            <div class="flex flex-col items-start gap-4 md:gap-6 w-full mt-8 md:mt-0">
                @foreach($faq as $category_group)
                  <div id="category-{{ $category_group['category']->term_id }}" class="mb-12 last:mb-0 w-full scroll-mt-8">
                      <h2 class="text-2xl font-title font-medium mb-6">{!! $category_group['category']->name !!}</h2>
                      <div class="grid gap-4" x-data="{
                          closeOthers(id) {
                              $dispatch('close-others', { id })
                          }
                      }">
                          <div data-reveal-group class="flex flex-col items-start w-full">
                              @foreach($category_group['posts'] as $index => $item)
                                  @include('components.question', [
                                      'question' => $item->post_title,
                                      'answer' => $item->post_content,
                                      'bg' => $background_color,
                                      'isFirst' => $loop->first,
                                  ])
                              @endforeach
                          </div>
                      </div>
                  </div>
                @endforeach
            </div>
          </div> 
        @else
          <div class="flex flex-col max-w-5xl mx-auto gap-4 items-start" x-data="{
              closeOthers(id) {
                  $dispatch('close-others', { id })
              }
          }">
            <div data-reveal-group class="flex flex-col gap-4 items-start flex-wrap justify-start mb-4 md:mb-8">
              <x-content.subtitle
                :subtitle="$subtitle"
                :content-items="$content_items"
                :background="$background_color"
              />
              <x-content.title
                :title="$title"
                :heading="$heading"
                class="max-w-xl text-xl xl:text-3xl"
                :background="$background_color"
                :content-items="$content_items"
              />
            </div>
            <div 
              data-reveal-group 
              @class([
                'flex flex-col items-start w-full mt-8 md:mt-0 rounded-2xl overflow-clip relative',
                'border border-white/7', $background_color === 'bg-dark'
              ])
            >
              @foreach ($faq as $index => $item)
                  @include('components.question', [
                      'question' => $item['title'],
                      'answer' => $item['content'],
                      'bg' => $background_color,
                      'index' => $index,
                      'isFirst' => $loop->first,
                  ])
              @endforeach
            </div>
            <x-content.text
              :content="$content"
              class="mt-8 lg:prose-lg prose max-w-full"
              :background="$background_color"
              :content-items="$content_items"
            />
            <div class="flex justify-start">
              <x-content.buttons
                :buttons="$buttons"
                :content-items="$content_items"
              />
            </div>
          </div>
        @endif
    </div>
  </div>
</x-section>
