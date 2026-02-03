<?php

namespace App\Helpers;

class SvgHelper
{
    /**
     * Render an SVG from URL/path with custom colors.
     *
     * @param array|null $image ACF image array with 'url' key
     * @param string $color Main stroke/fill color
     * @param string $class CSS class to add to SVG
     * @param string|null $bgColor Background fill color (for solid fills that should match background)
     */
    public static function render(?array $image, string $color = '#FFFFFF', string $class = '', ?string $bgColor = null): string
    {
        if (empty($image) || empty($image['url'])) {
            return '';
        }

        // Get the file path from the URL
        $uploadDir = wp_upload_dir();
        $filePath = str_replace($uploadDir['baseurl'], $uploadDir['basedir'], $image['url']);

        // Check if file exists
        if (!file_exists($filePath)) {
            return '';
        }

        // Read SVG content
        $svgContent = file_get_contents($filePath);
        if (!$svgContent) {
            return '';
        }

        // Temporarily replace mask content to protect it
        $masks = [];
        $svgContent = preg_replace_callback('/<mask[^>]*>.*?<\/mask>/s', function($match) use (&$masks) {
            $placeholder = '<!--MASK_' . count($masks) . '-->';
            $masks[] = $match[0];
            return $placeholder;
        }, $svgContent);

        // Replace stroke colors
        $svgContent = preg_replace('/stroke="[^"]*"/', 'stroke="' . esc_attr($color) . '"', $svgContent);

        // Replace fill colors
        if ($bgColor) {
            // If bgColor provided, use it for non-none fills (background elements)
            $svgContent = preg_replace('/fill="(?!none)[^"]*"/', 'fill="' . esc_attr($bgColor) . '"', $svgContent);
        } else {
            // Otherwise use main color for fills (but preserve fill="none")
            $svgContent = preg_replace('/fill="(?!none)[^"]*"/', 'fill="' . esc_attr($color) . '"', $svgContent);
        }

        // Restore mask content
        foreach ($masks as $i => $mask) {
            $svgContent = str_replace('<!--MASK_' . $i . '-->', $mask, $svgContent);
        }

        // Add class to SVG tag if provided
        if ($class) {
            $svgContent = preg_replace('/<svg/', '<svg class="' . esc_attr($class) . '"', $svgContent, 1);
        }

        return $svgContent;
    }
}
