<?php
declare(strict_types=1);

namespace Blogger\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ArticlesFixture
 */
class ArticlesFixture extends TestFixture
{
    public string $table = 'blogger_articles';

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
                'author_id' => 1,
                'title' => 'First Article',
                'body' => 'This is the full body of the first article.',
                'excerpt' => 'Excerpt of the first article.',
                'seo_title' => null,
                'seo_description' => null,
                'seo_keywords' => null,
                'sort_order' => 1,
                'published' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'comments_count' => 0,
            ],
            [
                'id' => 2,
                'author_id' => 1,
                'title' => 'Second Article',
                'body' => 'This is the full body of the second article.',
                'excerpt' => 'Excerpt of the second article.',
                'seo_title' => null,
                'seo_description' => null,
                'seo_keywords' => null,
                'sort_order' => 2,
                'published' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'comments_count' => 3,
            ],
            [
                'id' => 3,
                'author_id' => 1,
                'title' => 'First Archived Article',
                'body' => 'This is the full body of the first archived article.',
                'excerpt' => null,
                'seo_title' => null,
                'seo_description' => null,
                'seo_keywords' => null,
                'sort_order' => null,
                'published' => true,
                'created' => '2021-04-01 10:32:17',
                'modified' => '2021-04-01 10:32:17',
                'comments_count' => 0,
            ],
            [
                'id' => 4,
                'author_id' => 1,
                'title' => 'Second Archived Article',
                'body' => 'This is the full body of the second archived article.',
                'excerpt' => null,
                'seo_title' => null,
                'seo_description' => null,
                'seo_keywords' => null,
                'sort_order' => null,
                'published' => true,
                'created' => '2021-06-01 10:32:17',
                'modified' => '2021-06-01 10:32:17',
                'comments_count' => 0,
            ],
            [
                'id' => 5,
                'author_id' => 1,
                'title' => 'Third Archived Article',
                'body' => 'This is the full body of the third archived article.',
                'excerpt' => null,
                'seo_title' => null,
                'seo_description' => null,
                'seo_keywords' => null,
                'sort_order' => null,
                'published' => true,
                'created' => '2021-07-01 10:32:17',
                'modified' => '2021-07-01 10:32:17',
                'comments_count' => 0,
            ],
        ];
        parent::init();
    }
}
