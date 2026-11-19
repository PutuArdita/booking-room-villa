<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // akses sudah dibatasi middleware role di rute
    }

    public function rules(): array
    {
        $id = $this->route('room')?->id;

        return [
            'room_type_id' => ['required', 'exists:room_types,id'],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('rooms', 'name')
                    ->where('room_type_id', $this->input('room_type_id'))
                    ->ignore($id),
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Nama kamar sudah dipakai pada tipe ini.',
        ];
    }
}
