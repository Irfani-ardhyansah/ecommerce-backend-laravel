<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductDiscount extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['percent'];
    protected $hidden = ['product'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPercentAttribute()
    {
        $normalPrice    = $this->product->price;
        $discPrice      = $this->attributes['price'];
        $response       = round($discPrice / $normalPrice * 100, 2);
        return $response;
    }
}
