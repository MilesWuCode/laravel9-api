<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:200',
            'body' => 'sometimes|nullable|max:2000',
            'status' => 'required|in:draft,enable,disable',
            'publish_at' => 'sometimes|nullable|date',
            // tag:"" is clear
            'tag' => 'sometimes|array|nullable|max:6',
            'tag.*' => 'required',
        ];
    }
}
