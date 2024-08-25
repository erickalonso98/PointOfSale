<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index(){
        $companies = Company::all();

        if($companies->isEmpty()){
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "No hay Empresa existente"
            );
        }else{
            $data = array(
                "status"    => "success",
                "code"      => 200,
                "companies" => $companies
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
                    "message" => "introduzca el id a buscar"
                );
            }else{
                $company = Company::find($id);

                if(is_object($company) && !empty($company)){
                    $data = array(
                        "status"     => "success",
                        "code"       => 200,
                        "company"    => $company
                    );
                }else{
                    $data = array(
                        "status"  => "error",
                        "code"    => 404,
                        "message" => "La Empresa no existe"
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

    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                "name"    => "required|string|max:255",
                "email"   => "required|email",
                "adreess" => "required|string",
                "phone"   => "nullable|string"
            ],[
                "name.required"    => "El nombre de la empresa es obligatoria",
                "email.required"   => "El correo de la empresa es obligatoria",
                "adreess.required" => "La direccion de la empresa es obligatoria"
            ]);

            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                );
            }else{
                $company = Company::create([
                    "name"    => $request->input('name'),
                    "email"   => $request->input('email'),
                    "adreess" => $request->input('adreess'),
                    "phone"   => $request->input('phone')
                ]);

                $data = array(
                    "status"   => "success",
                    "code"     => 201,
                    "message"  => "Empresa creado con exito!!",
                    "company"  => $company
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
            
            if($id === null || !$id || empty($id)){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "introduzca el id a buscar"
                );
            }

            $validator = Validator::make($request->all(),[
                    "name"    => "required|string|max:255",
                    "email"   => "required|email",
                    "adreess" => "required|string",
                    "phone"   => "nullable|string"
            ],[
                    "name.required"    => "El nombre de la empresa es obligatoria",
                    "email.required"   => "El correo de la empresa es obligatoria",
                    "adreess.required" => "La direccion de la empresa es obligatoria"
            ]);

            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                );
            }else{
                $params = [
                    "name"    => $request->input('name'),
                    "email"   => $request->input('email'),
                    "adreess" => $request->input('adreess'),
                    "phone"   => $request->input('phone')
                ];

                 Company::where('id',$id)->first()->update($params);

                 $data = array(
                    "status"   => "success",
                    "code"     => 200,
                    "message"  => "Empresa actualizada con exito!!",
                    "changes"  => $params
                );
            }

        } catch (\Exception $e) {
            $data = array(
                "status"  => "error",
                "code"    => 500,
                "message" => "Error en el servidor".$e->getMessage()
            );
        }

        return response()->json($data,$data["code"]);
    }

    public function destroy($id){
        try {
            if($id === null || !$id || empty($id)){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "introduzca el id a buscar"
                );
            }
                $company = Company::find($id);

                if(is_object($company) && !empty($company)){

                    $company->delete();

                    $data = array(
                        "status"     => "success",
                        "code"       => 200,
                        "company"    => $company,
                        "message"    => "Empresa eliminada con exito!!"
                    );
                }else{
                    $data = array(
                        "status"  => "error",
                        "code"    => 404,
                        "message" => "La Empresa no existe"
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


