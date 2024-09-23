<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Sale;

class SaleController extends Controller
{
    public function index(){

        $sales = Sale::all();
        
        if($sales->isEmpty()){
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "No hay registros en el sistema"
            ];
        }else{
            $data = [
                "status"  => "success",
                "code"    => 200,
                "sales"   => $sales
            ];
        }

        return response()->json($data,$data["code"]);
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

            $sale = Sale::find($id);

            if(is_object($sale) && !empty($sale) && $sale){
                $data = [
                    "status"  => "success",
                    "code"    => 200,
                    "sale"    => $sale
                ];
            }else{
                $data = [
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "No se encuentra la venta"
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

    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'user_id'   => 'required|exists:users,id',
                'client_id' => 'required|exists:clients,id',
                'box_id'    => 'required|exists:boxes,id',
                'total'     => 'required|numeric|min:0',  
            ],[
                "user_id.required"   => "El Usuario es requerido",
                "client_id.required" => "El Cliente es requerido",
                "box_id.required"    => "La caja es requerido",
                "total.required"     => "El monto total es requerido"
            ]);

            if($validator->fails()){
                $data = [
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                ];
            }else{

                $sale = Sale::create($request->all());

                $data = [
                    "status"  => "success",
                    "code"    => 201,
                    "sale"    => $sale,
                    "message" => "Venta generada con exito!!"
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
            if($id == null){
                $data = [
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id"
                ];
            }

            $validator = Validator::make($request->all(), [
                'user_id'   => 'required|exists:users,id',
                'client_id' => 'required|exists:clients,id',
                'box_id'    => 'required|exists:boxes,id',
                'total'     => 'required|numeric|min:0',  
            ],[
                "user_id.required"   => "El Usuario es requerido",
                "client_id.required" => "El Cliente es requerido",
                "box_id.required"    => "La caja es requerido",
                "total.required"     => "El monto total es requerido"
            ]);

            if($validator->fails()){
                $data = [
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                ];
            }else{

                $sale = Sale::find($id);

                if(is_object($sale) && !empty($sale) && $sale){

                    $sale->update($request->all());

                    $data = [
                        "status"  => "success",
                        "code"    => 200,
                        "sale"    => $sale,
                        "message" => "Venta actualizada con exito!!"
                    ];
                }else{
                    $data = [
                        "status"  => "error",
                        "code"    => 404,
                        "message" => "No se encuentra la venta"
                    ];
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

            if($id == null){
                $data = [
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id"
                ];
            }

            $sale = Sale::find($id);

            if(!$sale){
                $data = [
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Venta no encontrada"
                ];
            }

            if(is_object($sale) && !empty($sale) && $sale){

                $sale->delete();

                $data = [
                    "status"  => "success",
                    "code"    => 200,
                    "sale"    => $sale,
                    "message" => "Venta Eliminada!!"
                ];

            }else{
                $data = [
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "No se logro eliminar la venta no encontrada"
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
}
