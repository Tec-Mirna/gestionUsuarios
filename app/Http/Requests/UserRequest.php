<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // si está en false, no me autoriza hacer solicitudes
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    { 
        
        return [
          'name' => 'required|string|max:84',
          'user_name' => 'required|string|max:25',
          'email' => 'required|string', // de tipo email
          'password' => 'required|min:8|max:16',
          'phone' => 'required|string|max:27',

        ];
    }


    public function failedValidation(Validator $validator){
        $response = response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }

}

