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
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected array $_accessible = [
        'article' => true,
        'category' => true,
    ];
}
