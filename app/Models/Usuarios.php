<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


 // Viene de la libreria JWT-Auth


class Usuarios extends Model implements JWTSubject
{
    use HasFactory, HasApiTokens;


    protected $fillable = [
        'name',
        'user_name',
        'email',
        'password',
        'phone',
        'role',
        'disabled',
    ];
    
    //DE JWT_AUTH
    // para obtener un nuevo jwt
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
   
}
