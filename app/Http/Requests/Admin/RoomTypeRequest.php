<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // akses sudah dibatasi middleware role di rute
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name')),
        ]);
    }

    public function rules(): array
    {
        $id = $this->route('roomType')?->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'max:120', Rule::unique('room_types', 'slug')->ignore($id)],
            'description' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'base_price' => ['required', 'integer', 'min:0'],
        ];
    }
}
