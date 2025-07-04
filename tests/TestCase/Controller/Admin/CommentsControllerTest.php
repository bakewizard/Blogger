<?php
declare(strict_types=1);

namespace Blogger\Test\TestCase\Controller\Admin;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * Blogger\Controller\Admin\CommentsController Test Case
 *
 * @link \Blogger\Controller\Admin\CommentsController
 */
class CommentsControllerTest extends TestCase
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
        'plugin.Blogger.Comments',
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
     * @link \Blogger\Controller\Admin\CommentsController::index()
     */
    public function testIndex(): void
    {
        $this->get('/admin/blogger/comments');

        $this->assertResponseOk();
        $this->assertNotNull($this->viewVariable('comments'));
    }

    /**
     * Test view method
     *
     * @return void
     * @link \Blogger\Controller\Admin\CommentsController::view()
     */
    public function testView(): void
    {
        $this->get('/admin/blogger/comments/view/1');
        $this->assertResponseOk();

        $this->assertNotEmpty($this->viewVariable('comment'));
        $this->assertEquals(1, $this->viewVariable('comment')->id);
    }

    /**
     * Test edit method
     *
     * @return void
     * @link \Blogger\Controller\Admin\CommentsController::edit()
     */
    public function testEditGet(): void
    {
        $this->get('/admin/blogger/comments/edit/1');
        $this->assertResponseOk();
    }

    /**
     * Test edit post method
     *
     * @return void
     * @link \Blogger\Controller\Admin\CommentsController::edit()
     */
    public function testEditPost(): void
    {
        $postData = [
            'body' => 'Updated comment content',
        ];
        $this->post('/admin/blogger/comments/edit/1', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/admin/blogger/comments');
    }

    /**
     * Test delete method
     *
     * @return void
     * @link \Blogger\Controller\Admin\CommentsController::delete()
     */
    public function testDeletePost(): void
    {
        $this->post('/admin/blogger/comments/delete/4');

        $this->assertResponseCode(302);
        $this->assertRedirectContains('/admin/blogger/comments');
    }
}
