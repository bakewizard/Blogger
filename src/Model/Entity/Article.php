<?php
declare(strict_types=1);

namespace Blogger\Model\Entity;

use Cake\Collection\Collection;
use Cake\ORM\Entity;

/**
 * Article Entity
 *
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property string $body
 * @property string|null $excerpt
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $seo_keywords
 * @property int|null $sort_order
 * @property bool $published
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \Blogger\Model\Entity\Category[] $categories
 * @property \Blogger\Model\Entity\Tag[] $tags
 */
class Article extends Entity
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
        'author_id' => true,
        'title' => true,
        'body' => true,
        'excerpt' => true,
        'seo_title' => true,
        'seo_description' => true,
        'seo_keywords' => true,
        'sort_order' => true,
        'published' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'categories' => true,
        'tags' => true,
        'comments' => true,
        'tag_string' => true,
    ];

    protected function _getTagString()
    {
        if (isset($this->_fields['tag_string'])) {
            return $this->_fields['tag_string'];
        }
        if (empty($this->tags)) {
            return '';
        }
        $tags = new Collection($this->tags);
        $str = $tags->reduce(function ($string, $tag) {
            return $string . $tag->title . ', ';
        }, '');

        return trim($str, ', ');
    }
}
