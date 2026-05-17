<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StokRequest extends FormRequest
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
            'id_produk'   => 'required|exists:produks,id_produk',
            'jumlah_stok' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'id_produk.required'   => 'Produk harus dipilih.',
            'id_produk.exists'     => 'Produk yang dipilih tidak valid.',
            'jumlah_stok.required' => 'Jumlah stok wajib diisi.',
            'jumlah_stok.integer'  => 'Jumlah stok harus berupa angka bulat.',
            'jumlah_stok.min'      => 'Jumlah stok harus lebih dari 0.',
        ];
    }
}
