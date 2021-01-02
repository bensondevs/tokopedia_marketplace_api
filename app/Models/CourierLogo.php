<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class CourierLogo extends Model
{
    protected $table = 'courier_logos';
    protected $primaryKey = 'courier_name';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'courier_name',
        'logo',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();
    }
}