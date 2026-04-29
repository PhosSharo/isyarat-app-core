<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetectGestureRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image.required' => 'The image field is required.',
            'image.image'    => 'The file must be an image.',
            'image.mimes'    => 'The image must be a file of type: jpeg, png, jpg.',
            'image.max'      => 'The image may not be greater than 5MB.',
        ];
    }
}
