<?php
declare(strict_types=1);

namespace Blogger\Test\TestCase\Controller\Admin;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * Blogger\Controller\Admin\ArticlesController Test Case
 *
 * @link \Blogger\Controller\Admin\ArticlesController
 */
class ArticlesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Users',
        'app.Roles',
        'plugin.Blogger.Articles',
    ];

    /**
     * setUp method
     *
     * This method is called before each test method.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $user = $this->fetchTable('Users')->get(1);

        $this->session([
            'Auth' => [
                'User' => $user,
            ],
        ]);
    }

    /**
     * tearDown method
     *
     * This method is called after each test method.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test view method
     *
     * @return void
     * @link \Blogger\Controller\Admin\ArticlesController::index()
     */
    public function testIndex(): void
    {
        $this->get('/admin/blogger/articles');

        $this->assertResponseOk();
        $this->assertNotNull($this->viewVariable('articles'));
    }

    /**
     * Test view method
     *
     * @return void
     * @link \Blogger\Controller\Admin\ArticlesController::view()
     */
    public function testView(): void
    {
        $this->get('/admin/blogger/articles/view/1');
        $this->assertResponseOk();

        $this->assertNotEmpty($this->viewVariable('article'));
        $this->assertEquals(1, $this->viewVariable('article')->id);
    }

    /**
     * Test add method
     *
     * @return void
     * @link \Blogger\Controller\Admin\ArticlesController::add()
     */
    public function testAddGet(): void
    {
        $this->get('/admin/blogger/articles/add');
        $this->assertResponseOk();
    }

    /**
     * Test add post method
     *
     * @return void
     * @link \Blogger\Controller\Admin\ArticlesController::add()
     */
    public function testAddPost(): void
    {
        $postData = [
            'author_id' => 1,
            'title' => 'Third Article Title',
            'body' => 'This is the detailed body content of the third article. It can be quite long.',
            'excerpt' => 'A short excerpt for the third article.',
            'seo_title' => 'Third Article SEO Title',
            'seo_description' => 'SEO description focused on keywords for the third article.',
            'seo_keywords' => 'third, article, blog, seo',
            'sort_order' => 3,
            'published' => true,
        ];
        $this->post('/admin/blogger/articles/add', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/admin/blogger/articles');
    }

    /**
     * Test edit method
     *
     * @return void
     * @link \Blogger\Controller\Admin\ArticlesController::edit()
     */
    public function testEditGet(): void
    {
        $this->get('/admin/blogger/articles/edit/1');
        $this->assertResponseOk();
    }

    /**
     * Test edit post method
     *
     * @return void
     * @link \Blogger\Controller\Admin\ArticlesController::edit()
     */
    public function testEditPost(): void
    {
        $postData = [
            'title' => 'Modified Third Article Title',
        ];
        $this->post('/admin/blogger/articles/edit/1', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/admin/blogger/articles');
    }

    /**
     * Test delete method
     *
     * @return void
     * @link \Blogger\Controller\Admin\ArticlesController::delete()
     */
    public function testDeletePost(): void
    {
        $this->post('/admin/blogger/articles/delete/1');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/admin/blogger/articles');
    }
}
