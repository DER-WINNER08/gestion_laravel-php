<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "title"       => "required|string|max:255",
            "description" => "nullable|string",
            "status"      => "required|in:draft,in_progress,completed",
            "category_id" => "required|exists:categories,id",
        ];
    }
}
