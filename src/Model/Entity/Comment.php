<?php
declare(strict_types=1);

namespace Blogger\Model\Entity;

use Cake\ORM\Entity;

/**
 * Comment Entity
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int|null $user_id
 * @property int $article_id
 * @property int $lft
 * @property int $rght
 * @property int $level
 * @property string|null $author_name
 * @property string|null $author_email
 * @property string|null $author_ip
 * @property string $content
 * @property bool $approved
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \Blogger\Model\Entity\Article $article
 * @property \Blogger\Model\Entity\Comment $parent_comment
 * @property \Blogger\Model\Entity\Comment[] $child_comments
 */
class Comment extends Entity
{
    protected array $_accessible = [
        'parent_id' => true,
        'user_id' => true,
        'article_id' => true,
        'lft' => true,
        'rght' => true,
        'level' => true,
        'author_name' => true,
        'author_email' => true,
        'author_ip' => true,
        'content' => true,
        'approved' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'article' => true,
        'parent_comment' => true,
        'child_comments' => true,
    ];
}
