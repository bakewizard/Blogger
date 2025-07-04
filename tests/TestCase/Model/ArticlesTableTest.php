<?php
declare(strict_types=1);

namespace Blogger\Test\TestCase\Model\Table;

use Blogger\Model\Table\ArticlesTable;
use Cake\ORM\Query\SelectQuery;
use Cake\TestSuite\TestCase;

/**
 * Blogger\Model\Table\ArticlesTable Test Case
 *
 * @uses \Blogger\Model\Table\ArticlesTable
 */
class ArticlesTableTest extends TestCase
{
    /**
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.Blogger.Articles',
        'plugin.Blogger.Categories',
        'plugin.Blogger.Tags',
        'plugin.Blogger.ArticlesCategories',
        'plugin.Blogger.ArticlesTags',
        'plugin.Blogger.Comments',
    ];

    /**
     * The ArticlesTable instance under test.
     *
     * @var \Blogger\Model\Table\ArticlesTable
     */
    protected ArticlesTable $Articles;

    /**
     * setUp method
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->Articles = $this->fetchTable('Blogger.Articles');
    }

    /**
     * tearDown method
     */
    protected function tearDown(): void
    {
        unset($this->Articles);
        parent::tearDown();
    }

    public function testFindPublished(): void
    {
        $query = $this->Articles->find('published');
        $this->assertInstanceOf(SelectQuery::class, $query);
        foreach ($query->all() as $article) {
            $this->assertTrue($article->published);
        }
    }

    public function testFindComments(): void
    {
        $query = $this->Articles->find('comments', sorting: 'asc');
        $result = $query->contain('Comments')->first();
        $this->assertNotEmpty($result?->comments);
    }
}
