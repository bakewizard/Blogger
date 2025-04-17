<?php

declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Articles seed.
 */
class ArticlesSeed extends AbstractSeed
{

    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'author_id' => 1,
                'title' => 'Hello world!',
                'body' => '<p>Welcome to Blogger plugin for CakeCMS. This is your first article. Edit or delete it, then start writing!</p>',
                'excerpt' => null,
                'seo_title' => null,
                'seo_description' => null,
                'seo_keywords' => null,
                'published' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'comments_count' => 0
            ]
        ];

        $table = $this->table('blogger_articles');
        $table->insert($data)->save();
    }

}
