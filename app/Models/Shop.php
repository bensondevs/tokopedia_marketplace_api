<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Shop extends Model
{
    protected $table = 'shops';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'app_id',
        'client_id',
        'client_secret',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($shop) {
            $shop->id = Uuid::generate()->string;
    	});
    }
}