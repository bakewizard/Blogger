<?php

declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * ArticlesCategories seed.
 */
class ArticlesCategoriesSeed extends AbstractSeed
{

    public function run(): void
    {
        $data = [
            [
                'article_id' => 1,
                'category_id' => 1
            ]
        ];

        $table = $this->table('blogger_articles_categories');
        $table->insert($data)->save();
    }

    public function getDependencies(): array
    {
        return [
            'ArticlesSeed',
            'CategoriesSeed'
        ];
    }

}
