<?php

namespace App\Blocks;

use StoutLogic\AcfBuilder\FieldsBuilder;

class ScrollableText extends BaseBlock
{
    public $name = 'Scrollable Text';
    public $description = 'Sectie met vaste achtergrond, sticky titel links en scrollende stappen rechts.';
    public $category = 'formatting';
    public $keywords = ['scroll', 'sticky', 'steps', 'stappen'];
    public $post_types = [];
    public $parent = [];
    public $mode = 'edit';
    public $view = 'blocks.scrollable-text';

    public $supports = [
        'full_height' => false,
        'anchor' => false,
        'mode' => 'edit',
        'multiple' => true,
        'supports' => ['mode' => false],
        'jsx' => true,
    ];

    public function with(): array
    {
        return array_merge(
            $this->getCommonFields(),
            [
                'background_image' => get_field('background_image'),
                'steps'            => get_field('steps') ?: [],
            ]
        );
    }

    public function fields(): \StoutLogic\AcfBuilder\FieldsBuilder
    {
        $acfFields = new FieldsBuilder('scrollable-text');

        return $acfFields;
    }

    public function enqueue(): void
    {
        //
    }
}
