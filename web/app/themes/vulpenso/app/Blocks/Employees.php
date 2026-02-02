<?php

namespace App\Blocks;

use StoutLogic\AcfBuilder\FieldsBuilder;

class Employees extends BaseBlock
{
    public $name = 'Medewerkers';
    public $description = 'Toont de medewerkers met momentum hover effect.';
    public $category = 'formatting';
    public $keywords = ['medewerkers', 'employees', 'team'];
    public $post_types = [];
    public $parent = [];
    public $mode = 'edit';
    public $view = 'blocks.employees';

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
                'employees' => $this->getEmployees(),
            ]
        );
    }

    protected function getEmployees(): array
    {
        $employees = get_posts([
            'post_type'      => 'employees',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ]);

        return array_map(function ($employee) {
            return [
                'id'        => $employee->ID,
                'name'      => get_the_title($employee),
                'function'  => get_field('function', $employee->ID),
                'phone'     => get_field('phone', $employee->ID),
                'thumbnail' => get_post_thumbnail_id($employee),
            ];
        }, $employees);
    }

    public function fields(): \StoutLogic\AcfBuilder\FieldsBuilder
    {
        $acfFields = new FieldsBuilder('employees');

        return $acfFields;
    }

    public function enqueue(): void
    {
        //
    }
}
