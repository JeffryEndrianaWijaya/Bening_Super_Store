<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CuciGudangRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_produk'     => 'required|exists:produks,id_produk',
            'persen_diskon' => 'required|integer|min:1|max:100',
            'waktu_mulai'   => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
        ];
    }

    public function messages(): array
    {
        return [
            'id_produk.required'     => 'Produk harus dipilih.',
            'id_produk.exists'       => 'Produk yang dipilih tidak valid.',
            'persen_diskon.required' => 'Persentase diskon wajib diisi.',
            'persen_diskon.integer'  => 'Persentase diskon harus berupa angka bulat.',
            'persen_diskon.min'      => 'Persentase diskon minimal 1%.',
            'persen_diskon.max'      => 'Persentase diskon maksimal 100%.',
            'waktu_mulai.required'   => 'Waktu mulai diskon wajib diisi.',
            'waktu_mulai.date'       => 'Format waktu mulai tidak valid.',
            'waktu_selesai.required' => 'Waktu selesai diskon wajib diisi.',
            'waktu_selesai.date'     => 'Format waktu selesai tidak valid.',
            'waktu_selesai.after_or_equal' => 'Waktu selesai tidak boleh lebih awal dari waktu mulai.',
        ];
    }
}
