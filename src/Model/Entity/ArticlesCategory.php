<?php
declare(strict_types=1);

namespace Blogger\Model\Entity;

use Cake\ORM\Entity;

/**
 * ArticlesCategory Entity
 *
 * @property int $article_id
 * @property int $category_id
 *
 * @property \Blogger\Model\Entity\Article $article
 * @property \Blogger\Model\Entity\Category $category
 */
class ArticlesCategory extends Entity
{
    /**
     * @inheritDoc
     */
    protected array $_accessible = [
        'article' => true,
        'category' => true,
    ];
}
