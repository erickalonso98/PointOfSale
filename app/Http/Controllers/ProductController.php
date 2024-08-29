<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();

        if($products->isEmpty()){
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "No hay Productos existentes"
            );
        }else{
            $data = array(
                "status"   => "success",
                "code"     => 200,
                "products" => $products
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
                    "message" => "Introduzca un id"
                );
            }

            $product = Product::find($id);

            if(is_object($product) && !empty($product) && $product){
                $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "product" => $product
                );
            }else{
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "No se encuentra dicho producto"
                );
            }

        } catch (\Exception $e) {
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "Error en el servidor ".$e->getMessage()
            );
        }

        return response()->json($data,$data['code']);
    }

    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'code'           => 'required|unique:products,code',
                'name'           => 'required|string|max:255',
                'description'    => 'nullable|string',
                'purchase_price' => 'required|numeric|min:0',
                'sale_price'     => 'required|numeric|min:0',
                'stock'          => 'required|integer|min:0',
                'minimum_stock'  => 'required|integer|min:0',
                'photo'          => 'nullable|string',
                'status'         => 'required|in:ACTIVE,INACTIVE',
                'brand'          => 'nullable|string|max:255',
                'categories_id'  => 'required|exists:categories,id',
                'suppliers_id'   => 'required|exists:suppliers,id',
            ],[
                'code.required'           => 'El codigo del producto es requerido',
                'name.required'           => 'El nombre del producto es requerido',
                'purchase_price.required' => 'El precio de compra del producto es requerido',
                'sale_price.required'     => 'El precio de venta del producto es requerido',
                'stock.required'          => 'El stock del producto es requerido',
                'minimum_stock.required'  => 'El stock minimo es requerido',
                'status.required'         => 'El estado del  producto es requerido',
                'categories_id.required'  => 'La categoria del producto es requerido',
                'suppliers_id.required'   => 'El proveedor de producto es requerido',
            ]);

            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                );
            }else{

                $product = Product::create($request->all());

                $data = array(
                    "status"  => "success",
                    "code"    => 201,
                    "product" => $product,
                    "message" => "Producto Creado con exito!!"
                );
            }

        } catch (\Exception $e) {
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "Error en el servidor ".$e->getMessage()
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
                    "message" => "Introduzca un id"
                );
            }

            $validator = Validator::make($request->all(),[
                'code'           => 'required|unique:products,code',
                'name'           => 'required|string|max:255',
                'description'    => 'nullable|string',
                'purchase_price' => 'required|numeric|min:0',
                'sale_price'     => 'required|numeric|min:0',
                'stock'          => 'required|integer|min:0',
                'minimum_stock'  => 'required|integer|min:0',
                'photo'          => 'nullable|string',
                'status'         => 'required|in:ACTIVE,INACTIVE',
                'brand'          => 'nullable|string|max:255',
                'categories_id'  => 'required|exists:categories,id',
                'suppliers_id'   => 'required|exists:suppliers,id',
            ],[
                'code.required'           => 'El codigo del producto es requerido',
                'name.required'           => 'El nombre del producto es requerido',
                'purchase_price.required' => 'El precio de compra del producto es requerido',
                'sale_price.required'     => 'El precio de venta del producto es requerido',
                'stock.required'          => 'El stock del producto es requerido',
                'minimum_stock.required'  => 'El stock minimo es requerido',
                'status.required'         => 'El estado del  producto es requerido',
                'categories_id.required'  => 'La categoria del producto es requerido',
                'suppliers_id.required'   => 'El proveedor de producto es requerido',
            ]);

            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                );
            }else{

                Product::where('id',$id)->first()->update($request->all());

                $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "message" => "Producto Actualizado con exito!!"
               );
            }

        } catch (\Exception $e) {
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "Error en el servidor ".$e->getMessage()
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

            $product = Product::find($id);

            if(is_object($product) && !empty($product)){
                
                $product->delete();

                $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "product" => $product,
                    "message" => "Producto eliminado con exito!!"
                );
            }else{
                $data = array(
                    "status"  => "error",
                    "code"  => 404,
                    "message" => "No se encuentra el producto"
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
