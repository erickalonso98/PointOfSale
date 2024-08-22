<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    use HasFactory;

    protected $table = 'boxes';
    protected $fillable = [
        'name',
        'initial_balance',
        'current_balance'
    ];

    public function sales(){
        return $this->hasMany(Sale::class);
    }
}
