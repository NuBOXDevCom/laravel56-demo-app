<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $id = $this->category ? ',' . $this->category->id : '';
        return [
            'name' => 'required|string|max:255|unique:categories,name' . $id,
        ];
    }
}