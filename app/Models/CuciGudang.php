<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuciGudang extends Model
{
    use SoftDeletes;

    protected $table = 'cuci_gudangs';
    protected $primaryKey = 'id_cuci_gudang';

    protected $fillable = [
        'id_produk',
        'persen_diskon',
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
