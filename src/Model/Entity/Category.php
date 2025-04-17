<?php

declare(strict_types=1);

namespace Blogger\Model\Entity;

use Cake\ORM\Entity;

/**
 * Category Entity
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int $lft
 * @property int $rght
 * @property string $name
 * @property string|null $description
 * @property string|null $alias
 * @property bool $enabled
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $seo_keywords
 * @property int $articles_count
 *
 * @property \Blogger\Model\Entity\ParentBloggerCategory $parent_blogger_category
 * @property \Blogger\Model\Entity\ChildBloggerCategory[] $child_blogger_categories
 */
class Category extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'name' => true,
        'description' => true,
        'alias' => true,
        'enabled' => true,
        'seo_title' => true,
        'seo_description' => true,
        'seo_keywords' => true,
        'articles_count' => true,
        'parent_category' => true,
        'child_categories' => true,
        'articles' => true
    ];
}
