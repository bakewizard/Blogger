<?php
declare(strict_types=1);

namespace Blogger\Controller\Admin;

/**
 * Categories Controller
 *
 * @property \Blogger\Model\Table\CategoriesTable $Categories
 * @property \Search\Controller\Component\SearchComponent $Search
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class CategoriesController extends AppController
{
    /**
     * Categories list
     *
     * Displays a categories list
     *
     * @param string $id Category id.
     * @return \Cake\Http\Response|void
     */
    public function index(?string $id = null)
    {
        $categories = $this->Categories->find()->where(['parent_id is' => $id])->orderByAsc('lft')->toArray();

        $crumbs = [];
        if ($id) {
            $crumbs = $this->Categories->find('path', for: $id)->toArray();
        }

        if ($categories) {
            $this->set(compact('categories', 'crumbs'));
        } elseif (empty($categories) && is_null($id)) {
            $this->set(compact('categories'));
            $this->Flash->error(__('There are not any categories at the moment'));
        } else {
            return $this->redirect(['action' => 'view', $id]);
        }
    }

    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        if ($id === null) {
            return $this->redirect(['action' => 'index']);
        }

        $category = $this->Categories->get($id, contain: ['Articles' => [
                'sort' => [
                    'IF(Articles.sort_order = 0, 1, 0)' => 'asc',
                    'Articles.sort_order' => 'asc',
                ],
        ]]);

        $crumbs = $this->Categories->find('path', for: $id)->toArray();

        $this->set(compact('category', 'crumbs'));
    }

    /**
     * New category
     *
     * Creates a new category
     *
     * @return \Cake\Http\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $category = $this->Categories->newEmptyEntity();
        if ($this->request->is('post')) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The category could not be saved. Please, try again.'));
            }
        }
        $parentCategories = $this->Categories->ParentCategories->find('treeList', spacer: '-', limit: 200);
        $this->set(compact('category', 'parentCategories'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $category = $this->Categories->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The category could not be saved. Please, try again.'));
            }
        }
        $parentCategories = $this->Categories->ParentCategories->find('treeList', spacer: '-', limit: 200);
        $this->set(compact('category', 'parentCategories'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $category = $this->Categories->get($id);
        if ($this->Categories->delete($category)) {
            $this->Flash->success(__('The category has been deleted.'));
        } else {
            $this->Flash->error(__('The category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Move method
     *
     * Moves a category up or down in the list.
     *
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function move()
    {
        $this->request->allowMethod(['post', 'put']);

        $data = $this->request->getData();
        $id = intval($data['id']);
        $oldIndex = intval($data['oldIndex']);
        $newIndex = intval($data['newIndex']);

        $category = $this->Categories->get($id);

        if ($newIndex < $oldIndex) {
            $result = $this->Categories->moveUp($category, $oldIndex - $newIndex);
        } elseif ($newIndex > $oldIndex) {
            $result = $this->Categories->moveDown($category, $newIndex - $oldIndex);
        }

        if (isset($result)) {
            $this->Flash->success(__('The category has been moved.'));
        } else {
            $this->Flash->error(__('The category could not be moved. Please, try again.'));
        }

        return $this->redirect(['action' => 'index', $category->parent_id]);
    }

    /**
     * Edit article
     *
     * Edits an article in the category.
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editArticle(?string $id = null)
    {
        $article = $this->Categories->Articles->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $article = $this->Categories->Articles->patchEntity($article, $this->request->getData());
            if (!$this->Categories->Articles->save($article)) {
                $this->Flash->error(__('The Article could not be edited. Please, try again.'));
            }

            return $this->redirect($this->referer());
        }
    }
}
