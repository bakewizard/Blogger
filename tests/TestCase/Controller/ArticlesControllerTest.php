<?php
declare(strict_types=1);

namespace Blogger\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * @link \Blogger\Controller\ArticlesController
 */
class ArticlesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected array $fixtures = [
        'app.Users',
        'plugin.Blogger.Articles',
        'plugin.Blogger.Categories',
        'plugin.Blogger.Tags',
        'plugin.Blogger.Comments',
        'plugin.Blogger.ArticlesCategories',
        'plugin.Blogger.ArticlesTags',
    ];

    public function setUp(): void
    {
        parent::setUp();

        // $this->configRequest([
        //     'headers' => ['Accept' => 'application/json'],
        // ]);
    }

    /**
     * Test the index() method
     * Should return a paginated list of published articles
     */
    public function testIndex(): void
    {
        $this->get('/blogger');
        $this->assertResponseOk();
        $this->assertNotNull($this->viewVariable('articles'));
    }

    /**
     * Test the search() method
     * Should return filtered articles based on search query
     */
    public function testSearch(): void
    {
        $this->get('/blogger/search?title=first');
        $this->assertResponseOk();
        $this->assertNotNull($this->viewVariable('articles'));
    }

    /**
     * Test the view() method
     * Should return a single article by ID
     */
    public function testView(): void
    {
        $this->get('/blogger/item/1');
        $this->assertResponseOk();
        $this->assertNotNull($this->viewVariable('article'));
    }

    /**
     * Test the category() method
     * Should return articles from the category and its children
     */
    public function testCategory(): void
    {
        $this->get('/blogger/category/1');
        $this->assertResponseOk();
        $this->assertNotNull($this->viewVariable('articles'));
    }

    /**
     * Test the tag() method
     * Should return articles with a specific tag alias
     */
    public function testTag(): void
    {
        $this->get('/blogger/tag/sample-tag');
        $this->assertResponseOk();
        $this->assertNotNull($this->viewVariable('articles'));
    }

    /**
     * Test the user() method
     * Should return articles written by a specific user
     */
    public function testUser(): void
    {
        $this->get('/blogger/user/1');
        $this->assertResponseOk();
        $this->assertNotNull($this->viewVariable('articles'));
    }

    /**
     * Test the archive() method with just year
     * Should return articles from that year
     */
    public function testArchiveYear(): void
    {
        $this->get('/blogger/archive/2021');
        $this->assertResponseOk();
        $this->assertNotNull($this->viewVariable('articles'));
    }

    /**
     * Test the archive() method with year and month
     * Should return articles from that specific date
     */
    public function testArchiveYearMonth(): void
    {
        $this->get('/blogger/archive/2021/06');
        $this->assertResponseOk();
        $this->assertNotNull($this->viewVariable('articles'));
    }

    /**
     * Test the archive() method with year, month, and day
     * Should return articles from that specific date
     */
    public function testArchiveYearMonthDay(): void
    {
        $this->get('/blogger/archive/2021/06/01');
        $this->assertResponseOk();
        $this->assertNotNull($this->viewVariable('articles'));
    }

    /**
     * Test addComment() method (successful save)
     * Should redirect and show a success flash message
     */
    public function testAddCommentSuccess(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->post('/blogger/add-comment', [
            'parent_id' => 1,
            'user_id' => 1,
            'article_id' => 1,
            'author_name' => 'John Doe',
            'author_email' => 'johndoe@nodomain.net',
            'content' => 'Great article!',
        ]);

        $this->assertRedirect();
        $this->assertFlashMessage('Thanks for your comment. It will be available after moderation');
    }

    /**
     * Test addComment() method (failed save due to invalid input)
     * Should redirect and show an error flash message
     */
    public function testAddCommentFail(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->post('/blogger/add-comment', []);

        $this->assertRedirect();
        $this->assertFlashMessage('There was an error while saving your comment. Try again');
    }
}
