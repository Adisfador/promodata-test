<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'manufacturer_id';
    protected $table = 'manufacturer';
    protected $fillable = ['manufacturer_name'];

    public function products()
    {
        return $this->hasMany(Product::class, 'manufacturer_id', 'manufacturer_id');
    }
}
