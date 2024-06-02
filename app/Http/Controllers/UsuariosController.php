<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Usuarios;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use Tymon\JWTAuth\Facades\JWTAuth;



class UsuariosController extends Controller {
   /*public function prueba(){
        return response()->json([
            'status' => true,
            'message' => 'Api working fine'
        ]);
    } */
    // OBTENER TODOS
    
    public function getAllUser(){
      
        // Obtener registros donde el campo disabled sea falso /no eliminado
        $user = Usuarios::where('disabled', false)->get();

        // Si no hay usuarios
        if($user->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No users found',
                'data' => []
            ]);
        }
        // Mostrar todos los usuarios
        return response()->json([
             'status' => true,
             'message' => 'Get all Users',
             'data' => $user
        ], 200);
    }

    public function usuarioById($id){

        $user = Usuarios::find($id);

         // Verificar si existe y si no, muestra la respuesta
        if(!$user){
            return response()->json([
               'status' => false,
               'message' => 'User with id ' . $id . ' not found',
               'data' => null
            ], 404);
        }
    
        // mostrar solo los habilitados(no eliminados)
        if ($user->disabled){
            return response()->json([
                'status' => false,
                'message' => 'User whit id ' . $id . ' not found',
                'data' => 'not data'
            ], 404);
        }
        // Si todo sale bien
            return response()->json([
                'status' => true,
                'message' => 'Get user with id ' . $id,
                'data' => $user
            ], 200);
        
    }

   
     // CREAR:  MISMA FUNCIONALIDAD QUE REGISTRAR USUARIO
     public function createUser(UserRequest $request){

     // No puede haber un usuario duplicado(Mismo nombre)
     $usuarioDatabase =  Usuarios::where('name', $request->name)->first();
      if($usuarioDatabase){ // si usuarioDatabase contiene un valor significa que ya existe
        // Mensaje indica que ya existe el usuario con dicho nombre
        return response()->json([ 
            'status' => false,
            'message' => 'User already exists',
            'data' => 'Not data'
        ], 409); // Indica conflicto
      }

      // Error interno
        try{
           $user = Usuarios::create($request->all());
        } 
        catch(\Exception $e){
           return response()->json([
            'status' => true,
             'message' => 'Error creating user',
             'data' => $e->getMessage()
           ], 500);
        }
        // El usario se ha creado
        return response()->json([
            'status' => true,
            'message' => 'New user created',
            'data' => $user
        ], 201); // Indica creado
   }

   // EDITAR
   public function patchUser(Request $request, $id){
        // Busca el id en la base de datos
        $user = Usuarios::find($id);

        // si el usuario con el id proporcionado no existe, muestra lo siguiente
        if(!$user){
           return response()->json([
               'status' => false,
               'message' => 'User not found',
               'data' => 'Not data'
            ], 404);
        }
    
        // Actualiza los datos
        $user->update($request->all());
        // si ya se ha actualizado muestra la respuesta
        return response()->json([
            'status' => true,
            'message' => 'User updated successdully',
            'data' => $user
        ], 200);
    }

   //ELIMINAR/DESHABILITAR
   public function deleteUser($id){
    
        // Busca el id 
        $user = Usuarios::find($id);

        // respuesta en caso de que no exista
        if(!$user){
            return response()->json([
              'status' => false,
              'message' => 'User not found',
              'data' => 'Not data'
            ], 404);
        }
        // Eliminar (cambia el estado del registro activo/desactivado)
        $user->update(['disabled' => true]);
       return response()->json([
          'status' => true,
          'message' => 'User with id ' . $id . ' deleted',
          'data' =>$user
        ], 200);
    }

  

      // registrar un nuevo usuario
    public function register(UserRequest $request){
 
        // Crear el usuario usando el modelo
        $user = Usuarios::create([
          'name' => $request->name,
          'user_name' => $request->user_name,
          'email' => $request->email,
          'password' => bcrypt($request->password),
          'phone' => $request->phone,
        ]);

           // Si se crea exitosamente
           return response()->json([
           'status' => true,
           'message' => 'New user created',
           'data' => $user
        ], 201); // Indica creado
    }
    
    public function login(LoginRequest $request){
    // Busca el usuario por email
    $user = Usuarios::where('email', $request->email)->first();

    // Verifica si el usuario existe y la contraseña es correcta (compara la contraseña de la base de datos (encriptada) con la ingresada)
    if ($user && password_verify($request->password, $user->password)) {
        // Genera un token JWT con una expiración personalizada (en minutos)
        $token = JWTAuth::claims(['exp' => now()->addMinutes(1)]) // Expira en 1 minuto
                         ->fromUser($user);

        // Ingreso exitoso
        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
        ], 200); // 200 OK
    }

    // Credenciales incorrectas (email o contraseña)
    return response()->json([
        'status' => false,
        'message' => 'Invalid credentials',
        'data' => null
    ], 401); // 401 Unauthorized
  }


  // AGUPAR POR FECHA DE CREACION
  public function getUsersGroupedByDate(){

 // Obtener los registros agrupados por la fecha de creación
    $groupedUsers = Usuarios::select(DB::raw('DATE(created_at) as date'))
                            ->groupBy('date') // agrupar por fecha
                            ->orderBy('date', 'desc') // ordenar de mayor a menor cantidad de registros por fecha
                            ->get() // mapear para mostrar toda la informacion de cada usuario
                            ->map(function ($item) {
                                $item->count = Usuarios::whereDate('created_at', $item->date)
                                                        ->where('disabled', false) // mostrar solo los usuarios no deshabilitados en el numero que indica el total de registros
                                                        ->count();
                                $item->users = Usuarios::whereDate('created_at', $item->date)
                                                        ->where('disabled', false) // mostra solo los usuarios no deshabilitados
                                                        ->get(['id', 'name', 'user_name', 'email', 'phone', 'role', 'disabled', 'created_at']); // datos de los usuarios
                                return $item;
                            });

    //Si no hay registros
    if ($groupedUsers->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'No grouped users found',
            'data' => []
        ], 404);
    }

    // Devolver los registros agrupados
    return response()->json([
        'status' => true,
        'message' => 'Users grouped by date',
        'data' => $groupedUsers
    ], 200);
}

}
