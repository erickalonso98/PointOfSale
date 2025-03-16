<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Paymets;

class PaymetsController extends Controller
{
    public function index(){
        $paymets = Paymets::all();

        if($paymets->isEmpty()){
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "No se encuentran metodos de pago"
            );
        }else{
            $data = array(
                "status"  => "error",
                "code"    => 200,
                "paymets" => $paymets
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
                    "message" => "No se encuentra el id"
                );
            }

            $paymet = Paymets::find($id);

            if(!$paymet){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "No existe el metodo de pago"
                );
            }

            if(is_object($paymet) && !empty($paymet)){
                $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "paymet"  => $paymet
                );
            }else{
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "No encuentra el metodo de pago"
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
                "sale_id"         => "required|exists:sales,id",
                "payments_method" => "required|string|max:255",
                "paymets_amout"   => "required|numeric|min:0"
            ],[
                "sale_id.required"         => "La venta es requerida",
                "payments_method.required" => "El metodo de pago es requerido",
                "paymets_amout"            => "La cantidad de pago es requerido"
            ]);

            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                );
            }else{
                
                $paymet = Paymets::create($request->all());

                $data = array(
                    "status"  => "success",
                    "code"    => 201,
                    "paymet"  => $paymet,
                    "message" => "Metodo de pago creado con exito!!"
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

    public function update(Request $request, $id){
        try {
            if($id == null || !isset($id)){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id"
                );
            }

            $validator = Validator::make($request->all(),[
                "sale_id"         => "required|exists:sales,id",
                "payments_method" => "required|string|max:255",
                "paymets_amout"   => "required|numeric|min:0"
            ],[
                "sale_id.required"         => "La venta es requerida",
                "payments_method.required" => "El metodo de pago es requerido",
                "paymets_amout"            => "La cantidad de pago es requerido"
            ]);

            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                );
            }else{

                $paymet = Paymets::where(["id" => $id])->first();

                if(!$paymet){
                    $data = array(
                        "status"  => "error",
                        "code"    => 404,
                        "message" => "No existe el metodo de pago"
                    );
                }

                if(is_object($paymet) && !empty($paymet)){

                    $paymet->update($request->all());

                    $data = array(
                        "status"  => "success",
                        "code"    => 200,
                        "message" => "Metodo de pago actualizado con exito!!",
                        "paymet"  => $paymet 
                    );

                }else{
                    $data = array(
                        "status"  => "error",
                        "code"    => 404,
                        "message" => "No se encuentra el metodo de pago actualizar"
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
            
            if($id == null || !isset($id)){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id"
                );
            }

            $paymet = Paymets::where(["id" => $id])->first();

            if(is_object($paymet) && !empty($paymet)){

                $paymet->delete();

                $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "message" => "Metodo de pago eliminado con exito!!",
                    "paymet"  => $paymet 
                );

            }else{
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "No se encuentra el metodo de pago actualizar"
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
