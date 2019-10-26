<?php

namespace App\Providers;

use AdminNavigation;
use Illuminate\Routing\Router;
use SleepingOwl\Admin\Admin;
use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;

class AdminSectionsServiceProvider extends ServiceProvider
{

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
        $this->registerRoutes();
        $this->registerNavigations();
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
                $router->get('dashboard', ['as' => 'admin.dashboard',  'uses' => '\App\Http\Controllers\AdminController@dashboard']);

                $router->get('get/stats', ['as' => 'admin.get.stats', 'uses' => '\App\Http\Controllers\AdminController@getStatistics']);

                $router->get('quit', ['as' => 'admin.quit', 'uses' => '\App\Http\Controllers\AdminController@quitAdmin']);

                $router->post('admins/new', ['as' => 'admin.admins.new',
                    'uses' => '\App\Http\Controllers\AdminController@createNewAdmin']);

                $router->post('send-notifications', ['as' => 'admin.send-notifications',
                    'uses' => '\App\Http\Controllers\AdminController@sendEmailNotifications',]);

                $router->post('category/new', ['as' => 'admin.category.new',
                    'uses' => '\App\Http\Controllers\CategoryController@createNewCategory']);
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
                    'title' => 'Выйти',
                    'icon' => 'fas fa-sign-out-alt',
                    'priority' => 10,
                    'url'   => route('admin.quit')
                ]
            ]
        );
    }
}
