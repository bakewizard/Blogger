<?php

declare(strict_types=1);

namespace Blogger\View\Cell;

use App\View\Cell\BlockCell as Cell;

/**
 * Article cell
 */
class ArticleCell extends Cell
{

    /**
     * Recent articles
     * 
     * Displays a recent articles list
     *
     * @return void
     */
    public function recent()
    {
        $limit = intval($this->block->params['numberOfArticlesToShow'] ?? 5);

        $articles = $this->fetchTable('Blogger.Articles')
                ->find('published')
                ->limit($limit)
                ->orderByDesc('Articles.created')
                ->toArray();

        $this->set(compact('articles'));
    }

    /**
     * Popular (most commented) articles
     * 
     * Displays a popular articles list
     *
     * @return void
     */
    public function popular()
    {
        $limit = intval($this->block->params['numberOfArticlesToShow'] ?? 5);

        $articles = $this->fetchTable('Blogger.Articles')
                ->find('published')
                ->limit($limit)
                ->orderByDesc('Articles.comments_count')
                ->toArray();

        $this->set(compact('articles'));
    }

    /**
     * Related articles
     * 
     * Displays a related (have common tags) articles list
     *
     * @return void
     */
    public function related()
    {
        $id = intval($this->request->getParam('id')) ?? null;

        $limit = intval($this->block->params['numberOfArticlesToShow'] ?? 5);

        $articles = $this->fetchTable('Blogger.Articles')
                ->find('related', id: $id)
                ->limit($limit)
                ->toArray();

        $this->set(compact('articles'));
    }
}
