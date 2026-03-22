<?php
declare(strict_types=1);

namespace Blogger\Test\TestCase\Policy;

use App\Model\Entity\User;
use Authorization\Policy\Result;
use Blogger\Model\Entity\Article;
use Blogger\Policy\ArticlePolicy;
use Cake\TestSuite\TestCase;

/**
 * Blogger\Policy\ArticlePolicy Test Case
 *
 * before():
 *   - anonymous (null) -> true
 *   - Root (isRoot=true) -> true
 *   - others -> null (passes to canView/canEdit/canDelete)
 *
 * canView/canEdit/canDelete:
 *   - own article (user.id == article.author_id) -> true
 *   - other's article -> false
 *
 * @uses \Blogger\Policy\ArticlePolicy
 */
class ArticlePolicyTest extends TestCase
{
    private ArticlePolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ArticlePolicy();
    }

    private function makeUser(int $id, bool $isRoot = false): User
    {
        $user = new User(['id' => $id, 'role_id' => $isRoot ? 1 : 2]);
        $user->clean();

        return $user;
    }

    private function makeArticle(int $authorId): Article
    {
        $article = new Article(['id' => 1, 'author_id' => $authorId]);
        $article->clean();

        return $article;
    }

    // -------------------------------------------------------------------------
    // before()
    // -------------------------------------------------------------------------

    /**
     * Anonymous identity -> true.
     */
    public function testBeforeAllowsAnonymous(): void
    {
        $result = $this->policy->before(null, new Article(), 'canView');
        $this->assertTrue($result);
    }

    /**
     * Root -> true, bypasses all checks.
     */
    public function testBeforeAllowsRoot(): void
    {
        $root = $this->makeUser(1, true);
        $result = $this->policy->before($root, new Article(), 'canEdit');
        $this->assertTrue($result);
    }

    /**
     * Regular user -> null, passes control to canView/canEdit/canDelete.
     */
    public function testBeforeReturnsNullForRegularUser(): void
    {
        $user = $this->makeUser(2);
        $result = $this->policy->before($user, new Article(), 'canView');
        $this->assertNull($result);
    }

    // -------------------------------------------------------------------------
    // canView()
    // -------------------------------------------------------------------------

    /**
     * canView() allows viewing own article.
     */
    public function testCanViewAllowsOwnArticle(): void
    {
        $user = $this->makeUser(5);
        $article = $this->makeArticle(5);

        $result = $this->policy->canView($user, $article);
        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->getStatus());
    }

    /**
     * canView() denies viewing another user's article.
     */
    public function testCanViewDeniesOtherArticle(): void
    {
        $user = $this->makeUser(5);
        $article = $this->makeArticle(6);

        $result = $this->policy->canView($user, $article);
        $this->assertInstanceOf(Result::class, $result);
        $this->assertFalse($result->getStatus());
    }

    // -------------------------------------------------------------------------
    // canEdit()
    // -------------------------------------------------------------------------

    /**
     * canEdit() allows editing own article.
     */
    public function testCanEditAllowsOwnArticle(): void
    {
        $user = $this->makeUser(5);
        $article = $this->makeArticle(5);

        $result = $this->policy->canEdit($user, $article);
        $this->assertTrue($result->getStatus());
    }

    /**
     * canEdit() denies editing another user's article.
     */
    public function testCanEditDeniesOtherArticle(): void
    {
        $user = $this->makeUser(5);
        $article = $this->makeArticle(6);

        $result = $this->policy->canEdit($user, $article);
        $this->assertFalse($result->getStatus());
    }

    // -------------------------------------------------------------------------
    // canDelete()
    // -------------------------------------------------------------------------

    /**
     * canDelete() allows deleting own article.
     */
    public function testCanDeleteAllowsOwnArticle(): void
    {
        $user = $this->makeUser(5);
        $article = $this->makeArticle(5);

        $result = $this->policy->canDelete($user, $article);
        $this->assertTrue($result->getStatus());
    }

    /**
     * canDelete() denies deleting another user's article.
     */
    public function testCanDeleteDeniesOtherArticle(): void
    {
        $user = $this->makeUser(5);
        $article = $this->makeArticle(6);

        $result = $this->policy->canDelete($user, $article);
        $this->assertFalse($result->getStatus());
    }
}
