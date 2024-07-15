<?php

namespace App\Models;

use Faker\Guesser\Name;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRecord extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {

            $stock_item = StockItem::find($model->stock_item_id);
            if ($stock_item == null) {
                throw new \Exception("inavlid stock sub category");
            }


            $financial_period = Utils::getActiveFinancialPeriod($stock_item->company_id);
            if ($financial_period == null) {
                throw new \Exception("invalid financial period");
            }
            $model->financial_period_id = $financial_period->id;


            $model->company_id = $stock_item->company_id;
            $model->stock_category_id = $stock_item->stock_category_id;
            $model->stock_sub_category_id = $stock_item->stock_sub_category_id;
            $model->sku = $stock_item->sku;
            $model->name = $stock_item->name;
            $model->measurement_unit = $stock_item->stockSubCategory->measurement_unit;

            if ($model->description == null) {
                $model->description = $stock_item->type;
            }
            $quantity = abs($model->quantity);
            if ($quantity < 1) {
                throw new \Exception("invalid quantity");
            }

            $model->selling_price = $stock_item->selling_price;
            $model->total_sales = $model->selling_price * $quantity;
            $model->quantity = $quantity;

            if (
                $model->type == 'Sale' ||
                $model->type == 'Internal Use'
            ) {
                $model->total_sales = abs($model->total_sales);
                $model->profit = $model->total_sales - ($stock_item->buying_price * $quantity);
            } else {
                $model->total_sales = 0;
                $model->profit = 0;
            }

            //profit
            $model->profit = $model->total_sales - ($stock_item->buying_price * $quantity);
            $model->profit = abs($model->profit);





            $current_quantity = $stock_item->current_quantity;
            if ($current_quantity < $quantity) {
                throw new \Exception("insufficient stock. ");
            }

            $new_quantity = $current_quantity - $quantity;
            $stock_item->current_quantity = $new_quantity;
            $stock_item->save();

            return $model;
        });


        static::created(function ($model) {

            $stock_item = StockItem::find($model->stock_item_id);
            if ($stock_item == null) {
                throw new \Exception("inavlid stock sub category");
            }
            $stock_item->stockSubCategory->update_self();
            $stock_item->stockSubCategory->stockCategory->update_self();
        });
    }
}
