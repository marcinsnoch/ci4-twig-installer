<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $fillable = ['name', 'description', 'price', 'qty', 'created_at', 'updated_at'];
}
