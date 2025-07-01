<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * ArticlesCategories seed.
 */
class ArticlesCategoriesSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html#the-run-method
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'article_id' => 1,
                'category_id' => 1,
            ],
        ];

        $table = $this->table('blogger_articles_categories');
        $table->insert($data)->save();
    }

    /**
     * Get Dependencies Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html#foreign-key-dependencies
     *
     * @return void
     */
    public function getDependencies(): array
    {
        return [
            'ArticlesSeed',
            'CategoriesSeed',
        ];
    }
}
