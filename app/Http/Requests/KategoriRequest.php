<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class KategoriRequest extends FormRequest
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
        $kategori = $this->route('kategori');
        $kategoriId = $kategori ? $kategori->id_kategori : null;

        return [
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('kategori', 'nama_kategori')->ignore($kategoriId, 'id_kategori'),
            ],
        ];
    }
    
    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.string'   => 'Nama kategori harus berupa teks.',
            'nama_kategori.max'      => 'Nama kategori maksimal 255 karakter.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada di database.',
        ];
    }
}
