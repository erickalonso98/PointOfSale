<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymets extends Model
{
    use HasFactory;

    protected $table = 'paymets';

    protected $fillable = [
        'sale_id',
        'payments_method',
        'paymets_amout'
    ];

    public function sale(){
        return $this->belongsTo(Sale::class);
    }
}
