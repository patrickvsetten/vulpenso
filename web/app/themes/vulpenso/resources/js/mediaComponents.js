import Plyr from 'plyr';

// Plyr configuration constants
const PLYR_CONFIG = {
  fullscreen: { enabled: true, fallback: true, iosNative: true },
  youtube: { noCookie: true, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1 },
  vimeo: { dnt: true, byline: false, title: false, portrait: false }
};

const CONTROLS = ['play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'];
const PLYR_SETTINGS = {
  seekTime: 10,
  keyboard: { focused: true, global: false },
  tooltips: { controls: true, seek: true }
};
const VIMEO_PROGRESSIVE_REGEX = /player\.vimeo\.com\/progressive_redirect/;
const VIDEO_ID_REGEX = /\/playback\/(\d+)\//;

class VideoComponent {
  constructor(element) {
    this.element = element;
    this.layout = element.dataset.videoLayout || 'autoplay';
    this.videoType = element.dataset.videoType;
    this.videoId = element.dataset.videoId;
    this.container = element.closest('[class*="relative"]');
    
    if (!this.videoId) {
      console.warn('Video component missing data-video-id');
      return;
    }
    
    this.elements = this.getElements();
    this.player = null;
    this.state = { isPlaying: false, playPromise: null, hideTimeout: null };
    
    this.init();
  }

  getElements() {
    const selector = (suffix) => this.container?.querySelector(`#${this.videoId}-${suffix}`);
    return {
      placeholder: selector('placeholder'),
      playBtn: selector('play-btn'),
      playContainer: selector('play-container'),
      controls: null // Set after player initialization
    };
  }

  init() {
    if (!this.element || !this.videoId) return;

    const hasPlyrProvider = this.element.hasAttribute('data-plyr-provider');
    const isVideo = this.element.tagName === 'VIDEO';
    const isDivWrappedVideo = this.element.tagName === 'DIV' && this.element.querySelector('video');
    
    if (hasPlyrProvider) this.initPlyr();
    else if (isVideo) this.initHtml5Video();
    else if (isDivWrappedVideo) this.initDivWrappedVideo();
  }

  initPlyr() {
    const embedId = this.element.dataset.plyrEmbedId;
    
    // Handle Vimeo progressive URLs
    if (embedId?.includes('progressive_redirect')) {
      this.handleVimeoProgressive(embedId);
      return;
    }

    const isAutoplay = this.layout === 'autoplay';
    const config = {
      ...PLYR_CONFIG,
      ...PLYR_SETTINGS,
      autoplay: isAutoplay,
      muted: isAutoplay,
      loop: { active: isAutoplay },
      controls: this.layout === 'video_player' ? CONTROLS : []
    };

    this.player = new Plyr(this.element, config);
    this.player.on('ready', () => this.onPlayerReady());
  }

  initHtml5Video() {
    if (this.layout !== 'video_player') {
      this.setupAutoplayVideo();
      return;
    }

    const videoSrc = this.element.querySelector('source')?.src || this.element.src;
    
    // Handle Vimeo progressive URLs - but only for 'url' type, not 'external'
    // External type should be treated as normal video URL
    if (this.videoType === 'url' && VIMEO_PROGRESSIVE_REGEX.test(videoSrc)) {
      this.handleVimeoProgressive(videoSrc);
      return;
    }

    // Initialize Plyr for better controls
    const config = { ...PLYR_CONFIG, ...PLYR_SETTINGS, controls: CONTROLS, autoplay: false, muted: false, loop: { active: false } };
    this.player = new Plyr(this.element, config);
    this.player.on('ready', () => this.onPlayerReady());
  }

  initDivWrappedVideo() {
    // Find the actual video element inside the div
    const videoElement = this.element.querySelector('video');
    if (!videoElement) return;

    // Keep reference to the container div
    this.containerDiv = this.element;
    
    // Update this.element to point to the actual video element for Plyr
    this.element = videoElement;

    if (this.layout !== 'video_player') {
      this.setupAutoplayVideo();
      return;
    }

    const videoSrc = videoElement.querySelector('source')?.src || videoElement.src;
    
    // Handle Vimeo progressive URLs - but only for 'url' type, not 'external'
    // External type should be treated as normal video URL
    if (this.videoType === 'url' && VIMEO_PROGRESSIVE_REGEX.test(videoSrc)) {
      this.handleVimeoProgressive(videoSrc);
      return;
    }

    // Initialize Plyr for better controls
    const config = { ...PLYR_CONFIG, ...PLYR_SETTINGS, controls: CONTROLS, autoplay: false, muted: false, loop: { active: false } };
    this.player = new Plyr(this.element, config);
    this.player.on('ready', () => this.onPlayerReady());
  }

  setupAutoplayVideo() {
    if (this.elements.playBtn) {
      this.element.classList.add('opacity-0');
      this.setupPlayButton();
    }
    this.setupVideoEvents();
  }

  handleVimeoProgressive(url) {
    const match = url.match(VIDEO_ID_REGEX);
    if (match?.[1]) {
      console.warn(`Vimeo video ${match[1]} has privacy restrictions, using direct playback`);
    }
    
    // Initialize Plyr for Vimeo progressive URLs as HTML5 video
    const config = { ...PLYR_CONFIG, ...PLYR_SETTINGS, controls: CONTROLS, autoplay: false, muted: false, loop: { active: false } };
    this.player = new Plyr(this.element, config);
    this.player.on('ready', () => this.onPlayerReady());
  }

  onPlayerReady() {
    this.elements.controls = this.player.elements.container.querySelector('.plyr__controls');
    this.setupControls();
    this.setupEvents();
    this.setupPlayButton();
    
    // Add seeking event listeners for debugging
    this.player.on('seeking', () => {
      console.log('Video is seeking to:', this.player.currentTime);
    });
    
    this.player.on('seeked', () => {
      console.log('Video seeked to:', this.player.currentTime);
    });
    
    // Poster is disabled via CSS since we use custom placeholders
  }

  setupControls() {
    if (!this.elements.controls) return;

    const hideClasses = ['hidden', 'opacity-0', 'invisible'];
    
    if (this.layout === 'video_player') {
      this.player.elements.container.classList.add('plyr--hide-controls');
      this.elements.controls.classList.add(...hideClasses);
      this.setupHoverControls();
    } else {
      this.elements.controls.classList.add(...hideClasses, 'pointer-events-none');
    }
  }

  setupHoverControls() {
    const container = this.player.elements.container;
    const controls = this.elements.controls;
    const hideClasses = ['hidden', 'opacity-0', 'invisible'];
    
    container.addEventListener('mouseenter', () => {
      clearTimeout(this.state.hideTimeout);
      if (container.classList.contains('plyr--hide-controls')) {
        controls.classList.remove(...hideClasses);
      }
    });

    container.addEventListener('mouseleave', () => {
      // Only hide controls on mouseleave if video hasn't started playing yet
      if (container.classList.contains('plyr--hide-controls')) {
        this.state.hideTimeout = setTimeout(() => {
          controls.classList.add(...hideClasses);
        }, 2000);
      }
    });
  }

  setupPlayButton() {
    const playBtn = this.elements.playBtn || this.container?.querySelector(`#${this.videoId}-play-btn`);
    if (!playBtn) return;

    this.elements.playBtn = playBtn;
    playBtn.addEventListener('click', () => this.handlePlay());
  }

  async handlePlay() {
    if (this.state.isPlaying || this.state.playPromise) return;

    // Immediate UI feedback
    this.hideElementFast(this.elements.placeholder);
    this.hideElement(this.elements.playContainer);
    this.showElement(this.element);
    
    // Also show container div if it exists (for div-wrapped videos)
    if (this.containerDiv) {
      this.containerDiv.classList.remove('opacity-0');
      this.containerDiv.style.opacity = '1';
    }
    
    this.showLoadingIndicator();

    this.state.isPlaying = true;

    try {
      if (this.player) {
        await this.playWithPlyr();
      } else {
        await this.playNative();
      }
    } catch (error) {
      if (error.name !== 'AbortError') {
        this.handlePlayError(error);
      }
    } finally {
      this.resetPlayState();
    }
  }

  async playWithPlyr() {
    if (this.layout === 'video_player') {
      this.player.muted = false;
      this.player.elements.container.classList.remove('plyr--hide-controls');
      
      // Also remove from container div if it exists (for div-wrapped videos)
      if (this.containerDiv) {
        this.containerDiv.classList.remove('plyr--hide-controls');
      }
      
      // Find controls element if not already set
      if (!this.elements.controls) {
        this.elements.controls = this.player.elements.container.querySelector('.plyr__controls');
      }
      
      // Show controls with delay to ensure DOM is ready and clear any hide timeouts
      clearTimeout(this.state.hideTimeout);
      setTimeout(() => {
        if (this.elements.controls) {
          this.elements.controls.classList.remove('hidden', 'opacity-0', 'invisible');
        }
      }, 200);
    }
    
    this.state.playPromise = this.player.play();
    await this.state.playPromise;
  }

  async playNative() {
    if (this.layout === 'video_player') {
      this.element.muted = false;
    }

    this.state.playPromise = this.element.play();
    
    this.state.playPromise.catch(error => {
      if (this.element.readyState < 2) {
        this.loadAndRetry();
      } else {
        this.handlePlayError(error);
      }
    });
  }

  loadAndRetry() {
    this.element.load();
    
    const loadPromise = new Promise((resolve) => {
      const timeout = setTimeout(resolve, 1000);
      const onReady = () => {
        clearTimeout(timeout);
        ['loadeddata', 'canplay'].forEach(event => 
          this.element.removeEventListener(event, onReady)
        );
        resolve();
      };
      
      ['loadeddata', 'canplay'].forEach(event => 
        this.element.addEventListener(event, onReady)
      );
    });
    
    loadPromise.then(() => {
      this.element.play().catch(err => this.handlePlayError(err));
    });
  }

  setupEvents() {
    if (!this.player) return;

    this.player.on('ended', () => this.onVideoEnded());

    if (this.layout === 'autoplay') {
      this.player.on('ready', () => setTimeout(() => this.fadeOut(this.elements.placeholder), 200));
      this.player.on('play', () => setTimeout(() => this.fadeOut(this.elements.placeholder), 100));
      setTimeout(() => this.fadeOut(this.elements.placeholder), 4000);
    }
  }

  setupVideoEvents() {
    if (this.layout === 'video_player') {
      this.element.addEventListener('ended', () => this.onVideoEnded());
      this.element.addEventListener('play', () => this.state.isPlaying = true);
      this.element.addEventListener('pause', () => this.state.isPlaying = false);
    } else if (this.layout === 'autoplay') {
      ['loadeddata', 'play'].forEach(event => 
        this.element.addEventListener(event, () => this.fadeOut(this.elements.placeholder))
      );
    }
  }

  onVideoEnded() {
    this.resetPlayState();
    this.showElement(this.elements.placeholder, 'block');
    this.showElement(this.elements.playContainer);
    
    if (this.layout === 'video_player') {
      if (this.player) {
        this.player.elements.container.classList.add('plyr--hide-controls');
        this.elements.controls?.classList.add('hidden', 'opacity-0', 'invisible');
      } else {
        this.element.classList.add('opacity-0');
      }
    }
  }

  resetPlayState() {
    this.state.isPlaying = false;
    this.state.playPromise = null;
  }

  handlePlayError(error) {
    console.error('Video playback error:', error);
    
    if (!this.elements.playContainer) return;

    const errorDiv = document.createElement('div');
    errorDiv.className = 'absolute inset-0 flex items-center justify-center text-white bg-red-500/80 text-sm p-4 text-center rounded';
    errorDiv.textContent = 'Video kan niet worden afgespeeld. Probeer het later opnieuw.';
    
    const originalContent = this.elements.playContainer.innerHTML;
    this.elements.playContainer.innerHTML = '';
    this.elements.playContainer.appendChild(errorDiv);
    
    setTimeout(() => {
      this.elements.playContainer.innerHTML = originalContent;
      this.setupPlayButton();
    }, 3000);
  }

  showLoadingIndicator() {
    if (this.player || this.element.readyState >= 3) return;

    const loader = document.createElement('div');
    loader.className = 'absolute inset-0 flex items-center justify-center z-30 bg-black/20';
    loader.id = `${this.videoId}-loading`;
    loader.innerHTML = `
      <div class="w-8 h-8 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
    `;
    
    this.container?.appendChild(loader);
    
    const hideLoader = () => document.getElementById(`${this.videoId}-loading`)?.remove();
    const onPlay = () => {
      hideLoader();
      ['play', 'playing'].forEach(event => this.element.removeEventListener(event, onPlay));
    };
    
    ['play', 'playing'].forEach(event => this.element.addEventListener(event, onPlay));
    setTimeout(hideLoader, 2000);
  }

  // Utility methods
  fadeOut(element) {
    if (!element) return;
    element.classList.add('opacity-0');
    setTimeout(() => element.classList.add('hidden'), 500);
  }

  hideElementFast(element) {
    if (!element) return;
    element.style.transition = 'opacity 0.1s ease-out';
    element.classList.add('opacity-0');
    setTimeout(() => {
      element.classList.add('hidden');
      element.style.display = 'none';
    }, 100);
  }

  showElement(element, display = 'flex') {
    if (!element) return;
    element.classList.remove('opacity-0', 'hidden');
    element.style.display = display;
    if (element === this.element) {
      element.style.opacity = '1';
    }
  }

  hideElement(element) {
    if (!element) return;
    element.style.display = 'none';
    element.classList.add('hidden');
  }
}

/**
 * Initialize all video components
 */
export const initVideoComponents = () => {
  // Initialize Plyr providers (YouTube, Vimeo)
  document.querySelectorAll('[data-plyr-provider]').forEach(element => {
    new VideoComponent(element);
  });

  // Initialize HTML5 videos
  document.querySelectorAll('video[data-video-layout]').forEach(element => {
    new VideoComponent(element);
  });

  // Initialize div-wrapped videos (external video player layout)
  document.querySelectorAll('div[data-video-layout]').forEach(element => {
    new VideoComponent(element);
  });
};

// Backward compatibility exports
export const initVideoBlocks = initVideoComponents;
export const initPlyrPlayers = initVideoComponents;
export const initHtml5Videos = initVideoComponents; 