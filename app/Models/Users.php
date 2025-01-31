<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

   
    protected $table = 'users';
    protected $primaryKey = 'id';

    // Specify the attributes that can be mass-assigned
    protected $fillable = [
       // 'name',
        'email',
        'password',
        
    ];

    // Specify which attributes should be hidden in arrays
    protected $hidden = [
        'password',
        'remember_token',
    ];

        public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
