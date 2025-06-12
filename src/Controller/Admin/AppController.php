<?php
declare(strict_types=1);

namespace Blogger\Controller\Admin;

use App\Controller\Admin\AppController as BaseController;
use Cake\Event\EventInterface;

/**
 * @property \Search\Controller\Component\SearchComponent $Search
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class AppController extends BaseController
{
    /**
     * Before filter callback.
     *
     * @param \Cake\Event\EventInterface $event The beforeRender event.
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $request = $this->getRequest();
        $controller = $request->getParam('controller');
        $action = $request->getParam('action');
        if ($controller === 'Articles' && in_array($action, ['view', 'edit', 'delete'])) {
            $id = $request->getParam('pass')[0];
            $articlesTable = $this->fetchTable('Articles');
            $article = $articlesTable->get($id);
            $this->Authorization->authorize($article);
        }
    }
}
