<?php
declare(strict_types=1);

namespace Blogger;

use App\Core\CmsPlugin;
use Blogger\Event\CommentListener;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\ORM\TableRegistry;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Override;

/**
 * Plugin for Blogger
 */
class BloggerPlugin extends CmsPlugin
{
    protected ?string $name = 'Blogger';
    protected bool $consoleEnabled = false;
    protected bool $middlewareEnabled = false;
    protected bool $servicesEnabled = false;

    /**
     * @inheritDoc
     */
    #[Override]
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        Configure::load('Blogger', 'db');

        TableRegistry::getTableLocator()->get('Blogger.Comments')->getEventManager()->on(new CommentListener());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function routes(RouteBuilder $routes): void
    {
        parent::routes($routes);

        $routes->prefix('Admin', function (RouteBuilder $builder): void {
            $builder->plugin($this->name ?? 'Blogger', function (RouteBuilder $builder): void {
                $builder->applyMiddleware('auth');
                $builder->connect('/', ['controller' => 'Dashboard']);
                $builder->fallbacks(DashedRoute::class);
            });
        });
    }
}
