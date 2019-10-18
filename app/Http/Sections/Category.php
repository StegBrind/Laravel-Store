<?php

namespace App\Http\Sections;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Display\ControlLink;
use SleepingOwl\Admin\Form\Buttons\FormButton;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\FormPanel;
use SleepingOwl\Admin\Section;
use AdminDisplay;
use AdminColumn;
use AdminForm;
use AdminFormElement;

/**
 * Class Category
 *
 * @property \App\Category $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Category extends Section implements Initializable
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
    protected $title = 'Список категорий';

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'fa fa-th-large';
    }

    /**
     * @var string
     */
    protected $alias;

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display = AdminDisplay::datatables()->
        setHtmlAttribute('class', 'table table-condensed table-hover')->
        setColumns
        (
            [
                AdminColumn::text('id', 'id'),
                AdminColumn::text('name', 'Название'),
                AdminColumn::text('count_products', 'Количество Продуктов'),
                AdminColumn::datetime('created_at', 'Создано')->setFormat('d.m.Y'),
                AdminColumn::datetime('updated_at', 'Изменено')->setFormat('d.m.Y'),
                (
                    AdminColumn::control('')->addButton
                    (
                        new ControlLink
                        (
                            function (Model $category)
                            {
                                return url('admin/products?category_id=' . $category->id);
                            }, 'Список товаров'
                        )
                    )
                )->setDeletable(false)->setEditable(false)
            ]
        )->
        setNewEntryButtonText('Добавить категорию')->
        paginate(20);
        return $display;
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */

    public function onEdit($id)
    {

        return AdminForm::panel()->addBody
        (
            AdminFormElement::text('name', 'Название категории')
        );
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return AdminForm::panel()->addBody
        (
            [
                AdminFormElement::text('name', 'Название категории')
            ]
        )->setAction(route('admin.category.new'));
    }

    /**
     * @return void
     */
    public function onDelete($id)
    {

    }

    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation(5);
    }
}
