<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class MarketplaceLogo extends Model
{
    protected $table = 'marketplace_logos';
    protected $primaryKey = 'marketplace_name';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'marketplace_name',
        'logo',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();
    }
}