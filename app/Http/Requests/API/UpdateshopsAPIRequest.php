<?php

namespace App\Http\Requests\API;

use App\Models\shops;
use InfyOm\Generator\Request\APIRequest;

class UpdateshopsAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = shops::$rules;
        
        return $rules;
    }
}
