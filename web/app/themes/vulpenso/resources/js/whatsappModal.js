/**
 * WhatsApp Modal with QR Code
 * Uses kjua for QR code generation
 */

import kjua from 'kjua';

export async function initWhatsAppModal() {
  // Dynamic import for CommonJS module
  // const kjuaModule = await import('kjua');
  // const kjua = kjuaModule.default || kjuaModule;

  const modal = document.querySelector('[data-whatsapp-modal]');
  if (!modal) return;

  // Get WhatsApp URL from data attribute
  const url = (modal.getAttribute('data-whatsapp-modal') || '').trim();
  if (!url) return;

  // Extract phone number from URL and create wa.me link
  const phoneMatch = url.match(/[\d]+/);
  const phoneNumber = phoneMatch ? phoneMatch[0] : '';
  const waLink = phoneNumber ? `https://wa.me/${phoneNumber}` : url;

  // Generate an SVG QR via kjua
  const svg = kjua({
    text: waLink,
    render: 'svg',
    crisp: true,
    minVersion: 1,
    ecLevel: 'M',
    size: 540,
    fill: '#000000',
    back: '#FFFFFF',
    rounded: 0
  });

  // Let CSS control sizing
  svg.removeAttribute('width');
  svg.removeAttribute('height');
  svg.removeAttribute('style');

  // Insert into canvas placeholder(s)
  modal.querySelectorAll('[data-whatsapp-modal-qr-canvas]').forEach((placeholder, i) => {
    const node = i === 0 ? svg : svg.cloneNode(true);
    placeholder.appendChild(node);
  });

  // Add the link to all elements with [data-whatsapp-modal-link] attribute
  document.querySelectorAll('[data-whatsapp-modal-link]').forEach(linkEl => {
    linkEl.setAttribute('href', waLink);
    linkEl.setAttribute('target', '_blank');
  });

  // Toggle open/close the modal
  document.querySelectorAll('[data-whatsapp-modal-toggle]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      if (!modal) return;
      const isActive = modal.getAttribute('data-whatsapp-modal-status') === 'active';
      modal.setAttribute('data-whatsapp-modal-status', isActive ? 'not-active' : 'active');
    });
  });

  // Close on ESC key
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' || event.keyCode === 27) {
      if (modal) {
        modal.setAttribute('data-whatsapp-modal-status', 'not-active');
      }
    }
  });
}
