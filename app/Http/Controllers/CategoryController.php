<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();

        if($categories->isEmpty()){
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "No hay Categorias"
            );
        }else{
            $data = array(
                "status"     => "success",
                "code"       => 200,
                "categories" => $categories
            );
        }

        return response()->json($data,$data["code"]);
    }

    public function show($id){
        $categorie = Category::find($id);

        if(is_object($categorie) && !empty($categorie)){
            $data = array(
                "status"     => "success",
                "code"       => 200,
                "categories" => $categorie
            );
        }else{
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "La categoria no existe"
            );
        }

        return response()->json($data,$data["code"]);
    }

    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                "name"        => "required|string|max:255",
                "description" => "nullable|string"
             ]);
      
             if($validator->fails()){
                  $data = array(
                      "status"  => "error",
                      "code"    => 404,
                      "message" => "los campos son requeridos"
                  );
             }else{
      
                 $category =  Category::create([
                      "name"        => $request->input('name'),
                      "description" => $request->input('description')
                  ]);
      
                  $data = array(
                      "status"   => "success",
                      "code"     => 201,
                      "message"  => "Categoria creado con exito!!",
                      "category" => $category
                  );
             }
        } catch (\Exception $e) {
            $data = array(
                "status"  => "error",
                 "code"   => 500,
                "message" => "Error en el servidor"
            );
        }
       
       return response()->json($data,$data["code"]);
    }

    public function update(Request $request, $id){

        try {
            $validator = Validator::make($request->all(),[
                "name"        => "required|string|max:255",
                "description" => "nullable|string"
            ],[
                "name.required" => "el campo nombre de la categoria es obligatoria"
            ]);
    
            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "los campos son requeridos",
                    "erros"   => $validator->errors()
                );
            }else{
    
                $params = [
                    "name"        => $request->input('name'),
                    "description" => $request->input('description')
                ];
    
                Category::where('id',$id)->update($params);
    
                $data = array(
                    "status"   => "success",
                    "code"     => 200,
                    "message"  => "Categoria actualizado con exito!!",
                    "changes"  => $params
                );
            }
    
        } catch (\Exception $e) {
            $data = array(
                "status"  => "error",
                 "code"   => 500,
                "message" => "Error en el servidor"
            );
        }

        return response()->json($data,$data["code"]);
    }

    public function destroy($id){
        $categorie = Category::find($id);

        if(is_object($categorie) && !empty($categorie)){

            $categorie->delete();
            
            $data = array(
                "status"     => "success",
                "code"       => 200,
                "message"    => "La cetegoria eliminado con exito!!",
                "categories" => $categorie
            );
        }else{
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "La categoria no existe"
            );
        }

        return response()->json($data,$data["code"]);
    }

    public function sumCategory(){
        
        $total = Category::count();
        
        $data = array(
            "status"  => "success",
            "code"    => 200,
            "message" => "Total de categorias",
            "total"   => $total
        );
        
        return response()->json($data,$data["code"]);
    }
}
