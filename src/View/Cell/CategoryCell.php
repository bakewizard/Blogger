<?php
declare(strict_types=1);

namespace Blogger\View\Cell;

use App\Attribute\Link;
use App\View\Cell\BlockCell as Cell;

/**
 * Category cell
 */
class CategoryCell extends Cell
{
    /**
     * Articles categories
     *
     * Displays a categories list/tree
     *
     * @return void
     */
    #[Link(summary: 'Articles categories', description: 'Displays a list of article categories')]
    public function display(): void
    {
        $showHierarchy = $this->block->params['showHierarchy'] ?? false;
        $model = $this->fetchTable('Blogger.Categories');

        $query = $showHierarchy ? $model->find('threaded') : $model->find()->where(['parent_id is' => null]);

        $categories = $query->where(['enabled' => 1])->orderByAsc('lft')->toArray();

        $this->set(compact('categories'));
    }
}
