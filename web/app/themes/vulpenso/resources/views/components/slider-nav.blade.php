@props([
    'class' => '',
    'type' => '',
    'bg_color' => '',
])

<div @class([
  'grid place-items-center size-10 rounded-xl border transition-all duration-300',
  $type . '-prev' => $type,
  'border-black/10 hover:border-dark' => $bg_color === 'bg-white',
  'border-white/10 hover:border-white' => $bg_color === 'bg-dark',
])> 
  <div class="w-full h-full grid place-items-center">
    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M11.2174 6.81478C11.6496 6.81478 12 6.44998 12 6C12 5.55003 11.6496 5.18522 11.2174 5.18522L11.2174 6.81478ZM0.229188 5.42384C-0.0763954 5.74204 -0.0763955 6.25796 0.229187 6.57616L5.20971 11.7614C5.51535 12.0795 6.01085 12.0795 6.31648 11.7614C6.62212 11.4432 6.62212 10.9273 6.31648 10.6091L1.88936 6L6.31648 1.39091C6.62212 1.07271 6.62212 0.556846 6.31648 0.238648C6.01085 -0.0795503 5.51535 -0.0795504 5.20971 0.238648L0.229188 5.42384ZM11.2174 5.18522L0.782596 5.18522L0.782596 6.81478L11.2174 6.81478L11.2174 5.18522Z" fill="#141919"/>
    </svg>
  </div>
</div>

<div @class([
  'grid place-items-center size-10 rounded-xl border transition-all duration-300',
  $type . '-next' => $type,
  'border-black/10 hover:border-dark' => $bg_color === 'bg-white',
  'border-white/10 hover:border-white' => $bg_color === 'bg-dark',
])> 
  <div class="w-full h-full grid place-items-center">
    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M0.78261 5.18522C0.350385 5.18522 0 5.55002 0 6C0 6.44997 0.350385 6.81478 0.78261 6.81478V5.18522ZM11.7708 6.57615C12.0764 6.25796 12.0764 5.74204 11.7708 5.42384L6.79029 0.238644C6.48465 -0.079548 5.98915 -0.079548 5.68352 0.238644C5.37788 0.556832 5.37788 1.07272 5.68352 1.39091L10.1106 6L5.68352 10.6091C5.37788 10.9273 5.37788 11.4432 5.68352 11.7614C5.98915 12.0795 6.48465 12.0795 6.79029 11.7614L11.7708 6.57615ZM0.78261 6.81478H11.2174V5.18522H0.78261V6.81478Z" fill="#141919"/>
    </svg>      
  </div>
</div>