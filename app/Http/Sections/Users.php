<?php

namespace App\Http\Sections;

use App\User;
use AdminColumnEditable;
use AdminColumnFilter;
use AdminDisplay;
use AdminColumn;
use AdminForm;
use AdminFormElement;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;


/**
 * Class Users
 *
 * @property \App\User $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Users extends Section implements Initializable
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
    protected $title = 'Пользователи';

    /**
     * @var string
     */
    protected $alias;

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'fas fa-users';
    }

    /**
     * @return DisplayInterface
     * @throws \SleepingOwl\Admin\Exceptions\FilterOperatorException
     */
    public function onDisplay()
    {
        $display = AdminDisplay::
        datatablesAsync()->
        setHtmlAttribute('class', 'table-bordered')->
        setColumns
        (
            [
                AdminColumn::text('id', 'id'),
                AdminColumn::text('email', 'Email'),
                AdminColumnEditable::text('name_surname', 'Имя Фамилия')->
                setEditableMode('inline'),
                AdminColumn::datetime('email_verified_at', 'Дата Подтверждения Почты'),
                AdminColumn::datetime('created_at', 'Дата Регистрации')->setFormat('d.m.Y')
            ]
        )->
        paginate(30)->
        setActionsForm(AdminFormElement::html('<a class="btn btn-outline-success" href="/admin/export?table=users">Экспорт</a>'));
        $display->getActionsForm()->setPlacement('card.heading');
        $display->setColumnFilters
        (
            [
                AdminColumnFilter::text()->setPlaceholder('id')->setOperator('equal'),
                AdminColumnFilter::text()->setPlaceholder('Email')->setOperator('contains'),
                AdminColumnFilter::text()->setPlaceholder('Имя Фамилия')->setOperator('contains'),
                null,
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
//// Изменить разсположение положения кнопок на странице
//        $table->getActions()
//            ->setPlacement('panel.buttons')
//            ->setHtmlAttribute('class', 'pull-right');

    }

    public function onEdit($id)
    {
        return AdminForm::card()->
        addBody
        ([
            AdminFormElement::text('name_surname', 'Имя Фамилия')
        ]);
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
        $this->addToNavigation(2, function ()
        {
            return User::all()->count();
        });
    }
}