<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceOverride extends Model
{
    protected $fillable = ['date','price_per_night','is_active','note'];
    protected $casts = ['date'=>'date','is_active'=>'boolean'];
}
