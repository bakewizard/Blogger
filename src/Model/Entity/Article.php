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
 * @property array<\Blogger\Model\Entity\Category> $categories
 * @property array<\Blogger\Model\Entity\Tag> $tags
 * @property int $comments_count
 * @property string $tag_string
 * @property \Blogger\Model\Entity\ArticlesTag $_joinData
 * @property array<\Blogger\Model\Entity\Comment> $comments
 * @property array<\Blogger\Model\Entity\Comment> $approved_comments
 * @property array<\Cake\ORM\Entity> $_i18n
 */
class Article extends Entity
{
    /**
     * @inheritDoc
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

    /**
     * Returns a comma-separated string of tag titles associated with the article.
     *
     * If the 'tag_string' field is already set, it returns its value.
     * If there are no tags, it returns an empty string.
     * Otherwise, it concatenates the titles of all tags, separated by commas.
     *
     * @return string Comma-separated list of tag titles.
     * @see \Blogger\Model\Entity\Article::$tag_string
     */
    protected function _getTagString(): string
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
