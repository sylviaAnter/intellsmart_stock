<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Encore\Admin\Auth\Database\Administrator;

class User extends Administrator
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'admin_users'; //Specifies that the User model corresponds to the admin_users table in the database.
    public function company()
    { //lo el user da belong to el sharka de 3ndo relationship
        //with the comapny via company_id
        return $this->belongsTo(Company::class, 'company_id');
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) { //Adds an event listener for the creating event, which is triggered before a new User model is created.
            $name = ""; //Sets the name attribute to the first_name or last_name if available.
            if ($model->first_name != null && strlen($model->first_name) > 0) {
                $name = $model->first_name;
            }
            if ($model->last_name != null && strlen($model->last_name) > 0) {
                $name = $model->last_name;
            }
            $name = trim($name);
            if ($name != null && strlen($name) > 0) {
                $model->name = $name;
            }
            $model->username = $model->email;
            $model->password = bcrypt('admin');
            return $model;
        });
        static::updating(function ($model) {
            $name = "";
            if ($model->first_name != null && strlen($model->first_name) > 0) {
                $name = $model->first_name;
            }
            if ($model->last_name != null && strlen($model->last_name) > 0) {
                $name = $model->last_name;
            }
            $name = trim($name);
            if ($name != null && strlen($name) > 0) {
                $model->name = $name;
            }
            $model->username = $model->email;
            return $model;
        });
    }



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
