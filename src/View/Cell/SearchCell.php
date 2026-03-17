<?php
declare(strict_types=1);

namespace Blogger\View\Cell;

use App\Attribute\Link;
use App\View\Cell\BlockCell as Cell;

/**
 * Search cell
 */
class SearchCell extends Cell
{
    /**
     * Search
     *
     * Displays a search form
     *
     * @return void
     */
    #[Link(summary: 'Search', description: 'Displays a search form')]
    public function display(): void
    {
        $url = ['plugin' => 'Blogger', 'controller' => 'Articles', 'action' => 'search'];

        $this->set(compact('url'));
    }
}
