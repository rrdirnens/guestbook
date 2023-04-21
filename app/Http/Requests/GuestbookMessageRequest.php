<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Log;

class GuestbookMessageRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation() {
        // handy method for checking payload before validation
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // TODO: Implement authorization logic here (Admin + IP/time check)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|max:1000',
            'image' => 'nullable|image|max:4096|mimes:jpg,jpeg,png',
        ];
    }
}
