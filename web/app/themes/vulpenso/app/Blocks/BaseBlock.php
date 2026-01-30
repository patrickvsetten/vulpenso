<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use App\Traits\AcfBlockIcon;

abstract class BaseBlock extends Block
{
    use AcfBlockIcon;

    /**
     * Get layout field with proper fallbacks
     *
     * @return array
     */
    protected function getLayout(): array
    {
        $layout = get_field('layout');

        // Ensure we always return an array
        if (!is_array($layout)) {
            return [];
        }

        return $layout;
    }

    /**
     * Extract layout fields with proper fallbacks
     *
     * @return array
     */
    protected function extractLayoutFields(): array
    {
        $layout = $this->getLayout();

        return [
            'id'               => $layout['id'] ?? null,
            'pt'               => $layout['pt'] ?? null,
            'pb'               => $layout['pb'] ?? null,
            'background_color' => $layout['background'] ?? null,
        ];
    }

    /**
     * Get reusable content with proper fallbacks
     *
     * @return array
     */
    protected function getReusableContent(): array
    {
        $reusableContent = get_field('reusable_content');

        // Ensure we always return an array
        if (!is_array($reusableContent)) {
            return [];
        }

        return $reusableContent;
    }

    /**
     * Extract reusable content fields with proper fallbacks
     *
     * @return array
     */
    protected function extractReusableContentFields(): array
    {
        $reusableContent = $this->getReusableContent();

        return [
            'content_items' => $reusableContent['content_items'] ?? null,
            'subtitle'      => $reusableContent['subtitle'] ?? null,
            'heading'       => $reusableContent['heading'] ?? null,
            'heading_size'  => $reusableContent['heading_size'] ?? null,
            'title'         => $this->getCleanTitle($reusableContent['title'] ?? null),
            'content'       => $reusableContent['content'] ?? null,
            'buttons'       => $reusableContent['buttons'] ?? null,
        ];
    }

    /**
     * Clean title by removing paragraph tags
     *
     * @param string|null $title
     * @return string|null
     */
    protected function getCleanTitle($title): ?string
    {
        if (!$title) {
            return null;
        }
        
        return preg_replace('/<\/?p>/', '', $title);
    }

    /**
     * Get all common fields (layout + reusable content)
     *
     * @return array
     */
    protected function getCommonFields(): array
    {
        return array_merge(
            $this->extractLayoutFields(),
            $this->extractReusableContentFields()
        );
    }
} 