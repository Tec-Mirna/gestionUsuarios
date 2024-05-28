<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Usuarios;

class UsuariosController extends Controller
{
   public function prueba(){
        return response()->json([
            'status' => true,
            'message' => 'Api working fine'
        ]);
    }
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
        return response()->json([
             'status' => true,
             'message' => 'Get all Users',
             'data' => $user
        ]);
    }

   

     // CREAR
     public function createUser(Request $request){

     // No puede haber un usuario duplicado
     $usuarioDatabase =  Usuarios::where('name', $request->name)->first();
      if($usuarioDatabase){ // si usuarioDatabase contiene un valor significa que ya existe 
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
        return response()->json([
            'status' => true,
            'message' => 'New user created',
            'data' => $user
        ], 201); // Indica creado
   }

   // EDITAR
   public function patchUser(Request $request, $id){

    $user = Usuarios::find($id);
    if(!$user){
        return response()->json([
            'status' => true,
            'message' => 'User not found',
            'data' => 'Not data'
        ], 404);
    }

    $user->update($request->all());
    return response()->json([
        'status' => true,
        'message' => 'Update user with id',
        'data' => $user
    ]);
   }

   //ELIMINAR/DESHABILITAR
   public function deleteUser($id){

    $user = Usuarios::find($id);
    if(!$user){
        return response()->json([
            'status' => false,
            'message' => 'User not found',
            'data' => 'Not data'
        ], 404);
    }

    $user->update(['disabled' => true]);
       return response()->json([
        'status' => true,
        'message' => 'User with id ' . $id . ' deleted',
        'data' =>$user
       ], 200);
   }
}
