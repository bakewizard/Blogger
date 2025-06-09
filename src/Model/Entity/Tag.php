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
 * @property \Blogger\Model\Entity\Article[] $articles
 */
class Tag extends Entity
{
    protected array $_accessible = [
        'title' => true,
        'alias' => true,
        'articles' => true,
    ];

    protected function _setAlias($alias)
    {
        return strtolower(Text::slug($alias));
    }
}
