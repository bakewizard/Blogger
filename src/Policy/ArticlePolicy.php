<?php

declare(strict_types=1);

namespace Blogger\Policy;

use Blogger\Model\Entity\Article;
use Authorization\IdentityInterface;
use Authorization\Policy\BeforePolicyInterface;
use Authorization\Policy\Result;
use Authorization\Policy\ResultInterface;

class ArticlePolicy implements BeforePolicyInterface
{

    #[\Override]
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

    public function canView(IdentityInterface $user, Article $article)
    {
        if ($user->id == $article->author_id) {
            return new Result(true);
        }

        return new Result(false, __('User can only view his own article.'));
    }

    public function canEdit(IdentityInterface $user, Article $article)
    {
        if ($user->id == $article->author_id) {
            return new Result(true);
        }

        return new Result(false, __('User can only edit his own article.'));
    }

    public function canDelete(IdentityInterface $user, Article $article)
    {
        if ($user->id == $article->author_id) {
            return new Result(true);
        }

        return new Result(false, __('User can only delete his own article.'));
    }
}
