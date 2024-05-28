<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Usuarios extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_name',
        'email',
        'password',
        'phone',
        'role',
        'disabled',
    ];
    

   
}
