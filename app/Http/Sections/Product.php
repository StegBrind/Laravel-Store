<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminDisplay;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Section;

/**
 * Class Admin
 *
 * @property \App\Admin $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Product extends Section
{
    /**
     * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
     *
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $this->title = 'Список товаров (Каталог ' . \App\Category::query()->select('name')->
            where('id', $_GET['category_id'])->value('name') . ')';

        $this->addBreadCrumb(
        [
            'id' => 'categories',
            'title' => 'Список категорий',
            'url' => url('admin/categories'),
            'parent' => 'home'
        ]);
        $this->addBreadCrumb(
        [
            'id' => 'products',
            'title' => $this->getTitle(),
            'url' => url('admin/products', ['category_id' => $_GET['category_id']]),
            'parent' => 'categories'
        ]);

        return AdminDisplay::datatables()->
        setHtmlAttribute('class', 'table-bordered')->
        setColumns
        (
            [
                AdminColumn::text('id', 'id')->setHtmlAttribute('style', 'text-align: center')->setWidth(80),
                AdminColumn::text('name', 'Название')->setHtmlAttribute('style', 'text-align: center'),
                AdminColumn::text('description', 'Описание')->setWidth(600),
                AdminColumn::text('author', 'Автор')->setHtmlAttribute('style', 'text-align: center'),

            ]
        )->
        setApply(function ($query)
        {
            $query->where('category_id', $_GET['category_id']);
        })->
        paginate(50);
    }
    /**
     * @return void
     */
    public function onDelete($id)
    {

    }
}
