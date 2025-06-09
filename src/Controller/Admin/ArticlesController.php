<?php
declare(strict_types=1);

namespace Blogger\Controller\Admin;

/**
 * Articles Controller
 *
 * @property \Blogger\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppController
{
    /**
     * Articles list
     *
     * Displays an articles list
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $query = $this->Articles->find('search', search: $this->request->getQueryParams(), collection: 'backend')
                ->contain(['Users', 'Categories']);

        $articles = $this->paginate($query);

        $this->set(compact('articles'));
    }

    /**
     * View method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $article = $this->Articles->get($id);

        $this->set('article', $article);
    }

    /**
     * New article
     *
     * Creates a new article
     *
     * @return \Cake\Http\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $article = $this->Articles->newEmptyEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            $article->author_id = $this->Authentication->getIdentityData('id');
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('The article has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The article could not be saved. Please, try again.'));
            }
        }
        $tags = $this->Articles->Tags->find('list', limit: 200);
        $categories = $this->Articles->Categories->find('treeList', spacer: '-- ', limit: 200);

        $this->set(compact('article', 'categories', 'tags'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $article = $this->Articles->get($id, contain: ['Categories', 'Tags']);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('The article has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The article could not be saved. Please, try again.'));
            }
        }
        $users = $this->Articles->Users->find('list', limit: 200);
        $tags = $this->Articles->Tags->find('list', limit: 200);
        $categories = $this->Articles->Categories->find('treeList', spacer: '-- ', limit: 200);

        $this->set(compact('article', 'categories', 'tags', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The article has been deleted.'));
        } else {
            $this->Flash->error(__('The article could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
