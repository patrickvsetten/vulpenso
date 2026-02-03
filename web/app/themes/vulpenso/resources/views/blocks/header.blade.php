<section class="relative py-0 mt-4 overflow-clip mx-4 rounded-2xl md:rounded-3xl">
  <div class="container relative z-20">
    <div @class([
      'relative -mx-4 md:mx-0',
      'min-h-[60vh] md:h-[80vh] lg:h-[calc(100vh-2rem)] max-h-[60rem] flex items-center justify-center pt-40 pb-24 md:pt-40 lg:pt-48' => $page === 'home',
    ])>
      <div @class([
        'relative z-20',
        'pb-28 pt-48 md:pb-40 md:pt-32 lg:pt-40 xl:pb-48 xl:pt-56 2xl:pb-56 2xl:pt-64 w-full text-center max-w-2xl mx-auto' => $page === 'subpage',
        'max-w-xs md:max-w-3xl text-white flex flex-col items-center text-center gap-4 lg:gap-8' => $page === 'home',
      ])>
        <x-content.subtitle
          :subtitle="$subtitle"
          :contentItems="$content_items"
          background="image"
        />
        @if($page === 'home')
          <h1 data-rotating-title class="rotating-text__heading text-white">
            {{ $rotating_text_prefix ?? 'Service en onderhoud voor je' }}
            <div data-rotating-words="{{ $rotating_text_words ?? 'CV-ketel, warmtepomp, airco, ventilatie, melders' }}" class="rotating-text__highlight font-medium text-primary">{{ explode(',', $rotating_text_words ?? 'CV-ketel')[0] }}</div>
            {{ $rotating_text_suffix ?? 'in topconditie' }}
          </h1>
        @else
          <x-content.title
            :title="$title"
            :heading="$heading"
            headingWeight="light"
            background="image"
            :contentItems="$content_items"
            class="leading-[1.25] text-4xl lg:text-5xl xl:text-6xl"
          />
        @endif
        <x-content.text
          :content="$content"
          background="image"
          :contentItems="$content_items"
          class="xl:max-w-xl"
        />
        <div class="flex flex-row items-center justify-center gap-2 md:gap-4 mt-4">
          <x-content.buttons
            :buttons="$buttons"
            :contentItems="$content_items"
            background="image"
          />
        </div>
      </div>
    </div>
  </div>
  @if($type === 'video')
    <div class="video-toggle absolute bottom-8 right-8 z-30 text-white flex items-center space-x-2">
      <div>
        <div class="flex items-end space-x-1 h-[12px]">
          <div class="bar bg-white w-px h-[2px]"></div>
          <div class="bar bg-white w-px h-[6px]"></div>
          <div class="bar bg-white w-px h-[4px]"></div>
          <div class="bar bg-white w-px h-[8px]"></div>
        </div>
      </div>
      <div class="relative cursor-pointer">
        <span class="header__play-text relative transition-opacity duration-500">Bekijk video</span>
        <span class="header__close-text absolute left-0 transition-opacity duration-500 opacity-0">Sluit video</span>
      </div>
    </div>
    <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-black opacity-30 z-10"></div>
    @if($video_source === 'file' && $video)
        <video
          autoplay
          loop
          playsinline
          muted
          no-controls
          src="{!! $video['url']!!}"
          class="w-full h-full object-cover absolute inset-0"
          id="header-video"
        ></video>
    @else
        @if($video_url)
            <video
                autoplay
                loop
                playsinline
                muted
                class="w-full h-full object-cover absolute inset-0"
                src="{!! $video_url !!}"
                id="header-video"
              ></video>
        @endif
    @endif
  @else
    <div class="absolute scale-wrapper inset-0 z-0">
      @if($background_image)
        <div class="absolute inset-0 w-full h-full bg-black/50 z-10"></div>
        <div 
          data-scroll
          data-scroll-speed="-0.05"
          class="absolute inset-0 w-full h-full z-0"
        >
          {!! wp_get_attachment_image( $background_image['ID'], isset($size), "", ["class" => "w-full h-full md:h-[calc(100%+10vh)] md:mt-[-5vh] absolute inset-0 object-cover"] ) !!}
        </div>
      @else
          <div class="absolute inset-0 bg-black text-white grid place-items-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 md:h-20 md:w-20 lg:w-32 lg:h-32">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
              </svg>
          </div>
      @endif
    @endif
  </div>
</section>
