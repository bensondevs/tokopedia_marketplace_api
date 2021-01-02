<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use DNS1D;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'marketplace',
        'marketplace_logo',
        'courier_logo',
        'invoice_num',
        'order_id',
        'recipient_name',
        'shop_name',
        'address',
        'weight',
        'total',
        'shipping_price',
        'city',
        'province',
        'phone',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($order) {
            $order->id = Uuid::generate()->string;
    	});
    }

    public function getInvoiceNumBarcodeAttribute()
    {
        return DNS1D::getBarcodePNG($this->invoice_num, 'C128');
    }

    public function getOrderIdBarcodeAttribute()
    {
        return DNS1D::getBarcodePNG($this->order_id, 'C128');
    }

    public function products()
    {
        return $this->hasMany(
            'App\Models\OrderProduct', 
            'order_id', 
            'id'
        );
    }
}