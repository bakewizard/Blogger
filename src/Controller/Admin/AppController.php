<?php
declare(strict_types=1);

namespace Blogger\Controller\Admin;

use App\Controller\Admin\AppController as BaseController;
use Cake\Event\EventInterface;

/**
 * @property \Search\Controller\Component\SearchComponent $Search
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 * @property \Blogger\Model\Table\ArticlesTable $Articles
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

        $this->addCrumb(
            preg_replace('/([A-Z])/', ' ' . '$1', $controller),
            [
                'prefix' => 'Admin',
                'plugin' => 'Blogger',
                'controller' => $controller,
                'action' => 'index',
            ],
        );
        if ($action !== 'index') {
            $this->addCrumb($action);
        }

        if ($controller === 'Articles' && in_array($action, ['view', 'edit', 'delete'])) {
            $id = $request->getParam('pass')[0];
            $article = $this->Articles->get($id);
            $this->Authorization->authorize($article);
        }
    }
}
