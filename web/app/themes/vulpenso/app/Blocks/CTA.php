<?php

namespace App\Blocks;

use StoutLogic\AcfBuilder\FieldsBuilder;

class CTA extends BaseBlock
{
    public $name = 'CTA';
	  public $slug = 'cta';
    public $description = 'Een blok met een CTA-items.';
    public $category = 'formatting';
    public $keywords = [];
    public $post_types = [];
    public $parent = [];
    public $mode = 'edit';

    public $supports = [
        'full_height' => false,
        'anchor' => false,
        'mode' => 'edit',
        'multiple' => true,
        'supports' => array('mode' => false),
        'jsx' => true,
    ];

    public function with()
    {
        return array_merge(
            $this->getCommonFields(),
            [
				'items' => get_field('items'),
            ]
        );
    }

    public function fields()
    {
        $acfFields = new FieldsBuilder('CTA');

        return $acfFields->build();
    }

    public function enqueue()
    {
        //
    }
}
