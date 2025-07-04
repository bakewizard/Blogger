<?php
declare(strict_types=1);

namespace Blogger\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoriesFixture
 */
class CategoriesFixture extends TestFixture
{
    public string $table = 'blogger_categories';

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'parent_id' => null,
                'lft' => 1,
                'rght' => 4,
                'name' => 'Parent category',
                'description' => 'This is the parent category for all articles.',
                'alias' => 'parent-category',
                'enabled' => true,
                'seo_title' => null,
                'seo_description' => null,
                'seo_keywords' => null,
                'articles_count' => 0,
            ],
            [
                'id' => 2,
                'parent_id' => 1,
                'lft' => 2,
                'rght' => 3,
                'name' => 'Subcategory 1',
                'description' => 'This is the first subcategory under the parent category.',
                'alias' => 'subcategory-1',
                'enabled' => true,
                'seo_title' => null,
                'seo_description' => null,
                'seo_keywords' => null,
                'articles_count' => 2,
            ],
            [
                'id' => 3,
                'parent_id' => null,
                'lft' => 5,
                'rght' => 6,
                'name' => 'Parent category',
                'description' => 'This is the second parent category.',
                'alias' => 'parent-category-2',
                'enabled' => true,
                'seo_title' => null,
                'seo_description' => null,
                'seo_keywords' => null,
                'articles_count' => 3,
            ],
        ];
        parent::init();
    }
}
