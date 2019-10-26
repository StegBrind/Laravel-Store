<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnEditable;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;

/**
 * Class Admin
 *
 * @property \App\Admin $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Admin extends Section implements Initializable
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
    protected $title = "Список админов";

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'fa fa-user-secret';
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
        $display = AdminDisplay::
        datatables()->
        setHtmlAttribute('class', 'table-bordered')->
        setColumns
        (
            [
                AdminColumn::text('id', 'id'),
                AdminColumnEditable::text('email', 'Email')->
                setEditableMode('inline'),
                AdminColumnEditable::text('name', 'Псевдоним')->
                setEditableMode('inline'),
                AdminColumn::datetime('created_at', 'Дата Создания')->setFormat('d.m.Y')
            ]
        )->
        setNewEntryButtonText('Добавить админа')->
        paginate(30);
        $display->setColumnFilters
        (
            [
                AdminColumnFilter::text()->setPlaceholder('id')->setOperator('equal'),
                AdminColumnFilter::text()->setPlaceholder('Email')->setOperator('contains'),
                AdminColumnFilter::text()->setPlaceholder('Псевдоним')->setOperator('contains'),
                AdminColumnFilter::range()
                    ->setFrom
                    (
                        AdminColumnFilter::date()->setPlaceholder('Начиная с')->setPickerFormat('d.m.Y')->
                        setFormat('Y-m-d')
                    )->setTo
                    (
                        AdminColumnFilter::date()->setPlaceholder('Заканчивая')->setPickerFormat('d.m.Y')->
                        setFormat('Y-m-d')
                    )
            ]
        );
        return $display;
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        return AdminForm::panel()->addBody(
            [
                AdminFormElement::text('email', 'Email'),
                AdminFormElement::text('name', 'Псевдоним')
            ]);
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return AdminForm::panel()->addBody(
            [
                AdminFormElement::text('email', 'Email'),
                AdminFormElement::text('name', 'Псевдоним'),
                AdminFormElement::text('password', 'Пароль')
            ])->setAction(route('admin.admins.new'));
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

        if (\App\Admin::getAdminName() == 'Админ Владелец')
        {
            $this->addToNavigation(3, function ()
            {
                return \App\Admin::all()->count();
            });
        }
    }
}
