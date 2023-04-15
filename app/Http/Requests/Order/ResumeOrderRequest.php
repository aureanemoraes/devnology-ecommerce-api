<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ResumeOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'items' => [ 'required', 'array', 'min:1' ],
            'items.*.id' => [ 'string', 'max:255' ],
            'items.*.name' => [ 'string', 'max:255' ],
            'items.*.supplier' => [ 'string', 'max:255' ],
        ];
    }
}
