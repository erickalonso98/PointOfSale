<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index(){
        $permissions = Permission::all();
        
        if($permissions->isEmpty()){
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "No se encuentra ningun permiso"
            );
        }else{
            $data = array(
                "status"      => "success",
                "code"        => 200,
                "permissions" => $permissions
            );
        }

        return response()->json($data,$data["code"]);
    }

    public function show($id){
        try {
            
            if($id == null){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id"
                );
            }

            $permission = Permission::where(["id" => $id])->first();

            if(is_object($permission) && !empty($permission)){
                    $data = array(
                        "status"     => "success",
                        "code"       => 200,
                        "permission" => $permission
                    );
            }else{
                    $data = array(
                        "status"  => "error",
                        "code"    => 404,
                        "message" => "No se encuentra el permiso"
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

    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                "name" => "required|string|max:255"
            ],[
                "name.required" => "El permiso es requerido"
            ]);

            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                );
            }else{

                $permission = Permission::create([ "name" => $request->input("name") ]);

                $data = array(
                    "status"     => "success",
                    "code"       => 201,
                    "permission" => $permission,
                    "message"    => "Permiso creado con exito!!"
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

    public function assignPermissionToRole(){
        
    }

    public function update(Request $request, $id){
        try {

            if($id == null){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id"
                );
            }

            $validator = Validator::make($request->all(),[
                "name" => "required|string|max:255"
            ],[
                "name.required" => "El permiso es requerido"
            ]);

            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                );
            }else{

                $permission = Permission::where(["id" => $id])->first();

                if(is_object($permission) && !empty($permission)){
                    $permission->update($request->all());

                    $data = array(
                        "status"     => "success",
                        "code"       => 200,
                        "permission" => $permission,
                        "message"    => "Permiso actualizado con exito!!"
                    );

                }else{
                    $data = array(
                        "status"  => "error",
                        "code"    => 404,
                        "message" => "No se encuentra el permiso"
                    );
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
                    "message" => "Introduzca el id"
                );
            }

            $permission = Permission::where(["id" => $id])->first();

            if(is_object($permission) && !empty($permission)){

                $permission->delete();

                $data = array(
                    "status"     => "success",
                    "code"       => 200,
                    "permission" => $permission,
                    "message"    => "Permiso Eliminado con exito!!"
                );

            }else{
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "No se encuentra el permiso"
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
