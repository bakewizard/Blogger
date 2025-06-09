<?php
declare(strict_types=1);

namespace Blogger\Controller;

use Cake\Core\Configure;
use Cake\Database\Expression\QueryExpression;
use Cake\Database\Query;
use Cake\Utility\Hash;

/**
 * Articles Controller
 *
 * @property \Blogger\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppController
{
    public array $paginate = [
        'order' => [
            'Articles.created' => 'desc',
        ],
    ];

    public function initialize(): void
    {
        parent::initialize();
        $this->paginate['limit'] = Configure::read('Blogger.articlesPerPage', 10);
    }

    /**
     * Articles list
     *
     * Displays an articles list
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $articles = $this->paginate($this->Articles->find('published')->contain(['Users']));

        $this->set(compact('articles'));
    }

    public function search()
    {
        $query = $this->Articles->find('published')
                ->find('search', search: $this->request->getQueryParams(), collection: 'frontend')
                ->contain(['Users']);

        $articles = $this->paginate($query);

        $this->set(compact('articles'));

        $this->render('index');
    }

    /**
     * Single article
     *
     * Displays a single article
     *
     * @items Articles
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $article = $this->Articles->findById($id)
                ->find('published')
                ->find('comments', sorting: Configure::read('Blogger.comments.sorting', 'desc'))
                ->contain(['Tags', 'Users'])
                ->firstOrFail();

        $this->set('article', $article);
    }

    public function category($id)
    {
        $categories = $this->Articles->Categories
                ->find('children', for: $id)
                ->select(['id'])
                ->toArray();

        $ids = Hash::extract($categories, '{n}.id');

        array_push($ids, $id);

        $query = $this->Articles->find('published')
                ->contain(['Users'])
                ->innerJoinWith('Categories', function ($q) use ($ids) {
                    return $q->where(['Categories.id IN' => $ids]);
                });

        $articles = $this->paginate($query);

        $this->set(compact('articles'));

        $this->render('index');
    }

    public function tag($alias = null)
    {
        $query = $this->Articles->find('published')
                ->contain(['Users'])
                ->innerJoinWith('Tags', function ($q) use ($alias) {
                    return $q->where(['Tags.alias' => $alias]);
                });

        $articles = $this->paginate($query);

        $this->set(compact('articles'));

        $this->render('index');
    }

    public function user($id = null)
    {
        $query = $this->Articles->find('published')
                ->contain(['Users'])
                ->innerJoinWith('Users', function ($q) use ($id) {
                    return $q->where(['Users.id' => $id]);
                });

        $articles = $this->paginate($query);

        $this->set(compact('articles'));

        $this->render('index');
    }

    public function archive($year, $month = false, $day = false)
    {
        $query = $this->Articles->find('published')
                ->contain(['Users'])
                ->where(function (QueryExpression $exp, Query $q) use ($year, $month, $day) {
                    $exp = $exp->eq($q->func()->year(['Articles.created' => 'identifier']), $year);

                    if ($month) {
                        $exp = $exp->eq($q->func()->month(['Articles.created' => 'identifier']), $month);
                    }

                    if ($day) {
                        $exp = $exp->eq($q->func()->day(['Articles.created' => 'identifier']), $day);
                    }

                    return $exp;
                });

        $articles = $this->paginate($query);

        $this->set(compact('articles'));

        $this->render('index');
    }

    public function addComment()
    {
        $config = Configure::read('Blogger');

        if ($this->request->is('post')) {
            $comment = $this->Articles->Comments->newEntity($this->request->getData());
            $comment->author_ip = $this->request->clientIp();
            if (!$config['comments']['moderation']) {
                $comment->approved = true;
            }

            if ($this->Articles->Comments->save($comment)) {
                if ($config['comments']['moderation']) {
                    $this->Flash->success(__d('blogger', 'Thanks for your comment. It will be available after moderation'));
                } else {
                    $this->Flash->success(__d('blogger', 'Thanks for your comment'));
                }
            } else {
                $this->Flash->error(__d('blogger', 'There was an error while saving your comment. Try again'));
            }

            return $this->redirect($this->referer());
        }
    }
}
