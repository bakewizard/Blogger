<?php

declare(strict_types=1);

namespace Blogger\View\Cell;

use App\View\Cell\BlockCell as Cell;

/**
 * Tag cell
 */
class TagCell extends Cell
{

    /**
     * Articles tags
     * 
     * Displays a tags list
     *
     * @return void
     */
    public function display()
    {
        $limit = $this->block->params['numberOfTagsToShow'] ?? 5;

        $tags = $this->fetchTable('Blogger.Tags')
                ->find()
                ->limit($limit)
                ->orderByDesc('Tags.articles_count')
                ->toArray();

        $this->set(compact('tags'));
    }

}
