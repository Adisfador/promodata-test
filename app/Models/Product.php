<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'product_id';
    protected $table = 'product';
    protected $fillable = ['product_name', 'category_id', 'manufacturer_id'];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id', 'manufacturer_id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'product_id', 'product_id');
    }
}
