<?php

namespace App\Blocks;

use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Helpers\LordIconHelper;

class UspCards extends BaseBlock
{
    public $name = 'USP Cards';
    public $description = 'USP kaarten met icoon, titel en content.';
    public $category = 'formatting';
    public $keywords = [];
    public $post_types = [];
    public $parent = [];
    public $mode = 'edit';
    public $view = 'blocks.usp-cards';

    public $supports = [
        'full_height' => false,
        'anchor' => false,
        'mode' => 'edit',
        'multiple' => true,
        'supports' => ['mode' => false],
        'jsx' => true,
    ];

    protected function getUsps(): array
    {
        $usps = get_field('usps') ?: [];

        return array_map(function ($item) {
            $iconId = $item['icon'] ?? null;

            return [
                'icon'     => $iconId,
                'icon_url' => LordIconHelper::getIconUrl($iconId),
                'title'    => $item['title'] ?? null,
                'content'  => $item['content'] ?? null,
                'link'     => $item['link'] ?? null,
            ];
        }, $usps);
    }

    public function with(): array
    {
        return array_merge(
            $this->getCommonFields(),
            [
                'usps' => $this->getUsps(),
            ]
        );
    }

    public function fields(): \StoutLogic\AcfBuilder\FieldsBuilder
    {
        $acfFields = new FieldsBuilder('usp_cards');

        return $acfFields;
    }

    public function enqueue(): void
    {
        //
    }
}
