<?php

namespace App\Http\Requests;

use App\Rules\FileExist;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title' => 'required|string|max:200',
            'body' => 'nullable|max:2000',
            'status' => 'required|in:draft,enable,disable',
            'publish_at' => 'nullable|date',
            // tag:"" is clear
            'tag' => 'sometimes|array|nullable|max:6',
            'tag.*' => 'required|string',
            // gallery:file name
            'gallery' => 'sometimes|array|max:10',
            'gallery.*' => ['required', 'string', new FileExist],
        ];
    }
}
