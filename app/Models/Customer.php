<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'invoice_num';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'invoice_num',
        'order_id',
        'fs_id',
        'customer_account_name',
        'recipient_name',
        'recipient_address',
        'recipient_district',
        'recipient_city',
        'recipient_province',
        'recipient_phone',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::saving(function ($customer) {
            $customer->id = Uuid::generate()->string;
    	});
    }
}