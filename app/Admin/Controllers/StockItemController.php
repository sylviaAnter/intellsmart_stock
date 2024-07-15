<?php

namespace App\Admin\Controllers;

use App\Models\FinancialPeriod;
use App\Models\StockCategory;
use App\Models\StockItem;
use App\Models\StockSubCategory;
use App\Models\User;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StockItemController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Stock Items';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        // $item = StockItem::find(1);
        // // $stock_category = StockCategory::find($item->stock_category_id);
        // $stock_sub_category = StockSubCategory::find($item->stock_sub_category_id);
        // $stock_sub_category->update_self();
        // die('done');
        // $grid->column('updated_at', __('Updated at'));
        // $grid->column('company_id', __('Company id'));
        $grid = new Grid(new StockItem());

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name', 'Name');
            $u = Admin::user();
            $filter->equal('Stock_sub_category_id', 'Stock Sub Category')
                ->select(StockSubCategory::where([
                    'company_id' => $u->company_id
                ])->pluck('name', 'id'));

            $filter->equal('financial_period_id', 'Financial Period')
                ->select(FinancialPeriod::where([
                    'company_id' => $u->company_id
                ])->pluck('name', 'id'));

            $filter->equal('created_by_id', 'Created By')
                ->select(User::where([
                    'company_id' => $u->company_id
                ])->pluck('name', 'id'));

            $filter->equal('Stock_category_id', 'Stock Category')
                ->select(StockCategory::where([
                    'company_id' => $u->company_id
                ])->pluck('name', 'id'));

            //$filter->equal('barcode', 'Barcode');
            $filter->like('sku', 'Sku');
            $filter->between('buying_price', 'Buying Price')
                ->decimal();

            $filter->between('selling_price', 'Selling Price')
                ->decimal();

            //$filter->equal('original_quantity', 'Original Quantity');
            $filter->between('current_quantity', 'Current Quantity')
                ->decimal();
            $filter->equal('created_at', 'Created At')
                ->date();
        });
        $u = Admin::user();
        $grid->model()->where('company_id', $u->company_id);
        //$grid->model()->where('company_id')->default($u->company_id);
        $grid->disableBatchActions();
        $grid->column('id', __('ID'))->sortable();


        // $grid->column('image', __('Photo'))->lightbox([
        //     'width' => 50,
        //     //'height' => 50,

        // ]);
        $grid->column('image', __('Image'))
            ->display(function ($image) {
                return $image ? "<img src='/storage/{$image}' style='width:50px;height:50px;' />" : '';
            });

        $grid->column('name', __('Product Name'))->sortable();



        $grid->column('stock_category_id', __('Stock category'))
            ->display(function ($stock_category_id) {
                $stock_category = StockCategory::find($stock_category_id);
                if ($stock_category) {
                    return $stock_category->name_text;
                } else {
                    return 'N/A';
                }
            })->sortable()
            ->hide();
        $grid->column('stock_sub_category_id', __('Stock Sub Category'))
            ->display(function ($stock_sub_category_id) {
                $stock_sub_category = StockSubCategory::find($stock_sub_category_id);
                if ($stock_sub_category) {
                    return $stock_sub_category->name_text;
                } else {
                    return 'N/A';
                }
            })->sortable();
        $grid->column('financial_period_id', __('Financial Period'))
            ->display(function ($financial_period_id) {
                $financial_period = FinancialPeriod::find($financial_period_id);
                if ($financial_period) {
                    return $financial_period->name;
                } else {
                    return 'N/A';
                }
            })->sortable();

        $grid->column('description', __('Description'))->hide();
        $grid->column('barcode', __('Barcode'))->sortable();
        $grid->column('sku', __('Sku'))->sortable();
        // $grid->column('generate_sku', __('Generate sku'));
        // $grid->column('update_sku', __('Update sku'));
        // $grid->column('gallery', __('Gallery'));
        $grid->column('buying_price', __('Buying Price'))->sortable();
        $grid->column('selling_price', __('Selling Price'))->sortable();
        $grid->column('original_quantity', __('Original quantity'))->sortable();
        $grid->column('current_quantity', __('Current quantity'))->sortable();



        $grid->column('created_by_id', __('Created'))
            ->display(function ($created_by_id) {
                $user = User::find($created_by_id);
                if ($user) {
                    return $user->name;
                } else {
                    return 'N/A';
                }
            })->sortable();
        $grid->column('created_at', __('Created at'))
            ->display(function ($created_at) {
                return date('Y-m-d', strtotime($created_at));
            })->sortable();
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
        $show = new Show(StockItem::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('company_id', __('Company id'));
        $show->field('created_by_id', __('Created by id'));
        $show->field('stock_category_id', __('Stock category id'));
        $show->field('stock_sub_category_id', __('Stock sub category id'));
        $show->field('financial_period_id', __('Financial period id'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('image', __('Image'));
        $show->field('barcode', __('Barcode'));
        $show->field('sku', __('Sku'));
        $show->field('generate_sku', __('Generate sku'));
        $show->field('update_sku', __('Update sku'));
        $show->field('gallery', __('Gallery'));
        $show->field('buying_price', __('Buying price'));
        $show->field('selling_price', __('Selling price'));
        $show->field('original_quantity', __('Original quantity'));
        $show->field('current_quantity', __('Current quantity'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $u = Admin::user();
        $fiancial_period = Utils::getActiveFinancialPeriod($u->company_id);
        if ($fiancial_period == null) {
            return admin_error('please create a financial period first.');
        }
        $form = new Form(new StockItem());
        $form->hidden('company_id', __('Company id'))
            ->default($u->company_id);
        if ($form->isCreating()) {
            $form->hidden('created_by_id', __('Created by id'))
                ->default($u->id);
        }


        
        //Generate the URL for the stock-sub-categories API endpoint and store it in the variable $sub_cat_ajax_url.
        $sub_cat_ajax_url = url('api/stock-sub-categories');
        //Append the company_id parameter to the URL, setting its value to the admin's company ID.
        $sub_cat_ajax_url = $sub_cat_ajax_url
            . '?company_id=' . $u->company_id;
        $form->select('stock_sub_category_id', __('Stock Category'))
            ->ajax($sub_cat_ajax_url)
            //Configure it to use AJAX to fetch options from the $sub_cat_ajax_url.
            ->options(function ($id) {
                $sub_cat = StockSubCategory::find($id);
                if ($sub_cat) {
                    return [
                        $sub_cat->id => $sub_cat->name_text . " ( " . $sub_cat->measurement_unit . " ) "
                    ];
                } else {
                }
            })
            ->rules('required');
        $form->hidden('financial_period_id', __('Financial period id'));
        $form->text('name', __('Name'))
            ->rules('required');
        $form->image('image', __('Image'))
            ->uniqueName();
        //$form->textarea('barcode', __('Barcode'));
        if ($form->isEditing()) {
            $form->radio('update_sku', __('Update SKU'))
                ->options([
                    'Yes' => 'Yes',
                    'No' => 'No',
                ])->when('Yes', function (Form $form) {
                    $form->text('sku', __('ENTER SKU (Batch Number)'))
                        ->rules('required');
                })->rules('required')
                ->default('NO');
        } else {
            $form->hidden('update_sku', __('Update SKU'))->default('NO');
            $form->radio('generate_sku', __('Generate sku'))
                ->options([
                    'Manual' => 'Manual',
                    'Auto' => 'Auto',
                ])->when('Manual', function (Form $form) {
                    $form->text('generate_sku', __('Generate SKU'))
                        ->rules('required');
                })->rules('required');
        }
        //$form->text('update_sku', __('Update sku'));
        // $form->multipleImage('gallery', __('Item Gallery'))
        //     ->removable()
        //     ->uniqueName()
        //     ->downloadable();
        $form->decimal('buying_price', __('Buying price'))
            ->default(0.00)
            ->rules('required');
        $form->decimal('selling_price', __('Selling price'))
            ->default(0.00)
            ->rules('required');
        $form->decimal('original_quantity', __('Original quantity (in units)'))
            ->default(0.00)
            ->rules('required');
        //$form->number('current_price', __('Current quantity'));
        $form->textarea('description', __('Description'));
        return $form;
    }
}
