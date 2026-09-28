<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TaskFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "status"    => ["nullable","in:draft,in_progress,completed"],
            "perpage"   => ["nullable", "integer", "min:1", "max:4"],
            "category_id" => ["nullable", "integer", "exists:categories,id"],
            "search" => ["nullable", "string", "max:255"],
        ];
    }
}
