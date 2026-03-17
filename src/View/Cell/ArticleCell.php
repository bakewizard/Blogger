<?php
declare(strict_types=1);

namespace Blogger\View\Cell;

use App\Attribute\Link;
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
    #[Link(summary: 'Recent Articles', description: 'Displays a list of recent articles')]
    public function recent(): void
    {
        $articles = $this->fetchTable('Blogger.Articles')
            ->find('published')
            ->limit($this->getLimit())
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
    #[Link(summary: 'Popular (most commented) articles', description: 'Displays a list of popular articles')]
    public function popular(): void
    {
        $articles = $this->fetchTable('Blogger.Articles')
            ->find('published')
            ->limit($this->getLimit())
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
    #[Link(summary: 'Related Articles', description: 'Displays a list of related (have common tags) articles')]
    public function related(): void
    {
        $id = $this->request->getParam('id') !== null
            ? (int)$this->request->getParam('id')
            : null;

        if ($id === null) {
            $this->set('articles', []);

            return;
        }

        $articles = $this->fetchTable('Blogger.Articles')
            ->find('related', id: $id)
            ->limit($this->getLimit())
            ->toArray();

        $this->set(compact('articles'));
    }

    /**
     * Returns the configured article limit from block params.
     *
     * @return int
     */
    private function getLimit(): int
    {
        return (int)($this->block->params['numberOfArticlesToShow'] ?? 5);
    }
}
