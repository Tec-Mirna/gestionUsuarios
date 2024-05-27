<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    function prueba(){
        return response()->json([
            'status' => true,
            'message' => 'Api working fine'
        ]);
    }
}
