<?php
declare(strict_types=1);

namespace Blogger\Controller;

use App\Attribute\Link;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Utility\Hash;
use Override;
use const CAL_GREGORIAN;

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

    /**
     * @inheritDoc
     */
    #[Override]
    public function initialize(): void
    {
        parent::initialize();
        $this->paginate['limit'] = Configure::read('Blogger.articlesPerPage', 10);
    }

    /**
     * Index method.
     *
     * Displays a paginated list of published articles including their authors.
     *
     * @return \Cake\Http\Response|void
     */
    #[Link(summary: 'Articles list', description: 'Displays a list of published articles')]
    public function index()
    {
        $articles = $this->paginate($this->Articles->find('published')->contain(['Users']));

        $this->set(compact('articles'));
    }

    /**
     * Search articles
     *
     * Displays a list of articles based on search criteria
     *
     * @return \Cake\Http\Response|void
     */
    public function search()
    {
        $query = $this->Articles->find('published')
            ->find('search', search: $this->request->getQueryParams(), collection: 'frontend')
            ->contain(['Users']);

        $articles = $this->paginate($query);

        $this->set(compact('articles'));

        return $this->render('index');
    }

    /**
     * View method.
     *
     * Retrieves a single published article by its ID. The article includes
     * associated Tags and Users and loads comments using the `comments`
     * finder with configurable sorting.
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When the article is not found.
     */
    #[Link(summary: 'Single article', description: 'Displays a single article', picker: 'Articles')]
    public function view(?string $id = null)
    {
        $article = $this->Articles->findById($id)
            ->find('published')
            ->find('comments', sorting: Configure::read('Blogger.comments.sorting', 'desc'))
            ->contain(['Tags', 'Users'])
            ->firstOrFail();

        $this->set('article', $article);
    }

    /**
     * Category articles
     *
     * Displays a list of articles in a specific category and its subcategories
     *
     * @param int $id Category id.
     * @return \Cake\Http\Response|void
     */
    public function category(int $id)
    {
        $categories = $this->Articles->Categories
            ->find('children', for: $id)
            ->where(['enabled' => true])
            ->select(['id'])
            ->toArray();

        $ids = Hash::extract($categories, '{n}.id');
        $ids[] = $id;

        $query = $this->Articles->find('published')
            ->contain(['Users'])
            ->innerJoinWith('Categories', function ($q) use ($ids) {
                return $q->where(['Categories.id IN' => $ids]);
            });

        $articles = $this->paginate($query);

        $this->set(compact('articles'));

        return $this->render('index');
    }

    /**
     * Tag articles
     *
     * Displays a list of articles with a specific tag
     *
     * @param string $alias Tag alias.
     * @return \Cake\Http\Response|void
     */
    public function tag(string $alias)
    {
        $query = $this->Articles->find('published')
            ->contain(['Users'])
            ->innerJoinWith('Tags', function ($q) use ($alias) {
                return $q->where(['Tags.alias' => $alias]);
            });

        $articles = $this->paginate($query);

        $this->set(compact('articles'));

        return $this->render('index');
    }

    /**
     * User articles
     *
     * Displays a list of articles by a specific user
     *
     * @param int $id User id.
     * @return \Cake\Http\Response|void
     */
    public function user(int $id)
    {
        $query = $this->Articles->find('published')
            ->contain(['Users'])
            ->innerJoinWith('Users', function ($q) use ($id) {
                return $q->where(['Users.id' => $id]);
            });

        $articles = $this->paginate($query);

        $this->set(compact('articles'));

        return $this->render('index');
    }

    /**
     * Archive articles
     *
     * Displays a list of articles from a specific year, month, and day.
     * Uses date range conditions instead of EXTRACT() to allow index usage.
     *
     * @param int $year Year.
     * @param int|null $month Month (1-12).
     * @param int|null $day Day (1-31).
     * @return \Cake\Http\Response|void
     */
    public function archive(int $year = 0, ?int $month = null, ?int $day = null)
    {
        $year = $year === 0 ? (int)date('Y') : $year;
        $month = $month !== null ? max(1, min(12, $month)) : null;
        $day = $day !== null ? max(1, min(31, $day)) : null;

        if ($day !== null && $month !== null) {
            $start = sprintf('%04d-%02d-%02d 00:00:00', $year, $month, $day);
            $end = sprintf('%04d-%02d-%02d 23:59:59', $year, $month, $day);
        } elseif ($month !== null) {
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $start = sprintf('%04d-%02d-01 00:00:00', $year, $month);
            $end = sprintf('%04d-%02d-%02d 23:59:59', $year, $month, $daysInMonth);
        } else {
            $start = sprintf('%04d-01-01 00:00:00', $year);
            $end = sprintf('%04d-12-31 23:59:59', $year);
        }

        $query = $this->Articles->find('published')
            ->contain(['Users'])
            ->where([
                'Articles.created >=' => $start,
                'Articles.created <=' => $end,
            ]);

        $articles = $this->paginate($query);

        $this->set(compact('articles'));

        return $this->render('index');
    }

    /**
     * Add comment
     *
     * Adds a comment to an article
     *
     * @return \Cake\Http\Response|void Redirects on successful add, renders view otherwise.
     */
    public function addComment()
    {
        $this->request->allowMethod('post');

        $config = Configure::read('Blogger');

        $articleId = (int)$this->request->getData('article_id');

        if (!$articleId) {
            $this->Flash->error(__d('blogger', 'There was an error while saving your comment. Try again'));

            return $this->redirect($this->referer());
        }

        try {
            $this->Articles->findById($articleId)->find('published')->firstOrFail();
        } catch (RecordNotFoundException) {
            $this->Flash->error(__d('blogger', 'There was an error while saving your comment. Try again'));

            return $this->redirect($this->referer());
        }

        $comment = $this->Articles->Comments->newEntity($this->request->getData());
        $comment->author_ip = $this->request->clientIp();

        if (!$config['comments']['moderation']) {
            $comment->approved = true;
        }

        if ($this->Articles->Comments->save($comment)) {
            $message = $config['comments']['moderation']
                ? __d('blogger', 'Thanks for your comment. It will be available after moderation')
                : __d('blogger', 'Thanks for your comment');

            $this->Flash->success($message);
        } else {
            $this->Flash->error(__d('blogger', 'There was an error while saving your comment. Try again'));
        }

        return $this->redirect($this->referer());
    }
}
