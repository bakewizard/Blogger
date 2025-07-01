<?php
declare(strict_types=1);

namespace Blogger\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TagsFixture
 */
class TagsFixture extends TestFixture
{
    public string $table = 'blogger_tags';

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
                'title' => 'Test Tag 1',
                'alias' => 'test_tag_1',
                'articles_count' => 0,
            ],
            [
                'id' => 2,
                'title' => 'Test Tag 2',
                'alias' => 'test_tag_2',
                'articles_count' => 2,
            ],
        ];
        parent::init();
    }
}
