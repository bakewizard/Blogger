<?php

declare(strict_types=1);

namespace Blogger\View\Cell;

use App\View\Cell\BlockCell as Cell;

/**
 * Comment cell
 */
class CommentCell extends Cell
{

    /**
     * Recent comments
     * 
     * Displays a recent comments list
     *
     * @return void
     */
    public function display()
    {
        $limit = intval($this->block->params['numberOfCommentsToShow'] ?? 5);

        $comments = $this->fetchTable('Blogger.Comments')
                ->find()
                ->contain(['Users', 'Articles'])
                ->limit($limit)
                ->orderByDesc('Comments.created')
                ->toArray();

        $this->set(compact('comments'));
    }
}
