<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens; // Make sure to import HasApiTokens
use Illuminate\Foundation\Auth\User as Authenticatable; // Use the correct Authenticatable class
use Illuminate\Database\Eloquent\Model;

class Utilistaur extends Authenticatable
{
    use HasFactory, HasApiTokens; // Add HasApiTokens to the model

    protected $table = 'utilistaurs'; // Specify the table name if it's not the plural form of the model name
    protected $primaryKey = 'id'; // Specify the primary key if it's not the default 'id'

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
