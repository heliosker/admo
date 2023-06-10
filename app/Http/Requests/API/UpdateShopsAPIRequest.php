<?php

namespace App\Http\Requests\API;

use App\Models\shops;
use InfyOm\Generator\Request\APIRequest;

class UpdateShopsAPIRequest extends APIRequest
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
        return [
            'mark' => 'nullable|string|max:255',
            'is_allow_unbind' => 'boolean',
            'tags_id' => 'array'
        ];
    }
}
