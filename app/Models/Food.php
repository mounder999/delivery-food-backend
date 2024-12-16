<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $table = 'foods';

  
    protected $primaryKey = 'food_id';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'image',
        'price',
        'description',
        'category',
    ];
}
