<?php

namespace App\Admin\Controllers;

use App\Models\company;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CompanyEditController extends AdminController
{
    /**
     * Title for current resource.
     *
     *  @var string
     */
    protected $title = 'Company';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new company());
        $grid->disableCreateButton();
        $u = Admin::user();
        $grid->model()->where('id', $u->company_id);
        // $grid->column('id', __('Id'));
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));
        // $grid->column('owner_id', __('Owner id'));
        //$grid->column('logo', __('Logo'))->image('', 50, 50);
        $grid->column('logo', __('Logo'))
            ->display(function ($logo) {
                return $logo ? "<img src='/storage/{$logo}' style='width:50px;height:50px;' />" : '';
            });
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('website', __('Website'));
        $grid->column('about', __('About'))->hide();
        //$grid->column('status', __('Status'));
        $grid->column('license_expire', __('License expire'));
        // $grid->column('address', __('Address'));
        $grid->column('phone_number', __('Phone number'));
        $grid->disableBatchActions();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
        });
        // $grid->column('phone_number_2', __('Phone number 2'));
        // $grid->column('pobox', __('Pobox'));
        // $grid->column('color', __('Color'));
        // $grid->column('slogan', __('Slogan'));
        // $grid->column('facebook', __('Facebook'));
        // $grid->column('twitter', __('Twitter'));
        // $grid->column('currency', __('Currency'));
        // $grid->column('settings_worker_can_create_stock_item', __('Settings worker can create stock item'));
        // $grid->column('settings_worker_can_create_stock_record', __('Settings worker can create stock record'));
        // $grid->column('settings_worker_can_create_stock_category', __('Settings worker can create stock category'));
        // $grid->column('settings_worker_can_view_balance', __('Settings worker can view balance'));
        // $grid->column('settings_worker_can_view_stats', __('Settings worker can view stats'));
        return $grid;
    }
    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(company::findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('owner_id', __('Owner id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('logo', __('Logo'));
        $show->field('website', __('Website'));
        $show->field('about', __('About'));
        $show->field('status', __('Status'));
        $show->field('license_expire', __('License expire'));
        $show->field('address', __('Address'));
        $show->field('phone_number', __('Phone number'));
        $show->field('phone_number_2', __('Phone number 2'));
        $show->field('pobox', __('Pobox'));
        $show->field('color', __('Color'));
        $show->field('slogan', __('Slogan'));
        $show->field('facebook', __('Facebook'));
        $show->field('twitter', __('Twitter'));
        $show->field('currency', __('Currency'));
        $show->field('settings_worker_can_create_stock_item', __('Settings worker can create stock item'));
        $show->field('settings_worker_can_create_stock_record', __('Settings worker can create stock record'));
        $show->field('settings_worker_can_create_stock_category', __('Settings worker can create stock category'));
        $show->field('settings_worker_can_view_balance', __('Settings worker can view balance'));
        $show->field('settings_worker_can_view_stats', __('Settings worker can view stats'));
        return $show;
    }
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new company());
        // $form->number('owner_id', __('Owner id'));
        $form->text('name', __('Name'))->rules('required');
        $form->text('email', __('Email'));
        $form->image('logo', __('Logo'));
        $form->text('website', __('Website'));
        $form->text('about', __('About'));
        //$form->text('status', __('Status'));
        // $form->date('license_expire', __('License expire'))->default(date('Y-m-d'));
        $form->textarea('address', __('Address'));
        $form->text('phone_number', __('Phone number'));
        $form->text('phone_number_2', __('Phone number 2'));
        $form->text('pobox', __('Pobox'));
        $form->color('color', __('Color'));
        $form->text('slogan', __('Slogan'));
        $form->text('facebook', __('Facebook'));
        $form->text('twitter', __('Twitter'));
        $form->divider('Settings');
        $form->text('currency', __('Currency'))->default('USD')->rules('required');
        $form->radio('settings_worker_can_create_stock_item', __('Can worker create stock item'))
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])->default('Yes');
        $form->radio('settings_worker_can_create_stock_record', __('Can worker create stock record'))
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])->default('Yes');
        $form->radio('settings_worker_can_create_stock_category', __('Can worker create stock category'))
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])->default('Yes');
        $form->radio('settings_worker_can_view_balance', __('Can worker view balance'))
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])->default('Yes');
        $form->radio('settings_worker_can_view_stats', __('Can worker view stats'))
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])->default('Yes');
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });
        $form->disableCreatingCheck();
        $form->disableEditingCheck();
        $form->disableReset();
        $form->disableViewCheck();
        return $form;
    }
}
