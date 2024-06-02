<?php


use App\Http\Controllers\UsuariosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function() {

   // Todas las rutas protegidas
  /*   Route::get('/prueba', [UsuariosController::class, 'prueba']); */
    
    // Obtener todos
    Route::get('/getAll', [UsuariosController::class, 'getAllUser']);// http://127.0.0.1:8000/api/v1/getAll
    
    // Obtener por id
    Route::get('/user/{id}', [UsuariosController::class, 'usuarioById']); // http://127.0.0.1:8000/api/v1/user/id
    // Obtener agrupado por fecha de creacion
    Route::get('/getUsersGroupedByDate', [UsuariosController::class, 'getUsersGroupedByDate']); // http://127.0.0.1:8000/api/v1/getUsersGroupedByDate
    
    //Crear
    Route::post('/createUser', [UsuariosController::class, 'createUser']); // http://127.0.0.1:8000/api/v1/createUser
    //Editar
    Route::patch('/editUser/{id}', [UsuariosController::class, 'patchUser']); // http://127.0.0.1:8000/api/v1/editUser/id
    //Eliminar/Deshabilitar
    Route::delete('/deleteUser/{id}', [UsuariosController::class, 'deleteUser']); // http://127.0.0.1:8000/api/v1/deleteUser/id
});

//REGISTRAR
Route::post('/register', [UsuariosController::class, 'register']); // http://127.0.0.1:8000/api/v1/register

// LOGIN
Route::post('/login', [UsuariosController::class, 'login'])->name('login'); // http://127.0.0.1:8000/api/v1/login


