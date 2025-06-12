<?php
declare(strict_types=1);

namespace Blogger\Model\Entity;

use Cake\ORM\Entity;

/**
 * ArticlesTag Entity
 *
 * @property int $article_id
 * @property int $tag_id
 *
 * @property \Blogger\Model\Entity\Article $article
 * @property \Blogger\Model\Entity\Tag $tag
 * @property \Cake\ORM\Entity $tags_join
 */
class ArticlesTag extends Entity
{
    protected array $_accessible = [
        'article' => true,
        'tag' => true,
    ];
}
