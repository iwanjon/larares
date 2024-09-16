<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return $this->get("current_user") != null;
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
            "short_description"=>["nullable", 'max:100',"string"],
            "name"=>["required", 'max:100',"string"],
            "description"=>["required","string"],
            "perks"=>["required","string",'max:50'],
            "goal_amount"=>["required","integer"],
        ];
    }

    protected function failedValidation(Validator $validator){
        // protected function failedValidation(Validator $validator){
            throw new HttpResponseException(response([
                "errors"=> $validator->getMessageBag()
            ], 400));
        }
}
