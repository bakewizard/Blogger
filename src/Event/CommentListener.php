<?php

declare(strict_types=1);

namespace Blogger\Event;

use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Mailer\Mailer;

class CommentListener implements EventListenerInterface
{

    #[\Override]
    public function implementedEvents(): array
    {
        return [
            'Model.afterSave' => 'onAfterSave'
        ];
    }

    public function onAfterSave(EventInterface $event, EntityInterface $entity, \ArrayObject $options)
    {
        $config = Configure::read('Blogger');

        if ($entity->isNew() && $config['comments']['notification']) {
            $this->sendNotifyMail($entity);
        }
    }

    private function sendNotifyMail($entity)
    {
        $email = new Mailer();

        $email->setEmailFormat('html');

        if (isset($entity->parent_id)) {
            $comments = FactoryLocator::get('Table')->get('Blogger.Comments');
            $comment = $comments->get($entity->parent_id, ['contain' => ['Articles', 'Users']]);
            $email
                    ->setSubject(__d('blogger', 'New reply to your comment'))
                    ->addTo($comment->author_email)
                    ->setViewVars(['comment' => $entity, 'article' => $comment->article]);

            $email->viewBuilder()
                    ->setTemplate('Blogger.reply_notify');
        } else {
            $articles = FactoryLocator::get('Table')->get('Blogger.Articles');
            $article = $articles->get($entity->article_id, ['contain' => ['Users']]);
            $email
                    ->setSubject(__d('blogger', 'New comment to your article'))
                    ->addTo($article->user->email)
                    ->setViewVars(['comment' => $entity, 'article' => $article]);

            $email->viewBuilder()
                    ->setTemplate('Blogger.comment_notify');
        }

        $email->deliver();
    }
}
