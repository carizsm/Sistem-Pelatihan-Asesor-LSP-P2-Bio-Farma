<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\RealizationStatus;
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
            // Informasi Umum TNA
            'name' => 'required|string|max:255',
            'method' => 'required|string|max:255',
            'passing_score' => 'required|integer|between:60,100',
            'period' => 'required|integer|digits:4',
            
            // Jadwal Pelatihan
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'speaker' => 'required|string|max:255',
            'spt_file_path' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            
            // Analisis dan Justifikasi
            'reason' => 'required|string|max:500',
            'goal' => 'required|string|max:500',
            'before_status' => 'required|string|max:500',
            'after_status' => 'required|string|max:500',
            'realization_status' => [
                'required', 
                Rule::enum(RealizationStatus::class)
            ],
        ];
    }
}
