<?php
declare(strict_types=1);

namespace Blogger\Controller\Admin;

use App\Attribute\Resource;
use Override;

/**
 * @property \Search\Controller\Component\SearchComponent $Search
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class DashboardController extends AppController
{
    /**
     * Plugin dashboard
     *
     * Displays the plugin dashboard
     *
     * @return void
     */
    #[Resource(label: 'Blogger dashboard')]
    public function index()
    {
    }

    /**
     * Plugin settings
     *
     * Displays/Sets the plugin settings
     *
     * @return \Cake\Http\Response|void
     */
    #[Override]
    #[Resource(label: 'Blogger settings')]
    public function settings()
    {
        parent::settings();
    }
}
