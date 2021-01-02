<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class OrderProduct extends Model
{
    protected $table = 'order_products';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'order_id',
        'product_name',
        'sku',
        'size',
        'quantity',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($orderProduct) {
            $orderProduct->id = Uuid::generate()->string;
    	});
    }
}