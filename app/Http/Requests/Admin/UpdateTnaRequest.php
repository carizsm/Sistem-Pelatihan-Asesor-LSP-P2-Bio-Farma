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

    public function rules(): array
    {
        return [
            // Informasi Umum TNA
            'name'          => ['required', 'string', 'max:255'],
            'method'        => ['required', Rule::in(['Online', 'Offline', 'Hybrid'])],
            'passing_score' => ['required', 'integer', 'between:0,100'],
            'period'        => ['required', 'integer', 'digits:4'],

            // Jadwal Pelatihan
            'start_date'    => ['required', 'date'],
            'end_date'      => ['required', 'date', 'after_or_equal:start_date'],
            'speaker'       => ['required', 'string', 'max:255'],
            'spt_file_path' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],

            // Analisis dan Justifikasi
            'reason'        => ['required', 'string', 'max:1000'],
            'goal'          => ['required', 'string', 'max:1000'],
            'before_status' => ['required', 'string', 'max:1000'],
            'after_status'  => ['required', 'string', 'max:1000'],
            'realization_status' => ['required', Rule::enum(RealizationStatus::class)],
        ];
    }

    /**
     * Custom error messages (Bahasa Indonesia).
     */
    public function messages(): array
    {
        return [
            'required'       => ':attribute wajib diisi.',
            'string'         => ':attribute harus berupa teks.',
            'integer'        => ':attribute harus berupa angka bulat.',
            'digits'         => ':attribute harus berjumlah :digits digit.',
            'date'           => 'Format :attribute tidak valid.',
            'after_or_equal' => ':attribute tidak boleh mendahului Tanggal Mulai.',
            'between'        => ':attribute harus bernilai antara :min sampai :max.',
            'file'           => ':attribute harus berupa berkas.',
            'mimes'          => 'Format :attribute harus berupa: :values.',
            'max'            => [
                'numeric' => ':attribute tidak boleh lebih dari :max.',
                'file'    => 'Ukuran :attribute maksimal :max kilobyte (2MB).',
                'string'  => ':attribute maksimal :max karakter.',
            ],
            'enum'           => 'Pilihan :attribute tidak valid.',
        ];
    }

    /**
     * Custom attribute names (Supaya errornya "Nama Pelatihan wajib diisi" bukan "name wajib diisi").
     */
    public function attributes(): array
    {
        return [
            'name'               => 'Nama Pelatihan',
            'method'             => 'Metode Pelatihan',
            'passing_score'      => 'Skor Kelulusan',
            'period'             => 'Periode',
            'start_date'         => 'Tanggal Mulai',
            'end_date'           => 'Tanggal Selesai',
            'speaker'            => 'Pembicara/Instruktur',
            'spt_file_path'      => 'File SPT',
            'reason'             => 'Alasan',
            'goal'               => 'Tujuan',
            'before_status'      => 'Kondisi Sebelum',
            'after_status'       => 'Kondisi Diharapkan',
            'realization_status' => 'Status Realisasi',
        ];
    }
}