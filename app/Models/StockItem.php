<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;





    //boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model = self::prepare($model);
            $model->current_quantity = $model->original_quantity;


            return $model;
        });

        static::updating(function ($model) {
            $model = self::prepare($model);
            return $model;
        });
        static::created(function ($model) {
            $stock_category = StockCategory::find($model->stock_category_id);
            $stock_category->update_self();

            $stock_sub_category = StockSubCategory::find($model->stock_sub_category_id);
            $stock_sub_category->update_self();
        });
        static::updated(function ($model) {
            $stock_category = StockCategory::find($model->stock_category_id);
            $stock_category->update_self();

            $stock_sub_category = StockSubCategory::find($model->stock_sub_category_id);
            $stock_sub_category->update_self();
        });

        static::deleted(function ($model) {

            $stock_category = StockCategory::find($model->stock_category_id);
            $stock_category->update_self();

            $stock_sub_category = StockSubCategory::find($model->stock_sub_category_id);
            $stock_sub_category->update_self();
        });
    }

    static public function prepare($model)
    {

        //die("hello sylvia");
        $sub_category = StockSubCategory::find($model->stock_sub_category_id);
        if ($sub_category == null) {
            throw new \Exception("invalid stock sub category");
        }
        $model->stock_category_id = $sub_category->stock_category_id;


        $user = User::find($model->created_by_id);
        if ($user == null) {
            throw new \Exception("Invalid user");
        }
        $financial_period = Utils::getActiveFinancialPeriod($user->company_id);
        if ($financial_period == null) {
            throw new \Exception("invalid financial period");
        }
        $model->financial_period_id = $financial_period->id;
        $model->company_id = $user->company_id;

        if ($model->sku == null || strlen($model->sku) < 2) {
            $model->sku = Utils::generateSKU($model->company_id);
        }
        if ($model->update_sku == "Yes" && $model->generate_sku == 'Manual') {
            $model->sku = Utils::generateSKU($model->company_id);
            $model->genrate_sku = "No";
        }


        //dd($model->sku);

        return $model;
    }


    public function stockCategory()
    {
        return $this->belongsTo(StockCategory::class);
    }

    public function getGalleryAttribute($value)
    {
        if ($value != null && strlen($value) > 3) {
            return json_decode($value);
        }
        return [];
    }

    public function setGalleryAttribute($value)
    {
        $this->attributes['gallery'] = json_encode($value, true);
    }



    protected $appends = ['name_text'];

    public function getNameTextAttribute()
    {
        $name_text = $this->name;
        if ($this->stockSubCategory != null) {
            $name_text = $name_text . " - " . $this->stockSubCategory->name;
        }
        $name_text = $name_text . " ( " . number_format($this->current_quantity) . " " . $this->stockSubCategory->measurement_unit . " ) ";
        return $name_text;
    }

    public function stockSubCategory()
    {
        return $this->belongsTo(StockSubCategory::class);
    }
}
