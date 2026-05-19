<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProdukRequest extends FormRequest
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
        $id = $this->input('id_produk');

        return [
            'nama_produk' => 'required|string|max:255|unique:produks,nama_produk' . ($id ? ',' . $id . ',id_produk' : ''),
            'harga'       => 'required|numeric|min:0',
            'deskripsi'   => 'nullable|string',
            'id_kategori' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'nama_produk.string'   => 'Nama produk harus berupa teks.',
            'nama_produk.max'      => 'Nama produk maksimal 255 karakter.',
            'nama_produk.unique'   => 'Nama produk sudah ada, silakan gunakan nama yang berbeda.',
            
            'harga.required'       => 'Harga wajib diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'harga.min'            => 'Harga tidak boleh kurang dari 0.',
            
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.exists'   => 'Kategori yang dipilih tidak valid.',
        ];
    }
}
