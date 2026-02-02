<?php

namespace App\Blocks;

use StoutLogic\AcfBuilder\FieldsBuilder;

class Prices extends BaseBlock
{
    public $name = 'Prijzen';
    public $description = 'Prijstabellen met sticky navigatie.';
    public $category = 'formatting';
    public $keywords = [];
    public $post_types = [];
    public $parent = [];
    public $mode = 'edit';
    public $view = 'blocks.prices';

    public $supports = [
        'full_height' => false,
        'anchor' => false,
        'mode' => 'edit',
        'multiple' => true,
        'supports' => ['mode' => false],
        'jsx' => true,
    ];

    protected function getPriceTables(): array
    {
        $tables = get_field('price_tables') ?: [];

        return array_map(function ($table, $index) {
            return [
                'slug'            => sanitize_title($table['title'] ?? 'tabel-' . $index),
                'title'           => $table['title'] ?? null,
                'description'     => $table['description'] ?? null,
                'items'           => $table['items'] ?? [],
                'additional_info' => $table['additional_info'] ?? null,
            ];
        }, $tables, array_keys($tables));
    }

    public function with(): array
    {
        return array_merge(
            $this->getCommonFields(),
            [
                'price_tables' => $this->getPriceTables(),
            ]
        );
    }

    public function fields(): \StoutLogic\AcfBuilder\FieldsBuilder
    {
        $acfFields = new FieldsBuilder('prices');

        return $acfFields;
    }

    public function enqueue(): void
    {
        //
    }
}
