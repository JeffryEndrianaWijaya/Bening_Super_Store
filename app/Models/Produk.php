<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;

    protected $table = 'produks';
    protected $primaryKey = 'id_produk';
    
    protected $fillable = [
        'nama_produk',
        'harga',
        'deskripsi',
        'id_kategori'
    ];

    protected $dates = ['deleted_at'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function stoks()
    {
        return $this->hasMany(Stok::class, 'id_produk', 'id_produk');
    }

    public function getTotalStokAttribute()
    {
        return $this->stoks()->sum('jumlah_stok');
    }
}
