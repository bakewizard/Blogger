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
                'seo_title' => 'First Article SEO Title',
                'seo_description' => 'SEO description for the first article.',
                'seo_keywords' => 'article, blog, first',
                'sort_order' => 1,
                'published' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'comments_count' => 0,
            ],
            [
                'id' => 2,
                'author_id' => 2,
                'title' => 'Second Article',
                'body' => 'This is the full body of the second article.',
                'excerpt' => 'Excerpt of the second article.',
                'seo_title' => 'Second Article SEO Title',
                'seo_description' => 'SEO description for the second article.',
                'seo_keywords' => 'article, blog, second',
                'sort_order' => 2,
                'published' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'comments_count' => 3,
            ],
        ];
        parent::init();
    }
}
