<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            "short_description"=>["required_without_all:description,name,perks,goal_amount", 'max:100',"string"],
            "name"=>["required_without_all:description,short_description,perks,goal_amount", 'max:100',"string"],
            "description"=>["required_without_all:name,short_description,perks,goal_amount","string"],
            "perks"=>["required_without_all:description,short_description,name,goal_amount", 'max:50',"string"],
            "goal_amount"=>["required_without_all:description,short_description,perks,name","integer"],
        ];
    }


    protected function failedValidation(Validator $validator){
        // protected function failedValidation(Validator $validator){
            throw new HttpResponseException(response([
                "errors"=> $validator->getMessageBag()
            ], 400));
        }
}
