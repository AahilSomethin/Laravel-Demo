<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'payment_method' => ['required', 'in:cash,receipt'],
            'receipt' => ['required_if:payment_method,receipt', 'nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
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
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter your phone number.',
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'Please select a valid payment method.',
            'receipt.required_if' => 'Please upload a receipt image when paying by receipt.',
            'receipt.image' => 'The receipt must be an image file.',
            'receipt.mimes' => 'The receipt must be a JPEG, JPG, or PNG file.',
            'receipt.max' => 'The receipt image must not exceed 2MB.',
        ];
    }
}
