<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roomId = $this->route('id');
        return [
            'name' => [
                'required', 'string',
                'max:100',
                Rule::unique('rooms', 'name')
                    ->ignore($roomId)
                    ->whereNull('deleted_at')
            ],
            'capacity' => [
                'required', 'integer',
                'min:1',
                'max:100',
            ],
            'description' => [
                'nullable', 'string',
                'max:1000'
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }
}
