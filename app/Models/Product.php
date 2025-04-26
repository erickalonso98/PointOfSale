<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'code',
        'name',
        'description',
        'purchase_price',
        'sale_price',
        'stock',
        'minimum_stock',
        'photo',
        'status',
        'brand',
        'categories_id',
        'suppliers_id'
    ];

    public function categorie(){
        return $this->belongsTo(Category::class,'categories_id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class,'suppliers_id');
    }

    public function saleDetails(){
        return $this->hasMany(SaleDetail::class);
    }
}
