<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Database\Eloquent\Model;

class Utilistaur extends Authenticatable
{
    use HasFactory, HasApiTokens; 

    protected $table = 'utilistaurs'; 
    protected $primaryKey = 'id'; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'cartdata'
    ];

    protected $casts = [
        'password' => 'hashed', 
        'cartdata' => 'json',
    ];

    protected $hidden = [
        'password',
    ];
   

    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = bcrypt($value);
    // }
    
}
