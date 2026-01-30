@props([
  'video_type',
  'video_link' => '',
  'video_file' => '',
  'video_layout' => 'autoplay', // 'autoplay' or 'video_player'
  'placeholder' => '',
])

@php
  use App\Helpers\VideoHelper;
  $video_id = VideoHelper::generateVideoId();
@endphp


@if($video_type === 'youtube' && $video_link)
  @php $youtube_id = VideoHelper::extractVideoId($video_link, 'youtube'); @endphp
  @if($youtube_id)
    <div class="relative w-full h-full min-h-[200px]">
      @if($placeholder)
        <div id="{{ $video_id }}-placeholder" class="absolute inset-0 z-10 transition-opacity duration-500 opacity-100">
          {!! wp_get_attachment_image( $placeholder['ID'], 'full', "", ["class" => "w-full h-full object-cover"] ) !!}
        </div>
      @endif
      @if($video_layout === 'video_player')
        <div class="absolute inset-0 flex items-center justify-center z-20" id="{{ $video_id }}-play-container">
          <button id="{{ $video_id }}-play-btn" class="video-play-btn" aria-label="Play video">
            <svg class="w-8 h-8 text-gray-800" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M8 5v14l11-7z"/>
            </svg>
          </button>
        </div>
      @endif
      <div
        id="{{ $video_id }}-player"
        class="h-full w-full min-h-[200px] z-0"
        data-plyr-provider="youtube"
        data-plyr-embed-id="{{ $youtube_id }}"
        data-video-layout="{{ $video_layout }}"
        data-video-id="{{ $video_id }}"
        data-video-type="{{ $video_type }}"
      ></div>
    </div>
  @endif

@elseif($video_type === 'vimeo' && $video_link)
  @php
    // Decode HTML entities properly - handle double encoding
    $decoded_url = html_entity_decode($video_link, ENT_QUOTES, 'UTF-8');
    $decoded_url = html_entity_decode($decoded_url, ENT_QUOTES, 'UTF-8');
  @endphp
  <div class="relative w-full h-full min-h-[200px]">
    @if($placeholder)
      <div id="{{ $video_id }}-placeholder" class="absolute inset-0 z-10 transition-opacity duration-500 opacity-100">
        {!! wp_get_attachment_image( $placeholder['ID'], 'full', "", ["class" => "w-full h-full object-cover"] ) !!}
      </div>
    @endif

    @if($video_layout === 'video_player')
      <div class="absolute inset-0 flex items-center justify-center z-20" id="{{ $video_id }}-play-container">
        <button id="{{ $video_id }}-play-btn" class="plyr__control plyr__control--overlaid">
          <svg class="size-4 text-white" aria-hidden="true" focusable="false"><use xlink:href="#plyr-play"></use></svg>
        </button>
      </div>
      <div
        id="{{ $video_id }}-player"
        class="h-full w-full min-h-[200px] z-0"
        data-plyr-provider="vimeo"
        data-plyr-embed-id="{{ $video_link }}"
        data-video-layout="{{ $video_layout }}"
        data-video-id="{{ $video_id }}"
        data-video-type="{{ $video_type }}"
      ></div>
    @else
      {{-- HTML5 Video for Vimeo autoplay --}}
      <video
        autoplay
        loop
        playsinline
        muted
        class="w-full h-full object-cover absolute inset-0 z-0 min-h-[200px]"
        src="{{ $decoded_url }}"
        id="{{ $video_id }}-video"
        data-video-layout="{{ $video_layout }}"
        data-video-id="{{ $video_id }}"
        data-video-type="{{ $video_type }}"
      ></video>
    @endif
  </div>

@elseif($video_type === 'file' && $video_file)
  <div class="relative w-full h-full min-h-[200px]">
    @if($placeholder)
      <div id="{{ $video_id }}-placeholder" class="absolute inset-0 z-10 transition-opacity duration-500 opacity-100">
        {!! wp_get_attachment_image( $placeholder['ID'], 'full', "", ["class" => "w-full h-full object-cover"] ) !!}
      </div>
    @endif
    @if($video_layout === 'video_player')
      <div
      class="absolute inset-0 flex items-center justify-center z-20"
      aria-label="Toggle video"
      role="button"
      tabindex="0"
      aria-pressed="false">
        <button id="{{ $video_id }}-play-btn" class="video-play-btn">
          <svg class="w-8 h-8 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
            <path d="M8 5v14l11-7z"/>
          </svg>
        </button>
      </div>
    @endif
    <video
      @if($video_layout === 'autoplay') autoplay @endif
      loop
      playsinline
      muted
      class="w-full h-full object-cover absolute inset-0 z-0 min-h-[200px]"
      src="{{ $video_file }}"
      id="{{ $video_id }}-video"
      data-video-layout="{{ $video_layout }}"
      data-video-id="{{ $video_id }}"
      data-video-type="{{ $video_type }}"
    ></video>
  </div>
@endif

