<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;

class UserController extends Controller
{
    public function index(){

        $users = User::with("roles")->get();

        if($users->isEmpty()){
            $data = array(
                "status"  => "error",
                "code"    => 404,
                "message" => "No se encuentra ningun usuario"
            );
        }else{
            $data = array(
                "status" => "success",
                "code"   => 200,
                "users"  => $users
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

            $user = User::find($id);

            if(is_object($user) && !empty($user)){
                $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "user"    => $user
                );
            }else{
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "No se encuentra ese usuario"
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

    public function register(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                "name"              => "required|string|max:255",
                "lastname"          => "required|string|max:255",
                "email"             => "required|email|unique:users",
                "email_verified_at" => "nullable",
                "password"          => "required|min:7|confirmed",
                "photo"             => "nullable"
            ],[
                "name.required"     => "El nombre del usuario es requerido",
                "lastname.required" => "Los apellidos del usuario son requeridos",
                "email.required"    => "El correo del usuario es requerido",
                "password.required" => "La contraseña del usuario es requerido"
            ]);

            if($validator->fails()){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => $validator->errors()
                );
            }else{
                $user = new User();
                $user->name = $request->input('name');
                $user->lastname = $request->input('lastname');
                $user->email = $request->input('email');
                $pwd = Hash::make($request->input('password'));
                $user->password = $pwd;

                $user->save();

                $token = JWTAuth::fromUser($user);

                $data = array(
                    "status"  => "success",
                    "code"    => 201,
                    "user"    => $user,
                    "message" => "Usuario Creado con exito!!",
                    "token"   => $token
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

    public function login(LoginRequest $request){

        try {

            $credentials = $request->only(["email","password"]);

            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json([
                    "status" => "error",
                    "message" => "Error de Autenticacion"
                ],400);
            }

            $identity = Auth::user();

            $data = array(
                "status"   => "success",
                "code"     => 200,
                "identity" => $identity,
                "token"    => $token
            );

        } catch (JWTException $e) {
            return response()->json(["status" => "error","message" => "Error en el servidor ".$e->getMessage()],500);
        }

        return response()->json($data,$data["code"]);
    }

    public function update(Request $request, $id){
        try {
            if($id == null){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id a buscar"
                );
            }

            $user = JWTAuth::authenticate($request->bearerToken());

            if(!$user){
                $data = [
                    "status"  => "error",
                    "code"    => 401,
                    "message" => "Token invalido"
                ];
            }

            $validator = Validator::make($request->all(),[
                "name"              => "required|string|max:255",
                "lastname"          => "required|string|max:255",
                "email"             => "required|email|unique:users",
                "email_verified_at" => "nullable",
                "password"          => "required|min:7|confirmed",
                "photo"             => "nullable"
            ],[
                "name.required"     => "El nombre del usuario es requerido",
                "lastname.required" => "Los apellidos del usuario son requeridos",
                "email.required"    => "El correo del usuario es requerido",
                "password.required" => "La contraseña del usuario es requerido"
            ]);
    
            if($validator->fails()){
                $data = [
                    "status"  => "error",
                    "code"    => 400,
                    "message" => $validator->errors()
                ];
            }else{
                $user->name = $request->input("name");
                $user->lastname = $request->input("lastname");
                $user->email = $request->input("email");
                $pwd = Hash::make($request->input("password"));
                $user->password = $pwd;
    
                
                $user->save();
    
                $data = [
                    "status"  => "success",
                    "code"    => 200,
                    "user"    => $user,
                    "message" => "Usuario actualizado con exito!!", 
                ];
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

    public function destroy($id){
        try {

            if($id == null){
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Introduzca el id a buscar"
                );
            }

            $user = User::find($id);

            if(is_object($user) && !empty($user)){

                $user->delete();

                $data = array(
                    "status"  => "success",
                    "code"    => 200,
                    "user"    => $user,
                    "message" => "Usuario eliminado con exito!!"
                );
            }else{
                $data = array(
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "No se encuentra ese usuario"
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
}
