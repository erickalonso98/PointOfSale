<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "email"    => "required|email",
            "password" => "required|min:7"
        ];
    }

    public function messages()
    {
        return [
            "email.required"    => "el Correo electronico es requerido",
            "password.required" => "La contraseña es obligatoria"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $data = [
            "status" => "error",
            "code"   => 400,
            "errors" => $errors
        ];
        throw new HttpResponseException(response()->json($data,$data["code"]));
        //parent::failedValidation($errors);
    }
}
