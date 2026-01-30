@props([
  'id' => null,
  'pt' => 'normal',
  'pb' => 'normal',
  'background_color' => null,
])

<section
  {{ $id ? "id=$id" : '' }}
  {!! $attributes->class([
	'relative',
	$background_color,
	'pt-12 md:pt-16 lg:pt-18 xl:pt-24' => $pt === 'normal',
	'pt-0' => $pt === 'pt-0',
	'pt-2 md:pt-4' => $pt === 'pt-small',
	'pt-8 md:pt-12 lg:pt-16' => $pt === 'medium',

	'pb-12 md:pb-16 lg:pb-18 xl:pb-24' => $pb === 'normal',
	'pb-0' => $pb === 'pb-0',
	'pb-2 md:pb-4' => $pb === 'pb-small',
	'pb-8 md:pb-12 lg:pb-16' => $pb === 'medium',
  ]) !!}
>
  {{ $slot }}
</section>
