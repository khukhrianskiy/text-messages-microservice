<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LatestTextMessages extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit' => 'sometimes|required|numeric',
        ];
    }
}
