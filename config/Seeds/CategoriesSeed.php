<?php

declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Categories seed.
 */
class CategoriesSeed extends AbstractSeed
{

    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'parent_id' => null,
                'lft' => 1,
                'rght' => 2,
                'name' => 'Uncategorized',
                'description' => null,
                'alias' => 'uncategorized',
                'seo_title' => null,
                'seo_description' => null,
                'seo_keywords' => null,
                'articles_count' => 1
            ]
        ];

        $table = $this->table('blogger_categories');
        $table->insert($data)->save();
    }

}
