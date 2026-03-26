<?php
declare(strict_types=1);

namespace Blogger\Controller\Admin;

use App\Attribute\Resource;

/**
 * Comments Controller
 *
 * @property \Blogger\Model\Table\CommentsTable $Comments
 * @method \Blogger\Model\Entity\Comment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 * @property \Search\Controller\Component\SearchComponent $Search
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class CommentsController extends AppController
{
    /**
     * Comments list
     *
     * Displays a comments list
     *
     * @return \Cake\Http\Response|void
     */
    #[Resource(label: 'List comments')]
    public function index()
    {
        $query = $this->Comments->find()
                ->contain(['Users', 'Articles', 'ParentComments'])
                ->orderByDesc('Comments.created');

        $comments = $this->paginate($query);

        $this->set(compact('comments'));
    }

    /**
     * View method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    #[Resource(label: 'View a comment')]
    public function view(?string $id = null)
    {
        $comment = $this->Comments->get($id, contain: ['Users', 'Articles', 'ParentComments', 'ChildComments' => ['Users', 'Articles']]);

        $this->set('comment', $comment);
    }

    /**
     * Edit method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    #[Resource(label: 'Edit a comment')]
    public function edit(?string $id = null)
    {
        $comment = $this->Comments->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $comment = $this->Comments->patchEntity($comment, $this->request->getData());
            if ($this->Comments->save($comment)) {
                $this->Flash->success(__('The comment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The comment could not be saved. Please, try again.'));
        }

        $this->set(compact('comment'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    #[Resource(label: 'Delete a comment')]
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comment = $this->Comments->get($id);
        if ($this->Comments->delete($comment)) {
            $this->Flash->success(__('The comment has been deleted.'));
        } else {
            $this->Flash->error(__('The comment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
