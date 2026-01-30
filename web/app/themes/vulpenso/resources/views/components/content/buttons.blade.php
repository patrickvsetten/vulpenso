@if(isset($buttons) && $buttons)
    @foreach ($buttons as $button)
      @if($button && $button['link'])
        <x-content.button
          :href="$button['link']['url']"
          :type="$button['type']"
          :title="$button['link']['title']"
          :target="$button['link']['target']"
        >
        </x-content.button>
      @endif
    @endforeach
@endif

@if(isset($buttons_cta) && $buttons_cta)
  @foreach ($buttons_cta as $button)
    @if($button && $button['link'])
      <x-content.button
        :href="$button['link']['url']"
        :type="$button['type']"
        :title="$button['link']['title']"
        :target="$button['link']['target']"
      >
      </x-content.button>
    @endif
  @endforeach
@endif

@if(isset($item['buttons']) && $item['buttons'])
  @foreach ($item['buttons'] as $button)
    @if($button && $button['link'])
      <x-content.button
        :href="$button['link']['url']"
        :type="$button['type']"
        :title="$button['link']['title']"
        :target="$button['link']['target']"
      >
      </x-content.button>
    @endif
  @endforeach
@endif