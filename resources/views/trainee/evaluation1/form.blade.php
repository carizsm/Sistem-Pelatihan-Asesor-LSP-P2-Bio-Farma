@extends('layouts.trainee')

@section('title', 'Feedback Evaluasi 1')

@section('content')
    {{-- Navbar Atas --}}
    <div class="flex items-center bg-[#F3F3F3] rounded-xl p-2 shadow-sm mb-3">
        {{-- Title di tengah --}}
        <div class="flex-1 text-center">
            <h1 class="font-semibold text-lg">
                Evaluasi 1 - {{ $tna->name }}
                @if(isset($feedback))
                    <span class="text-lg text-green-600 font-semibold">(Review)</span>
                @endif
            </h1>
        </div>
    </div>

    {{-- Isi Konten --}}
    <div class="bg-white rounded-xl shadow-sm p-6 flex-1 overflow-y-auto">
        <form id="feedback-form" method="POST" action="{{ route('evaluasi1.store', $registration) }}"
            @if(!isset($feedback)) 
                data-autosave="feedback_{{ $registration->id }}" 
            @endif
            >
            @csrf
            <div class="overflow-x-auto">
                <table class="evaluation-table w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-orange-400 bg-orange-200 px-4 py-3">NO</th>
                            <th class="border border-orange-400 bg-orange-200 px-4 py-3">OBJEK PENGAMATAN</th>
                            <th class="border border-orange-400 bg-orange-200 px-4 py-3">INDIKATOR MAKS</th>
                            <th class="border border-orange-400 bg-orange-200 px-4 py-3">SKALA</th>
                            <th class="border border-orange-400 bg-orange-200 px-4 py-3">INDIKATOR MIN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $questions = [
                                // Tujuan Pelatihan
                                ['Tujuan pelaksanaan pelatihan', 'Tercapai', 'Tidak Tercapai'],
                                ['Tujuan Anda mengikuti pelatihan ini', 'Tercapai', 'Tidak Tercapai'],
                                
                                // Materi Pelatihan - separator
                                ['__SEPARATOR__', 'MATERI PELATIHAN', ''],
                                ['Cakupan materi', 'Sangat Lengkap', 'Tidak Lengkap'],
                                ['Kedalaman Materi', 'Sangat Dalam', 'Sangat Dangkal'],
                                ['Daya Tarik Topik', 'Sangat Menarik', 'Tidak Menarik'],
                                
                                // Alokasi Waktu - separator
                                ['__SEPARATOR__', 'ALOKASI WAKTU', ''],
                                ['Alokasi Waktu Pelaksanaan Pelatihan', 'Panjang', 'Pendek'],
                                ['Alokasi Waktu Untuk Diskusi', 'Panjang', 'Pendek'],
                                
                                // Instruktur - separator
                                ['__SEPARATOR__', 'INSTRUKTUR', ''],
                                ['Daya Tarik Penyampaian Topik', 'Sangat Menarik', 'Tidak Menarik'],
                                ['Penguasaan Atas Materi Pelatihan', 'Sangat Baik', 'Sangat Buruk'],
                                ['Penyampaian Materi', 'Sistematik', 'Tidak Sistematik'],
                                ['Kemampuan Menjawab Pertanyaan', 'Sangat Baik', 'Sangat Buruk'],
                                
                                // Fasilitas Pelatihan - separator
                                ['__SEPARATOR__', 'FASILITAS PELATIHAN', ''],
                                ['Kualitas Tempat Pelatihan', 'Sangat Baik', 'Sangat Buruk'],
                                ['Kualitas Modul/Handouts', 'Sangat Dalam', 'Sangat Dangkal'],
                                
                                // Hasil Pelatihan - separator
                                ['__SEPARATOR__', 'HASIL PELATIHAN', ''],
                                ['Manfaat Pelatihan', 'Sangat Bermanfaat', 'Tidak Bermanfaat'],
                                ['Aplikasi Pada Pekerjaan', 'Aplikatif', 'Tidak Aplikatif'],
                            ];
                            
                            $questionNumber = 0;
                        @endphp

                        @foreach($questions as $index => $question)
                            @if($question[0] === '__SEPARATOR__')
                                {{-- Category Separator Row --}}
                                <tr class="bg-gray-100">
                                    <td colspan="5" class="border border-gray-300 px-4 py-2 text-center font-bold">
                                        {{ $question[1] }}
                                    </td>
                                </tr>
                            @else
                                @php 
                                    $questionNumber++; 
                                    $scoreField = sprintf('score_%02d', $questionNumber);
                                    $savedScore = isset($feedback) ? $feedback->$scoreField : null;
                                @endphp
                                <tr class="{{ isset($feedback) ? 'bg-gray-50' : '' }}">
                                    <td class="border border-gray-300 text-center font-semibold">{{ $questionNumber }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $question[0] }}</td>
                                    <td class="border border-gray-300 text-center px-4">{{ $question[1] }}</td>
                                    <td class="border border-gray-300">
                                        <div class="flex justify-center gap-4 py-2">
                                            @for($i = 4; $i >= 1; $i--)
                                                <label class="flex items-center gap-1 {{ isset($feedback) ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                                                    <input type="radio" 
                                                           name="score_{{ str_pad($questionNumber, 2, '0', STR_PAD_LEFT) }}" 
                                                           value="{{ $i }}"
                                                           {{ isset($feedback) && $savedScore == $i ? 'checked' : '' }}
                                                           {{ isset($feedback) ? 'disabled' : 'required' }}
                                                           class="w-4 h-4 {{ isset($feedback) ? 'cursor-not-allowed' : '' }}">
                                                    <span class="text-sm {{ isset($feedback) && $savedScore == $i ? 'font-bold text-[#F26E22]' : '' }}">
                                                        {{ $i }}
                                                    </span>
                                                </label>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="border border-gray-300 text-center px-4">{{ $question[2] }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                {{-- Button kanan --}}
                <div class="mt-4 w-full flex justify-end items-center"> 
                    @if(!isset($feedback))
                        <button type="submit" form="feedback-form"
                            class="inline-flex items-center justify-center bg-[#F26E22] hover:bg-[#d65c1c] text-white text-sm px-4 py-2 rounded-lg font-semibold min-w-[120px] transition whitespace-nowrap leading-none">
                            Selesai
                        </button>
                    @else
                        <a href="{{ route('peserta.evaluasi1') }}"
                        class="inline-flex items-center justify-center bg-gray-500 text-white text-sm px-4 py-2 rounded-lg font-semibold hover:bg-gray-600 min-w-[120px] transition whitespace-nowrap leading-none">
                            Kembali
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
