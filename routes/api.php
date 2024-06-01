<?php

use App\Http\Controllers\UsuariosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/prueba', [UsuariosController::class, 'prueba']);

// Obtener todos
Route::get('/getAll', [UsuariosController::class, 'getAllUser']);

// Obtener por id
Route::get('/user/{id}', [UsuariosController::class, 'usuario']);

//Crear
Route::post('/createUser', [UsuariosController::class, 'createUser']);
//Editar
Route::patch('/editUser/{id}', [UsuariosController::class, 'patchUser']);
//Eliminar/Deshabilitar
Route::delete('/deleteUser/{id}', [UsuariosController::class, 'deleteUser']);

//REGISTRAR
Route::post('/register', [UsuariosController::class, 'register']);

// LOGIN
Route::post('/login', [UsuariosController::class, 'login']);
