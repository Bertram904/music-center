<?php

declare(strict_types = 1);

namespace App\Http\Requests\Shift;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShiftRequest extends FormRequest
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
        $shiftId = $this->route('id');
        return [
            'name' => [
              'required', 'string', 'min:3', 'max:100',
                Rule::unique('shifts', 'name')->ignore($shiftId, 'id')->whereNull('deleted_at'),
            ],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_date'],
            'is_active' => ['boolean'],
        ];
    }
}
