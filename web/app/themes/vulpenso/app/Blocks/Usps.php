<?php

namespace App\Blocks;

use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Helpers\LordIconHelper;

class Usps extends BaseBlock
{
    public $name = 'USPs';
    public $slug = 'usps';
    public $description = 'Horizontale balk met USPs.';
    public $category = 'formatting';
    public $keywords = [];
    public $post_types = [];
    public $parent = [];
    public $mode = 'edit';
    public $view = 'blocks.usps';

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
        $usps = get_field('usps') ?: [];

        // Process USPs to add icon URLs
        foreach ($usps as &$usp) {
            if (!empty($usp['icon'])) {
                $usp['icon_url'] = LordIconHelper::getIconUrl($usp['icon']);
            }
        }

        return array_merge(
            $this->extractLayoutFields(),
            [
                'usps' => $usps,
            ]
        );
    }

    public function fields()
    {
        $acfFields = new FieldsBuilder('usps');

        return $acfFields->build();
    }

    public function enqueue()
    {
        //
    }
}
