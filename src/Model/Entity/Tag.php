<?php
declare(strict_types=1);

namespace Blogger\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;

/**
 * Tag Entity
 *
 * @property int $id
 * @property string $title
 * @property string|null $alias
 *
 * @property array<\Blogger\Model\Entity\Article> $articles
 * @property int $articles_count
 * @property \Cake\ORM\Entity $_joinData
 * @property array<\Cake\ORM\Entity> $_i18n
 */
class Tag extends Entity
{
    /**
     * @inheritDoc
     */
    protected array $_accessible = [
        'title' => true,
        'alias' => true,
        'articles' => true,
    ];

    /**
     * Sets the alias attribute after converting it to a URL-friendly slug in lowercase.
     *
     * @param string $alias The alias value to be set.
     * @return string The processed alias, slugged and in lowercase.
     * @see \Blogger\Model\Entity\Tag::$alias
     */
    protected function _setAlias(string $alias): string
    {
        return strtolower(Text::slug($alias));
    }
}
