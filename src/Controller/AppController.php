<?php
declare(strict_types=1);

namespace Blogger\Controller;

use App\Controller\AppController as BaseController;
use Cake\Event\EventInterface;

class AppController extends BaseController
{
    /**
     * Before render callback.
     *
     * @param \Cake\Event\EventInterface $event The beforeRender event.
     * @return void
     */
    public function beforeRender(EventInterface $event)
    {
        $action = $this->request->getParam('action');

        $this->addCrumb(__d('blogger', 'Blog'), ['plugin' => 'Blogger', 'controller' => 'Articles', 'action' => 'index']);

        if ($action === 'view') {
            $this->addCrumb($this->viewBuilder()->getVar('article')->title);
        }

        parent::beforeRender($event);
    }
}
