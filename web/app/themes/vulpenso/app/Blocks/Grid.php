<?php

namespace App\Blocks;

use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Helpers\LordIconHelper;

class Grid extends BaseBlock
{
    public $name = 'Grid';
    public $description = 'Grid met opties.';
    public $category = 'formatting';
    public $keywords = [];
    public $post_types = [];
    public $parent = [];
    public $mode = 'edit';
    public $view = 'blocks.grid';

    public $supports = [
        'full_height' => false,
        'anchor' => false,
        'mode' => 'edit',
        'multiple' => true,
        'supports' => ['mode' => false],
        'jsx' => true,
    ];

    protected function getGridItems(): array
    {
        $gridItems = get_field('grid_items') ?: [];
        $typeContent = get_field('type_content');

        return array_map(function ($item) use ($typeContent) {
            $width = $item['width'] ?? '1/3';

            if ($typeContent === 'services' && !empty($item['service'])) {
                return $this->buildServiceItem($item['service'], $width);
            }

            return $this->buildManualItem($item, $width);
        }, $gridItems);
    }

    protected function buildServiceItem(int $serviceId, string $width): array
    {
        $iconId = get_field('icon', $serviceId);

        return [
            'width'             => $width,
            'icon'              => $iconId,
            'icon_url'          => LordIconHelper::getIconUrl($iconId),
            'title'             => get_the_title($serviceId),
            'short_description' => get_field('short_description', $serviceId) ?: get_the_excerpt($serviceId),
            'link'              => get_permalink($serviceId),
            'image'             => get_field('image', $serviceId) ?: get_post_thumbnail_id($serviceId),
        ];
    }

    protected function buildManualItem(array $item, string $width): array
    {
        $iconId = $item['icon'] ?? null;

        return [
            'width'             => $width,
            'icon'              => $iconId,
            'icon_url'          => LordIconHelper::getIconUrl($iconId),
            'title'             => $item['title'] ?? null,
            'short_description' => $item['short_description'] ?? null,
            'link'              => $item['link']['url'] ?? null,
            'link_target'       => $item['link']['target'] ?? null,
            'image'             => $item['image'] ?? null,
        ];
    }

    public function with(): array
    {
        return array_merge(
            $this->getCommonFields(),
            [
                'type_content' => get_field('type_content'),
                'grid_items'   => $this->getGridItems(),
            ]
        );
    }

    public function fields(): \StoutLogic\AcfBuilder\FieldsBuilder
    {
        $acfFields = new FieldsBuilder('grid');

        return $acfFields;
    }

    public function enqueue(): void
    {
        //
    }
}
