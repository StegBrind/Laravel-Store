<?php

namespace App\Http\Sections;

use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;
use AdminDisplay;
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
        return AdminDisplay::tree()->
        setParentField('parent_id')->
        setNewEntryButtonText('Добавить категорию')->
        setOrderField('id')->
        setValue(function (\App\Category $category)
        {
            $row_text = '<div style="width: 100%">' . $category->name . ' (' . $category->count_products . ')';
            if ($category->count_products != 0)
            {
                $row_text .= ' <a style="float: right" class="btn btn-info btn-xs" href="' . url('admin/products?category_id=' . $category->id) . '">Посмотреть товары</a>';
            }
            $row_text .= '</div>';
            return $row_text;
        })->
        setReorderable(false)->
        setCardClass('border-info');
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */

    public function onEdit($id)
    {
        return AdminForm::card()->addBody
        (
            AdminFormElement::text('name', 'Название категории')
        );
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        $categories = \App\Category::query()->select(['id', 'name'])->where('count_products', '=', '0')->get()->toArray();

        foreach ($categories as $index => $category)
        {
            unset($categories[$index]);
            $categories[$category['id']] = $category['name'];
        }

        $categories[-1] = 'Корень';

        return AdminForm::card()->addBody
        (
            [
                AdminFormElement::text('name', 'Название категории')->setValidationRules('required'),
                AdminFormElement::select('parent_id','Место добавления', $categories)->setDefaultValue('-1')->setValidationRules('required')
            ]
        )->
        setAction(route('admin.category.new'));
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
        $this->addToNavigation(3);
    }
}
