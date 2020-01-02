<?php


namespace App\Http\Sections;

use AdminDisplay;
use AdminColumn;
use AdminColumnFilter;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Display\DisplayTabbed;
use SleepingOwl\Admin\Form\Element\View;
use SleepingOwl\Admin\Section;

class AdminNotificationTabs extends Section implements Initializable
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
    protected $title = "Рассылка писем";

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'far fa-envelope';
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
        $tabs = new DisplayTabbed;
        $tabs->appendTab(new View('admin.admin_send_notifications_form'), 'Рассылка', true);
        //
        // Notifications History Table
        //
        $table = AdminDisplay::datatablesAsync()->
        setHtmlAttribute('class', 'table-bordered')->
        setColumns
        (
            [
                AdminColumn::text('id', 'id'),
                AdminColumn::text('subject', 'Тема'),
                AdminColumn::text('content_message', 'Текст Сообщения')->setView(view('admin.custom.column_text')),
                AdminColumn::boolean('done', 'Разослано'),
                AdminColumn::datetime('created_at', 'Дата Отправки')
            ]
        )->
        paginate(50);
        $table->setColumnFilters
        (
            [
                AdminColumnFilter::text()->setPlaceholder('id')->setOperator('equal'),
                AdminColumnFilter::text()->setPlaceholder('Тема')->setOperator('contains'),
                null,
                AdminColumnFilter::select(null, 'done')->setPlaceholder('')->setOptions(['Нет', 'Да']),
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

        $tabs->appendTab($table, 'История рассылок');
        return $tabs;
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
        $this->addToNavigation(4);
    }
}