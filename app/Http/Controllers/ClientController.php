<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(){

        $clients = Client::all();

        if($clients->isEmpty()){
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "No hay ningun cliente registrado"
            );
        }else{
            $data = array(
                "status"  => "success",
                "code"    => 200,
                "clients" => $clients 
            );
        }

        return response()->json($data,$data["code"]);
    }

    public function show($id){
        try {
            if($id === null || !$id || empty($id)){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id"
                );
            }

            $client = Client::find($id);

            if(is_object($client) && !empty($client)){
                $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "client"  => $client
                );
            }else{
                $data = array(
                    "status"  => "error",
                    "code"  => 404,
                    "message" => "No se encuentra el cliente"
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
                "name"     => "required|string|max:255",
                "lastname" => "nullable|string",
                "email"    => "required|email",
                "phone"    => "nullable|string",
                "adreess"  => "nullable|string" 
            ],[
                "name.required"     => "El nombre del cliente es requerido",
                "email.required"    => "El correo electronico es requerido",
            ]);

            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                );
            }else{

                $client = Client::create($request->all());

                $data = array(
                    "status"  => "success",
                    "code"    => 201,
                    "message" => "Cliente Creado con exito!!",
                    "client"  => $client
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
            if($id == null || empty($id)){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id"
                );
            }

            $validator = Validator::make($request->all(),[
                "name"     => "required|string|max:255",
                "lastname" => "nullable|string",
                "email"    => "required|email",
                "phone"    => "nullable|string",
                "adreess"  => "nullable|string" 
            ],[
                "name.required"     => "El nombre del cliente es requerido",
                "email.required"    => "El correo electronico es requerido",
            ]);

            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                );
            }else{

               $client =  Client::where('id',$id)->first()->update($request->all());

               $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "message" => "Cliente Actualizado con exito!!"
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

    public function destroy($id){
        try {
            if($id == null ){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id"
                );
            }

            $client = Client::find($id);

            if(is_object($client) && !empty($client)){
                $client->delete();
                $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "cliente" => $client,
                    "message" => "Cliente eliminado con exito!!"
                );
            }else{
                $data = array(
                    "status"  => "error",
                    "code"  => 404,
                    "message" => "No se encuentra el cliente"
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

    public function countClient(){
        $clients = Client::count();

        $data = array(
            "status"  => "success",
            "code"    => 200,
            "clients" => $clients,
            "message" => "Clientes contados con exito!!"
        );

        return response()->json($data,$data['code']);
    }
}
