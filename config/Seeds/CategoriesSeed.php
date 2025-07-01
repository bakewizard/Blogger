<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Categories seed.
 */
class CategoriesSeed extends BaseSeed
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
                'articles_count' => 1,
            ],
        ];

        $table = $this->table('blogger_categories');
        $table->insert($data)->save();
    }
}
