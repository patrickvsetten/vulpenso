<?php

namespace App\Blocks;

use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Helpers\LordIconHelper;

class TextImage extends BaseBlock
{
    public $name = 'Tekst met afbeelding';
    public $description = 'Tekst met een afbeelding ernaast.';
    public $category = 'formatting';
    public $keywords = [];
    public $post_types = [];
    public $parent = [];
    public $mode = 'edit';
    public $view = 'blocks.text-image';

    public $supports = [
        'full_height' => false,
        'anchor' => false,
        'mode' => 'edit',
        'multiple' => true,
        'supports' => ['mode' => false],
        'jsx' => true,
    ];

    protected function getLinks(): array
    {
        $links = get_field('links') ?: [];

        return array_map(function ($link) {
            $link['icon_url'] = $link['icon'] ? LordIconHelper::getIconUrl($link['icon']) : null;
            return $link;
        }, $links);
    }

    public function with(): array
    {
        $imageTextPosition = get_field('image_text_position');
        $isTextFirst = $imageTextPosition === 'text-image';

        return array_merge(
            $this->getCommonFields(),
            [
                'image_text_position'    => $imageTextPosition,
                'text_position'          => $isTextFirst ? 'order-2 md:order-1' : 'order-2',
                'media_position'         => $isTextFirst ? 'order-1 md:order-2' : 'order-1',
                'media'                  => get_field('media'),
                'type'                   => get_field('type') ?: 'basis',
                'highlights'             => get_field('highlights'),
                'image_fit'              => get_field('image_fit') ?: 'cover',
                'show_highlight_overlay' => get_field('show_highlight_overlay'),
                'overlay_title'          => get_field('overlay_title'),
                'overlay_text'           => get_field('overlay_text'),
                'overlay_link'           => get_field('overlay_link'),
                'add_links'              => get_field('add_links'),
                'links'                  => $this->getLinks(),
            ]
        );
    }

    public function fields(): \StoutLogic\AcfBuilder\FieldsBuilder
    {
        $acfFields = new FieldsBuilder('text-image');

        return $acfFields;
    }

    public function enqueue(): void
    {
        //
    }
}
