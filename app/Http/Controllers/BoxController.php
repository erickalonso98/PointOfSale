<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Box;

class BoxController extends Controller
{
    public function index(){

        $boxes = Box::all();

        if($boxes->isEmpty()){
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "No se encuentra la caja"  
            ];
        }else{
            $data = [
                "status" => "success",
                "code"   => 200,
                "boxes"  => $boxes
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function show($id){
        try {
            if($id == null){
                $data = [
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id"
                ];
            }

            $box = Box::find($id);

            if(is_object($box) && !empty($box) && $box){
                $data = [
                    "status" => "success",
                    "code"   => 200,
                    "box"    => $box
                ];
            }else{
                $data = [
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "No se encuentra la caja"  
                ];
            }

        } catch (\Exception $e) {
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "Error en el servidor"  
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                "name"            => "required|string|max:255",
                "initial_balance" => "required|numeric|min:0",
                "current_balance" => "required|numeric|min:0"
            ],[
                "name.required"            => "El Nombre de caja es requerido",
                "initial_balance.required" => "El saldo inicial es requerido",
                "current_balance.required" => "El saldo actual es requerido"
            ]);

            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                );
            }else{
                $box = Box::create($request->all());

                $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "box"     => $box,
                    "message" => "La caja Creada exitosamente!!"
                );
            }

        } catch (\Exception $e) {
           $data = array(
                "status"  => "error",
                "code"    => 500,
                "message" => "Error en el servidor"
           );
        }

        return response()->json($data,$data['code']);
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
                "name"            => "required|string|max:255",
                "initial_balance" => "required|numeric|min:0",
                "current_balance" => "required|numeric|min:0"
            ],[
                "name.required"            => "El Nombre de caja es requerido",
                "initial_balance.required" => "El saldo inicial es requerido",
                "current_balance.required" => "El saldo actual es requerido"
            ]);

            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                );
            }else{

                $box = Box::where('id',$id)->first();

                if(is_object($box) && !empty($box)){

                    $box->update($request->all());
                    
                    $data = array(
                        "status"  => "success",
                        "code"    => 200,
                        "message" => "Caja Aztualizada con exito!!"
                    );

                }else{
                    $data = array(
                        "status"  => "error",
                        "code"    => 404,
                        "message" => "No se encuentra el id a actualizar"
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

        return response()->json($data,$data['code']);
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

            $box = Box::where('id',$id)->first();

            if(is_object($box) && !empty($box)){
                $box->delete();
                $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "box"     => $box,
                    "message" => "Caja eliminado con exito"
                );
            }else{
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "No se encuentra el id a eliminar"
                );
            }

        } catch (\Exception $e) {
            $data = array(
                "status"  => "error",
                "code"    => 500,
                "message" => "Error en el servidor"
            );
        }

        return response()->json($data,$data['code']);
    }
    
}
