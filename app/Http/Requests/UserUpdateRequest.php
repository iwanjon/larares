<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->get("current_user") != null;
        // return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            "email"=>["required_without_all:password,name,occupation,role","email"],
            "name"=> ["required_without_all:password,email,occupation,role"],
            "password"=> ["required_without_all:email,name,occupation,role"],
            "occupation"=> ["required_without_all:password,name,email,role"],
            "role"=> ["required_without_all:password,name,occupation,email"],
        ];
    }


    protected function failedValidation(Validator $validator){
        // protected function failedValidation(Validator $validator){
            throw new HttpResponseException(response([
                "errors"=> $validator->getMessageBag()
            ], 400));
        }

}
