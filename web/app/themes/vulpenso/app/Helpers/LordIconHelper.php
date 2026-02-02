<?php

namespace App\Helpers;

class LordIconHelper
{
    /**
     * Convert a LordIcon ID to the static file URL.
     */
    public static function getIconUrl(?string $iconId): ?string
    {
        if (empty($iconId)) {
            return null;
        }

        // Check if icon exists in public folder (cacheable static files)
        $publicPath = get_theme_file_path('public/icons/' . $iconId . '.json');
        if (file_exists($publicPath)) {
            return get_theme_file_uri('public/icons/' . $iconId . '.json');
        }

        // Fallback to resources folder via AJAX (for icons not yet in public)
        $resourcesPath = get_theme_file_path('resources/icons/' . $iconId . '.json');
        if (file_exists($resourcesPath)) {
            return admin_url('admin-ajax.php?action=get_lordicon_json&icon_id=' . urlencode($iconId));
        }

        return null;
    }

    /**
     * Process grid items to convert icon IDs to URLs.
     */
    public static function processGridItems(mixed $gridItems): mixed
    {
        if (!is_array($gridItems)) {
            return $gridItems;
        }

        foreach ($gridItems as &$item) {
            if (isset($item['icon']) && !empty($item['icon'])) {
                $item['icon_url'] = self::getIconUrl($item['icon']);
            }
        }

        return $gridItems;
    }
}
