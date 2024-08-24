<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index(){
        $providers = Supplier::all();

        if($providers->isEmpty()){
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "No hay Proveedores"
            );
        }else{
            $data = array(
                "status"     => "success",
                "code"       => 200,
                "providers"  => $providers
            );
        }

        return response()->json($data,$data["code"]);
    }

    public function show($id){
        try {

            if($id === null || !$id){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "introduzca el id a buscar"
                );
            }else{
                $provider = Supplier::find($id);
    
                if(is_object($provider) && !empty($provider)){
                    $data = array(
                        "status"     => "success",
                        "code"       => 200,
                        "provider"   => $provider
                    );
                }else{
                    $data = array(
                        "status"  => "error",
                        "code"    => 404,
                        "message" => "el proveedor no existe"
                    );
                }
            }
        } catch (\Exception $e) {
            $data = [
                "status"  => "error",
                "code"    => 500,
                "message" => "Error en el servidor"
            ];
        }
        
        return response()->json($data,$data["code"]);
    }

    public function store(Request $request){
        try {
           $validator = Validator::make($request->all(),[
                "name"     => "required|max:255",
                "lastname" => "required|max:255",
                "email"    => "required|email|string",
                "company"  => "required|string",
                "address"  => "nullable|string|max:255",
                "phone"    => "nullable|string"
           ],[
                "name.required"     => "el campo nombre es obligatorio",
                "lastname.required" => "el campo de apellido es obligatorio",
                "email.required"    => "el campo de correo electronico es obligatorio",
                "company.required"  => "el campo de la empresa es obligatorio",
           ]);

           if($validator->fails()){
                $data = [
                    "status"   => "error",
                    "code"     => 404,
                    "messages" => $validator->errors()
                ];
           }else{
                $provider = new Supplier();

                $provider->name = $request->input('name');
                $provider->lastname = $request->input('lastname');
                $provider->email = $request->input('email');
                $provider->company = $request->input('company');
                $provider->address = $request->input('address');
                $provider->phone = $request->input('phone');

                $provider->save();

                $data = [
                    "status"   => "success",
                    "code"     => 201,
                    "message"  => "Datos del proveedor creado con exito!!",
                    "provider" => $provider
                ];
           }
        } catch (\Exception $e) {
            $data = [
                "status"  => "error",
                "code"    => 500,
                "message" => "Error en el servidor"
            ];
        }

        return response()->json($data,$data["code"]);
    }

    public function update(Request $request, $id){
        try {
            
            if($id === null || !$id){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "introduzca el id a modificar"
                );
            }else{
                $validator = Validator::make($request->all(),[
                    "name"     => "required|max:255",
                    "lastname" => "required|max:255",
                    "email"    => "required|email|string",
                    "company"  => "required|string",
                    "address"  => "nullable|string|max:255",
                    "phone"    => "nullable|string"
                ],[
                    "name.required"     => "el campo nombre es obligatorio",
                    "lastname.required" => "el campo de apellido es obligatorio",
                    "email.required"    => "el campo de correo electronico es obligatorio",
                    "company.required"  => "el campo de la empresa es obligatorio",
               ]);
               
               if($validator->fails()){
                    $data = [
                        "status"   => "error",
                        "code"     => 404,
                        "messages" => $validator->errors()
                    ];
               }else{
                    $params = [
                        "name"     => $request->input('name'),
                        "lastname" => $request->input('lastname'),
                        "email"    => $request->input('email'),
                        "company"  => $request->input('company'),
                        "address"  => $request->input('address'),
                        "phone"    => $request->input('phone')
                    ];

                    Supplier::where('id',$id)->update($params);

                    $data = array(
                        "status"   => "success",
                        "code"     => 200,
                        "message"  => "Proveedor actualizado con exito!!",
                        "changes"  => $params
                    );
               }
            }

        } catch (\Exception $e) {
            $data = [
                "status"  => "error",
                "code"    => 500,
                "message" => "Error en el servidor"
            ];
        }

        return response()->json($data,$data["code"]);
    }

    public function destroy($id){
        try {
            if($id === null || !$id){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "introduzca el id a eliminar"
                );
            }else{
                $provider = Supplier::find($id);
    
                if(is_object($provider) && !empty($provider)){

                    $provider->delete();

                    $data = array(
                        "status"   => "success",
                        "code"     => 200,
                        "provider" => $provider,
                        "message"  => "proveedor eliminado con exito"
                    );
                }else{
                    $data = array(
                        "status"  => "error",
                        "code"    => 404,
                        "message" => "el proveedor no existe"
                    );
                }
            }
        } catch (\Exception $e) {
            $data = [
                "status"  => "error",
                "code"    => 500,
                "message" => "Error en el servidor"
            ];
        }

        return response()->json($data,$data["code"]);
    }
}
