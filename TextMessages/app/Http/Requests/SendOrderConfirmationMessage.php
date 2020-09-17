<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendOrderConfirmationMessage extends FormRequest
{
    public function rules(): array
    {
        return [
            'restaurant_name' => 'required|min:2|max:100',
            'delivery_time'   => 'required|date_format:"Y-m-d H:i"',
            'phone_number'    => 'required',
        ];
    }
}
