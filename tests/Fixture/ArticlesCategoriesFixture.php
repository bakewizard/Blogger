<?php
declare(strict_types=1);

namespace Blogger\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ArticlesCategoriesFixture
 */
class ArticlesCategoriesFixture extends TestFixture
{
    public string $table = 'blogger_articles_categories';

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'article_id' => 1,
                'category_id' => 2,
            ],
            [
                'article_id' => 2,
                'category_id' => 2,
            ],
            [
                'article_id' => 3,
                'category_id' => 3,
            ],
            [
                'article_id' => 4,
                'category_id' => 3,
            ],
            [
                'article_id' => 5,
                'category_id' => 3,
            ],
        ];
        parent::init();
    }
}
