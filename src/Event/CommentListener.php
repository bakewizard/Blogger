<?php
declare(strict_types=1);

namespace Blogger\Event;

use ArrayObject;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;
use Override;

class CommentListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function implementedEvents(): array
    {
        return [
            'Model.afterSave' => 'onAfterSave',
        ];
    }

    /**
     * Triggered after a comment is saved.
     *
     * If the comment is new and notifications are enabled, it sends an email notification.
     *
     * @param \Cake\Event\EventInterface $event The event that was triggered.
     * @param \Blogger\Model\Entity\Comment $entity The entity that was saved.
     * @param \ArrayObject $options Additional options passed to the save method.
     * @return void
     */
    public function onAfterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $config = Configure::read('Blogger');

        if ($entity->isNew() && $config['comments']['notification']) {
            $this->sendNotifyMail($entity);
        }
    }

    /**
     * Sends a notification email when a new comment or reply is posted.
     *
     * If the comment is a reply, it notifies the original commenter.
     * If it's a new comment on an article, it notifies the article author.
     *
     * @param \Blogger\Model\Entity\Comment $entity The comment entity.
     * @return void
     */
    private function sendNotifyMail(EntityInterface $entity): void
    {
        $email = new Mailer();

        $email->setEmailFormat('html');

        if (isset($entity->parent_id)) {
            $comments = TableRegistry::getTableLocator()->get('Blogger.Comments');
            $comment = $comments->get($entity->parent_id, ['contain' => ['Articles', 'Users']]);
            $email
                    ->setSubject(__d('blogger', 'New reply to your comment'))
                    ->addTo($comment->author_email)
                    ->setViewVars(['comment' => $entity, 'article' => $comment->article]);

            $email->viewBuilder()
                    ->setTemplate('Blogger.reply_notify');
        } else {
            $articles = TableRegistry::getTableLocator()->get('Blogger.Articles');
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
