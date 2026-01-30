@props([
    'src' => '',
    'trigger' => 'hover',
    'target' => '',
    'stroke' => 'bold',
    'primary' => '#000000',
    'secondary' => '#000000',
])

@if($src)
    @php
        $iconSrc = $src;

        // Als het al een volledige URL is, gebruik die direct
        if (str_starts_with($src, 'http')) {
            $iconSrc = $src;
        }
        // Check of icon in public folder bestaat (snelle statische bestanden)
        elseif (str_starts_with($src, 'wired-')) {
            $cleanSrc = str_replace('.json', '', $src);
            $publicFile = get_theme_file_path('public/icons/' . $cleanSrc . '.json');
            $resourcesFile = get_theme_file_path('resources/icons/' . $cleanSrc . '.json');

            if (file_exists($publicFile)) {
                $iconSrc = get_theme_file_uri('public/icons/' . $cleanSrc . '.json');
            } elseif (file_exists($resourcesFile)) {
                $iconSrc = admin_url('admin-ajax.php?action=get_lordicon_json&icon_id=' . $cleanSrc);
            }
        }
        // Als het .json bevat
        elseif (str_contains($src, '.json')) {
            $cleanSrc = str_replace('.json', '', $src);
            $publicFile = get_theme_file_path('public/icons/' . $cleanSrc . '.json');
            $resourcesFile = get_theme_file_path('resources/icons/' . $cleanSrc . '.json');

            if (file_exists($publicFile)) {
                $iconSrc = get_theme_file_uri('public/icons/' . $cleanSrc . '.json');
            } elseif (file_exists($resourcesFile)) {
                $iconSrc = admin_url('admin-ajax.php?action=get_lordicon_json&icon_id=' . $cleanSrc);
            } else {
                $iconSrc = 'https://cdn.lordicon.com/' . $cleanSrc . '.json';
            }
        }
        // Anders, probeer CDN (korte codes zoals wqjnwpc8)
        else {
            $iconSrc = 'https://cdn.lordicon.com/' . $src . '.json';
        }
    @endphp
    <lord-icon
        {{ $attributes->except(['src', 'trigger', 'target', 'stroke', 'primary', 'secondary'])->merge(['class' => 'lordicon-element']) }}
        src="{!! $iconSrc !!}"
        trigger="{{ $trigger }}"
        @if($target)target="{{ $target }}"@endif
        stroke="{{ $stroke }}"
        colors="primary:{{ $primary }},secondary:{{ $secondary }}"
    ></lord-icon>
@endif