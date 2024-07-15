<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialPeriod extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();
        //static::creating(function ($model) { ... }):
        //Adds an event listener for the creating event,
        //which is triggered before a new FinancialPeriod
        //model is created.
        static::creating(function ($model) {
            $active_financial_period = FinancialPeriod::where([
                'company_id' => $model->company_id,
                'status' => 'Active',
            ])->first();
            //if ($active_financial_period != null &&
            //$model->status == 'Active'): Checks if there
            // is already an active financial period for the
            // company.
            if ($active_financial_period != null && $model->status == 'Active') {
                throw new \Exception('there is an active financial period. please close it first');
            }
        });
        static::updating(function ($model) {
            $active_financial_period = FinancialPeriod::where([
                'company_id' => $model->company_id,
                'status' => 'Active',
            ])->first();

            if ($model->status == 'Active') {
                if ($active_financial_period != null && $active_financial_period->id != $model->id) {
                    throw new \Exception('there is an active financial period. please close it first. ');
                }
            }
        });
    }
}
//Checking for an active financial period before creating a new one and throwing an exception if one exists.
//Checking for an active financial period before updating an existing one to 'Active' and throwing an exception if another active period exists.
