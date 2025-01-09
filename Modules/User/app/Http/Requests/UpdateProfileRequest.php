<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username' => [
                'sometimes',
                'string',
                Rule::unique('users', 'username')->ignoreModel(auth()->user()),
                'max:255',
            ],
            'email'    => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignoreModel(auth()->user()),
                'max:255',
            ],
            'address'  => ['nullable', 'string', 'max:255'],
            'phone'    => [
                'nullable',
                'string',
                'min:10',
                'max:15',
                Rule::unique('users', 'phone')->ignoreModel(auth()->user()),
            ],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() === true;
    }
}
