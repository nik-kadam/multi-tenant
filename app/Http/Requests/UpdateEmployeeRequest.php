<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
        return [
            'edit_name' => 'required',
            'edit_email' => 'required|email',
            'edit_position' => 'required',
            'edit_department' => 'required',
            'edit_salary' => 'required',
            'edit_joining_date' => 'required',
        ];
    }
}
