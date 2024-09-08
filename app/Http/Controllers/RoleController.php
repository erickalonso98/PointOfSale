<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleController extends Controller
{
    public function index(){

        $role = Role::all();

        if($role->isEmpty()){
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "No se encuentra ningun rol"
            );
        }else{
            $data = array(
                "status" => "success",
                "code"   => 200,
                "users"  => $role
            );
        }

        return response()->json($data,$data['code']);
    }

    public function show($id){
        try {

            if($id == null){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id a buscar"
                );
            }

            $role = Role::find($id);

            if(is_object($role) && !empty($role)){
                $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "role"    => $role
                );
            }else{
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "No se encuentra ese rol"
                );
            }

        } catch (\Exception $e) {
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "Error en el servidor"
            );
        }

        return response()->json($data,$data["code"]);
    }

    public function store(Request $request){

    try {

        $validator = Validator::make($request->all(),[
            "name" => "required|string|max:255"
        ],[
            "name.required" => "El nombre del rol es requerido"
        ]);

        if($validator->fails()){
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => $validator->errors()
            );
        }else{

            $role = Role::create(["name" => $request->input("name")]);
            $userId = $request->input("id");
            $user = User::find($userId);

            if($user){

                $user->assignRole($role);

                $data = array(
                    "status"  => "success",
                    "code"    => 201,
                    "role"    => $role,
                    "user"    => $user,
                    "message" => "Rol creado con exito!!"
                );

            }else{
                $data = [
                    "status" => "error",
                    "code" => 404,
                    "message" => "Usuario no encontrado."
                ];
            }
            
        }

        } catch (\Exception $e) {
            $data = array(
                "status"  => "error",
                "code"    => 500,
                "message" => "Error en el servidor"
            );
        }

        return response()->json($data,$data["code"]);
    }

    public function destroy($id){
        try {
            if($id == null){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id a buscar"
                );
            }

            $role = Role::find($id);

            if(is_object($role) && !empty($role)){

                $role->delete();

                $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "role"    => $role,
                    "message" => "Rol eliminado con exito!!"
                );
                
            }else{
                $data = array(
                    "status"  => "error",
                    "code"  => 404,
                    "message" => "No se encuentra el usuario"
                );
            }

        } catch (\Exception $e) {
            $data = array(
                "status"  => "error",
                "code"    => 500,
                "message" => "Error en el servidor"
            );
        }

        return response()->json($data,$data["code"]);
    }
}
