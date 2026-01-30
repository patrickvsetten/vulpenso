@props(['url'])

<div
  data-whatsapp-modal-status="not-active"
  data-whatsapp-modal="{{ $url }}"
  class="whatsapp-modal fixed inset-0 z-[100] flex items-center justify-center pointer-events-none"
>
  <div
    data-whatsapp-modal-toggle
    class="whatsapp-modal__overlay absolute inset-0 bg-black/40 pointer-events-auto cursor-pointer"
  ></div>
  <div class="whatsapp-modal__card relative flex flex-col items-center gap-6 bg-white rounded-3xl w-[26rem] max-w-[90vw] px-12 pt-20 pb-14 pointer-events-auto">
    <div data-whatsapp-modal-qr-canvas class="size-32"></div>
    <div class="flex flex-col items-center gap-4 pt-2">
      <h2 class="text-center text-3xl font-semibold leading-none">WhatsApp ons</h2>
      <p class="text-center text-base text-black/50 max-w-xs leading-relaxed">Scan de QR-code om met ons te chatten via je smartphone.</p>
    </div>
    <x-content.button
      data-whatsapp-modal-link
      href="#"
      target="_blank"
      style="primary"
      title="of chat via desktop"
    />
    <button data-whatsapp-modal-toggle class="absolute top-6 right-6 flex items-center justify-center cursor-pointer">
      <div class="group relative z-10 size-8 border-[1.5px] ml-4 border-black flex-shrink-0 rounded-lg grid place-items-center">
        <svg class="stroke-current size-3 transition-transform duration-300 ease-in-out rotate-45 group-hover:-rotate-45" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M7 1V13M13 7H1" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
      </div>
    </button>
  </div>
</div>
