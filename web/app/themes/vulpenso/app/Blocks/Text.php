<?php

namespace App\Blocks;

use StoutLogic\AcfBuilder\FieldsBuilder;

class Text extends BaseBlock
{
    public $name = 'Tekst';
    public $description = 'Tekst met opties.';
    public $category = 'formatting';
    public $keywords = [];
    public $post_types = [];
    public $parent = [];
    public $mode = 'edit';
    public $view = 'blocks.text';

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
                'text_layout' => get_field('text_layout'),
            ]
        );
    }

    public function fields(): \StoutLogic\AcfBuilder\FieldsBuilder
    {
        $acfFields = new FieldsBuilder('text');

        return $acfFields;
    }

    public function enqueue(): void
    {
        //
    }
}
