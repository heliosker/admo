<?php

namespace App\Http\Requests\API;

use App\Models\Task;
use InfyOm\Generator\Request\APIRequest;

class UpdateTaskAPIRequest extends APIRequest
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
        $rules = [
            'name' => 'string|max:50',
            'adv_id.*' => 'string|max:255',
            'peak_price' => 'integer',
            'min_roi' => 'numeric',
            'is_allow_bulk' => 'boolean',
            'is_allow_unbind' => 'boolean',
            'punish' => 'string|in:pause,delete',
            'status' => 'string|in:pause,inProgress',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => '请输入任务名称',
            'punish.in' => '触发配置值不正确',
            'status.in' => '任务状态值不正确',
        ];
    }
}