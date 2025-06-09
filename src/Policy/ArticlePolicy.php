<?php
declare(strict_types=1);

namespace Blogger\Policy;

use Authorization\IdentityInterface;
use Authorization\Policy\BeforePolicyInterface;
use Authorization\Policy\Result;
use Authorization\Policy\ResultInterface;
use Blogger\Model\Entity\Article;
use Override;

class ArticlePolicy implements BeforePolicyInterface
{
    /**
     * Pre-authorization check
     *
     * @param \App\Model\Entity\User|null $identity
     * @param mixed $resource
     * @param string $action
     * @return \Authorization\Policy\ResultInterface|bool|null
     */
    #[Override]
    public function before(?IdentityInterface $identity, mixed $resource, string $action): ResultInterface|bool|null
    {
        if (!$identity) {
            return true;
        }

        if ($identity->isRoot()) {
            return true;
        }

        return null;
    }

    /**
     * View check
     *
     * @param \App\Model\Entity\User $user
     * @param \Blogger\Model\Entity\Article $article
     * @return \Authorization\Policy\Result|bool
     */
    public function canView(IdentityInterface $user, Article $article): bool|Result
    {
        if ($user->id == $article->author_id) {
            return new Result(true);
        }

        return new Result(false, __('User can only view his own article.'));
    }

    /**
     * Edit check
     *
     * @param \App\Model\Entity\User $user
     * @param \Blogger\Model\Entity\Article $article
     * @return \Authorization\Policy\Result|bool
     */
    public function canEdit(IdentityInterface $user, Article $article): bool|Result
    {
        if ($user->id == $article->author_id) {
            return new Result(true);
        }

        return new Result(false, __('User can only edit his own article.'));
    }

    /**
     * Delete check
     *
     * @param \App\Model\Entity\User $user
     * @param \Blogger\Model\Entity\Article $article
     * @return \Authorization\Policy\Result|bool
     */
    public function canDelete(IdentityInterface $user, Article $article): bool|Result
    {
        if ($user->id == $article->author_id) {
            return new Result(true);
        }

        return new Result(false, __('User can only delete his own article.'));
    }
}
