<?php

declare(strict_types=1);

namespace Blogger;

use App\Core\CmsPlugin;
use App\Core\Configure\Engine\DbConfig;
use Blogger\Event\CommentListener;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/**
 * Plugin for Blogger
 */
class BloggerPlugin extends CmsPlugin
{

    protected ?string $name = 'Blogger';
    protected bool $consoleEnabled = false;
    protected bool $middlewareEnabled = false;
    protected bool $servicesEnabled = false;

    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        Cache::setConfig('blogger', [
            'className' => 'File',
            'prefix' => 'blogger_',
            'path' => CACHE . 'blogger' . DS,
            'duration' => '+6 months'
        ]);

        Configure::config('db', new DbConfig(null, 'blogger'));
        Configure::load('Blogger', 'db');

        FactoryLocator::get('Table')->get('Blogger.Comments')->getEventManager()->on(new CommentListener());
    }

    public function routes(RouteBuilder $routes): void
    {
        parent::routes($routes);

        $routes->prefix('Admin', function (RouteBuilder $builder) {
            $builder->plugin($this->name, function (RouteBuilder $builder) {
                $builder->applyMiddleware('auth');
                $builder->connect('/', ['controller' => 'Dashboard']);
                $builder->fallbacks(DashedRoute::class);
            });
        });
    }
}
