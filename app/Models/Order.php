<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    protected $fillable = [
        'user_idd',
        'items',
        'amount',
        'address',
        'status',
        'date',
        'payment'
    ];

    protected $casts = [
        'items' => 'array',
        'date' => 'datetime',
        'payment' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(Utilistaur::class, 'user_idd', 'id');
    }
}
