<?php

namespace App\Providers;

use AdminNavigation;
use Illuminate\Routing\Router;
use Meta;
use SleepingOwl\Admin\Admin;
use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;

class AdminSectionsServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $widgets = [
        \App\Widgets\NotificationsNavigation::class
    ];

    /**
     * @var array
     */
    protected $sections =
    [
        \App\User::class => 'App\Http\Sections\Users',
        \App\Admin::class => 'App\Http\Sections\Admin',
        \App\Product::class => 'App\Http\Sections\Product',
        \App\AdminNotificationHistory::class => 'App\Http\Sections\AdminNotificationTabs',
        \App\Category::class => 'App\Http\Sections\Category'
    ];

    /**
     * Register sections.
     *
     * @param Admin $admin
     * @return void
     */
    public function boot(Admin $admin)
    {

        parent::boot($admin);
        Meta::addJs('custom', 'js/admin.js', [], true);
        $this->registerRoutes();
        $this->registerNavigations();
        $this->registerWidgets();
    }

    private function registerRoutes()
    {
        $this->app['router']->group
        (
            [
                'prefix' => config('sleeping_owl.url_prefix'),
                'middleware' => config('sleeping_owl.middleware')
            ],
            function (Router $router)
            {
                /*
                 * AdminController
                 */
                $router->get('dashboard', ['as' => 'admin.dashboard', 'uses' => '\App\Http\Controllers\Admin\AdminController@dashboard']);

                $router->get('ga', ['as' => 'admin.ga', 'uses' => '\App\Http\Controllers\Admin\AdminController@getGA']);

                $router->get('quit', ['as' => 'admin.quit', 'uses' => '\App\Http\Controllers\Admin\AdminController@quitAdmin']);

                $router->post('admins/new', ['as' => 'admin.admins.new',
                    'uses' => '\App\Http\Controllers\Admin\AdminController@createNewAdmin']);

                /*
                 * GatheringController
                 */
                $router->get('get/stats', ['as' => 'admin.get.stats', 'uses' => '\App\Http\Controllers\Admin\GatheringController@getStatistics']);

                $router->get('export', ['as' => 'admin.export',
                    'uses' => '\App\Http\Controllers\Admin\GatheringController@exportUsers']);

                /*
                 * MailingUsersController
                 */
                $router->post('send-notifications', ['as' => 'admin.send-notifications',
                    'uses' => '\App\Http\Controllers\Admin\MailingUsersController@post',]);

                /*
                 * CategoryController
                 */
                $router->post('category/new', ['as' => 'admin.category.new',
                    'uses' => '\App\Http\Controllers\CategoryController@createNewCategory']);

                /*
                 * NotificationController
                 */
                $router->get('notifications/get-all', ['as' => 'admin.notifications.get-all',
                    'uses' => '\App\Http\Controllers\Admin\NotificationController@getAll']);

                $router->get('notifications/get-unread', ['as' => 'admin.notifications.get-unread',
                    'uses' => '\App\Http\Controllers\Admin\NotificationController@getFromLastReadNotificationIndex']);

                $router->get('notifications/update-last-read', ['as' => 'admin.notifications.update-last-read',
                    'uses' => '\App\Http\Controllers\Admin\NotificationController@updateLastReadNotification']);
            }
        );
    }

    private function registerNavigations()
    {
        AdminNavigation::setFromArray
        (
            [
                [
                    'title' => 'Главная',
                    'icon' => 'fas fa-home',
                    'priority' => 1,
                    'url'   => route('admin.dashboard')
                ],
                [
                    'title' => 'Тест Google Analytics',
                    'priority' => 1.1,
                    'url' => route('admin.ga')
                ],
                [
                    'title' => 'Выйти',
                    'icon' => 'fas fa-sign-out-alt',
                    'priority' => 10,
                    'url'   => route('admin.quit')
                ]
            ]
        );
    }

//    private function addCategoryPagesToNavigation()
//    {
//        $pages = [(new Page)->setUrl('/admin/categories')->setTitle('Категории')];
//        $records = \DB::table('category')->select(['id', 'name'])->get();
//        foreach ($records as $record)
//        {
//            $pages[$record->id] = (new Page)->setUrl('/admin/products?category_id='.$record->id)
//                ->setTitle($record->name);
//        }
//        return $pages;
//    }

    private function registerWidgets()
    {
        $widgetsRegistry = $this->app[\SleepingOwl\Admin\Contracts\Widgets\WidgetsRegistryInterface::class];
        foreach ($this->widgets as $widget)
            $widgetsRegistry->registerWidget($widget);
    }
}
