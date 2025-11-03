<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTnaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $tna = $this->tna;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tnas')
                    ->where('batch', $tna->batch) 
                    ->ignore($tna->id),
            ],
            'period' => 'required|integer|digits:4',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'speaker' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
