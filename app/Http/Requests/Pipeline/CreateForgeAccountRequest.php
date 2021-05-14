<?php

namespace App\Http\Requests\Pipeline;

use App\Enums\PipelineType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateForgeAccountRequest extends FormRequest
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
            'name' => 'required|string',
            'key' => 'required|string',
        ];
    }
}
