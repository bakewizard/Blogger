<?php
declare(strict_types=1);

namespace Blogger\Test\TestCase\Controller\Admin;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * @link \Blogger\Controller\Admin\CategoriesController
 */
class CategoriesControllerTest extends TestCase
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
        'plugin.Blogger.Categories',
        'plugin.Blogger.Articles',
        'plugin.Blogger.ArticlesCategories',
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
     * Test index with no parent category (top-level)
     *
     * @return void
     * @link \Blogger\Controller\Admin\CategoriesController::index()
     */
    public function testIndexRoot(): void
    {
        $this->get('/admin/blogger/categories');
        $this->assertResponseOk();
    }

    /**
     * Test index with a parent ID (child categories)
     *
     * @return void
     * @link \Blogger\Controller\Admin\CategoriesController::index()
     */
    public function testIndexWithParent(): void
    {
        $this->get('/admin/blogger/categories/index/1');
        $this->assertResponseOk();
        $this->assertResponseContains('categories');
    }

    /**
     * Test index redirects to view when no children found
     *
     * @return void
     * @link \Blogger\Controller\Admin\CategoriesController::index()
     */
    public function testIndexRedirectToView(): void
    {
        $this->get('/admin/blogger/categories/index/2');
        $this->assertRedirectContains('/admin/blogger/categories/view/2');
    }

    /**
     * Test viewing a category and its articles
     */
    public function testView(): void
    {
        $this->get('/admin/blogger/categories/view/2');
        $this->assertResponseOk();
        $this->assertNotNull($this->viewVariable('category'));
    }

    /**
     * Test redirect if view called without ID
     *
     * @return void
     * @link \Blogger\Controller\Admin\CategoriesController::view()
     */
    public function testViewRedirectsToIndex(): void
    {
        $this->get('/admin/blogger/categories/view');
        $this->assertRedirectContains('/admin/blogger/categories');
    }

    /**
     * Test add new category (GET + POST success)
     *
     * @return void
     * @link \Blogger\Controller\Admin\CategoriesController::add()
     */
    public function testAdd(): void
    {
        // GET
        $this->get('/admin/blogger/categories/add');
        $this->assertResponseOk();

        // POST
        $this->post('/admin/blogger/categories/add', [
            'name' => 'New Category',
            'parent_id' => null,
        ]);
        $this->assertRedirectContains('/admin/blogger/categories');
        $this->assertFlashMessage('The category has been saved.');
    }

    /**
     * Test editing a category (GET + PATCH)
     *
     * @return void
     * @link \Blogger\Controller\Admin\CategoriesController::edit()
     */
    public function testEdit(): void
    {
        // GET
        $this->get('/admin/blogger/categories/edit/1');
        $this->assertResponseOk();

        // PATCH
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->patch('/admin/blogger/categories/edit/1', [
            'name' => 'Updated Category Name',
        ]);
        $this->assertRedirectContains('/admin/blogger/categories');
    }

    /**
     * Test deleting a category
     *
     * @return void
     * @link \Blogger\Controller\Admin\CategoriesController::delete()
     */
    public function testDelete(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->delete('/admin/blogger/categories/delete/1');
        $this->assertRedirectContains('/admin/blogger/categories');
    }

    /**
     * Test move up/down functionality
     *
     * @return void
     * @link \Blogger\Controller\Admin\CategoriesController::move()
     */
    public function testMove(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->post('/admin/blogger/categories/move', [
            'id' => 1,
            'oldIndex' => 1,
            'newIndex' => 0,
        ]);
        $this->assertRedirectContains('/admin/blogger/categories/index');
    }

    /**
     * Test editArticle() saves data and redirects
     *
     * @return void
     * @link \Blogger\Controller\Admin\CategoriesController::editArticle()
     */
    public function testEditArticle(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->patch('/admin/blogger/categories/edit-article/1', [
            'title' => 'Edited Article Title',
        ]);
        $this->assertRedirect();
    }
}
