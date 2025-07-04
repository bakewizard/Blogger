<?php
declare(strict_types=1);

namespace Blogger\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoriesFixture
 */
class CommentsFixture extends TestFixture
{
    public string $table = 'blogger_comments';

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            // Root comment on article 1
            [
                'id' => 1,
                'parent_id' => null,
                'user_id' => 1,
                'article_id' => 1,
                'lft' => 1,
                'rght' => 6,
                'level' => 0,
                'author_name' => null,
                'author_email' => null,
                'author_ip' => '127.0.0.1',
                'content' => 'This is the root comment.',
                'approved' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // Reply to comment #1
            [
                'id' => 2,
                'parent_id' => 1,
                'user_id' => null,
                'article_id' => 1,
                'lft' => 2,
                'rght' => 3,
                'level' => 1,
                'author_name' => 'Guest User',
                'author_email' => 'guest@example.com',
                'author_ip' => '192.168.0.100',
                'content' => 'Thanks for your comment!',
                'approved' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // Another reply to comment #1
            [
                'id' => 3,
                'parent_id' => 1,
                'user_id' => 2,
                'article_id' => 1,
                'lft' => 4,
                'rght' => 5,
                'level' => 1,
                'author_name' => null,
                'author_email' => null,
                'author_ip' => '127.0.0.1',
                'content' => 'I agree with this post.',
                'approved' => 0,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // Root comment on article 2
            [
                'id' => 4,
                'parent_id' => null,
                'user_id' => 1,
                'article_id' => 2,
                'lft' => 1,
                'rght' => 2,
                'level' => 0,
                'author_name' => null,
                'author_email' => null,
                'author_ip' => '10.0.0.5',
                'content' => 'Nice article on article 2!',
                'approved' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];
        parent::init();
    }
}
