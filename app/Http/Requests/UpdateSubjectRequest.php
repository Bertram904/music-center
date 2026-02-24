<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubjectRequest extends FormRequest
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
        $subjectId = $this->route('subject');
        return [
            'name' => ['nullable', 'string', 'max:255', 'unique:subjects,name,' . $subjectId],
            'code' => ['nullable', 'string', 'max:255', 'unique:subjects,code,' . $subjectId],
            'description' => ['nullable', 'string'],
            'is_active' => [' boolean'],
        ];
    }

    public function messages(): array
    {
        return [
          'required' => 'The :attribute field is required.',
        ];
    }
}
