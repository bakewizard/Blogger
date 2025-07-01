<?php
declare(strict_types=1);

namespace Blogger\Test\TestCase\Controller\Admin;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * Blogger\Controller\Admin\TagsController Test Case
 *
 * @link \Blogger\Controller\Admin\TagsController
 */
class TagsControllerTest extends TestCase
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
        'plugin.Blogger.Tags',
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
     * @link \Blogger\Controller\Admin\TagsController::index()
     */
    public function testIndex(): void
    {
        $this->get('/admin/blogger/tags');

        $this->assertResponseOk();
        $this->assertNotNull($this->viewVariable('tags'));
    }

    /**
     * Test add method
     *
     * @return void
     * @link \Blogger\Controller\Admin\TagsController::add()
     */
    public function testAddGet(): void
    {
        $this->get('/admin/blogger/tags/add');
        $this->assertResponseOk();
    }

    /**
     * Test add post method
     *
     * @return void
     * @link \Blogger\Controller\Admin\TagsController::add()
     */
    public function testAddPost(): void
    {
        $postData = [
            'title' => 'Some Test Tag',
            'alias' => 'some-test-tag',

        ];
        $this->post('/admin/blogger/tags/add', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/admin/blogger/tags');
    }

    /**
     * Test edit method
     *
     * @return void
     * @link \Blogger\Controller\Admin\TagsController::edit()
     */
    public function testEditGet(): void
    {
        $this->get('/admin/blogger/tags/edit/1');
        $this->assertResponseOk();
    }

    /**
     * Test edit post method
     *
     * @return void
     * @link \Blogger\Controller\Admin\TagsController::edit()
     */
    public function testEditPost(): void
    {
        $postData = [
            'title' => 'Some Modified Test Tag',
        ];
        $this->post('/admin/blogger/tags/edit/1', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/admin/blogger/tags');
    }

    /**
     * Test delete method
     *
     * @return void
     * @link \Blogger\Controller\Admin\TagsController::delete()
     */
    public function testDeletePost(): void
    {
        $this->post('/admin/blogger/tags/delete/1');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/admin/blogger/tags');
    }
}
