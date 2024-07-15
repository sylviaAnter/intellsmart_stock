<?php

namespace App\Admin\Controllers;



use App\Models\StockSubCategory;
use App\Models\StockCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;


class StockSubCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Stock Sub Categories';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new StockSubCategory());
        $grid->disableBatchActions();
        $grid->quickSearch('name');
        $u = Admin::user();
        $grid->model()->where('company_id', $u->company_id)
            ->orderBy('name', 'asc');
        $grid->column('id', __('ID'))->sortable();
        $grid->column('image', __('Image'))
            ->display(function ($image) {
                return $image ? "<img src='/storage/{$image}' style='width:50px;height:50px;' />" : '';
            });
        $grid->column('name', __('Name'))->sortable();
        $grid->column('stock_category_id', __('Category'))
            ->display(function ($stock_category_id) {
                $category = StockCategory::find($stock_category_id);
                if ($category == null) {
                    return '';
                }
                return $category->name;
            })->sortable();
        // $grid->column('buying_price', __('Investment'))->sortable();
        // $grid->column('selling_price', __('Expected Sales'))->sortable();
        // $grid->column('expected_profit', __('Expected profit'))->sortable();
        $grid->column('earned_profit', __('Earned profit'))->sortable();
        // $grid->column('measurement_unit', __('Measurement unit'));
        $grid->column('current_quantity', __('Current quantity'))
            ->display(function ($current_quantity) {
                return number_format($current_quantity) . ' ' . $this->measurement_unit;
            })->sortable();
        $grid->column('recorder_level', __('Recorder level'))
            ->display(function ($recorder_level) {
                return number_format($recorder_level) . ' ' . $this->measurement_unit;
            })->sortable()
            ->editable();
        $grid->column('description', __('Description'))
            ->hide();
        $grid->column('in_stock', __('In Stock'))
            ->dot([
                'Yes' => 'success',
                'No' => 'danger'
            ])->sortable()
            ->filter([
                'Yes' => 'In Stock',
                'No'  => 'Out Of Stock'
            ]);
        $grid->column('status', __('Status'))
            ->label([
                'Active' => 'success',
                'Inactive' => 'danger'
            ])->sortable()
            ->filter([
                'Active' => 'In Stock',
                'Inactive'  => 'Out Of Stock'
            ]);
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
        $show = new Show(StockSubCategory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('company_id', __('Company id'));
        $show->field('stock_category_id', __('Stock category id'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('status', __('Status'));
        $show->field('image', __('Image'))->image('/storage/', 50, 50);
        $show->field('buying_price', __('Buying price'));
        $show->field('selling_price', __('Selling price'));
        $show->field('expected_profit', __('Expected profit'));
        $show->field('earned_profit', __('Earned profit'));
        $show->field('measurement_unit', __('Measurement unit'));
        $show->field('current_quantity', __('Current quantity'));
        $show->field('recorder_level', __('Recorder level'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new StockSubCategory());
        $u = Admin::user();
        $form->hidden('company_id', __('Company id'))
            ->default($u->company_id);
        $categories = StockCategory::where([ //using the StockCategory model to query the database.
            'company_id' => $u->company_id,
            'status' => 'active' //ana kada bas2l 3la collection
        ])->get()->pluck('name', 'id'); //results are returned as a collection of StockCategory objects.
        //The pluck method creates an associative array where the keys are the values of the id field and the values are the values of the name field from the collection of StockCategory objects.
        $form->select('stock_category_id', __('Stock category id'))
            ->options($categories)
            ->rules('required');
        $form->text('name', __('Name'))
            ->rules('required');
        $form->textarea('description', __('Description'));
        $form->image('image', __('Image'))
            ->uniqueName();
        $form->number('buying_price', __('Buying price'));
        $form->number('selling_price', __('Selling price'));
        $form->number('expected_profit', __('Expected profit'));
        $form->number('earned_profit', __('Earned profit'));
        $form->text('measurement_unit', __('Measurement unit'))
            ->rules('required');
        $form->decimal('recorder_level', __('Recorder level'))
            ->rules('required');
        $form->radio('status', __('Status'))
            ->options([
                'Active' => 'Active',
                'Inactive' => 'Inactive'
            ])->default('Active')
            ->rules('required');
        return $form;
    }
}
