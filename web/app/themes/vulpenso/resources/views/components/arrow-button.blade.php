@props([
    'size' => 'md',
    'type' => 'outline',
])

@php
  $sizeClasses = match($size) {
    'xs' => 'size-8',
    'sm' => 'size-10',
    'lg' => 'size-14',
    default => 'size-12'
  };

  $baseClasses = 'rounded-lg grid place-items-center relative overflow-clip transition-all duration-300';

  $typeClasses = match($type) {
    'solid' => 'bg-primary text-white group-hover:bg-primary-dark',
    'soft-solid' => 'bg-primary/10 text-primary group-hover:bg-primary-dark',
    'outline-white' => 'border-[1.5px] border-white text-white group-hover:bg-white group-hover:text-primary',
    'outline-to-white' => 'border-[1.5px] border-primary text-primary group-hover:border-white group-hover:text-white',
    'text' => 'text-primary group-hover:text-primary-dark',
    'text-white' => 'text-white group-hover:text-primary',
    default => 'border-[1.5px] border-primary text-primary group-hover:bg-primary group-hover:text-white'
  };
@endphp

<div {{ $attributes->class([$sizeClasses, $baseClasses, $typeClasses]) }}>
  {{-- Huidige pijl - vliegt naar rechtsboven --}}
  <svg class="absolute transition-all duration-300 ease-out group-hover:translate-x-[200%] group-hover:-translate-y-[200%] group-hover:opacity-0" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M2.1642 0.750059H9.23527M9.23527 0.750059V7.82113M9.23527 0.750059L0.749989 9.23534" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
  {{-- Nieuwe pijl - komt van linksonder --}}
  <svg class="absolute transition-all opacity-0 duration-300 ease-out -translate-x-[200%] translate-y-[200%] group-hover:translate-x-0 group-hover:translate-y-0 group-hover:opacity-100" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M2.1642 0.750059H9.23527M9.23527 0.750059V7.82113M9.23527 0.750059L0.749989 9.23534" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</div>
