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
        'id_kategori',
        'status'
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
        $stokMasuk = $this->stoks()->where('status', 'approved')->where('jumlah_stok', '>', 0)->sum('jumlah_stok');
        
        $terjual = \App\Models\PesananDetail::where('id_produk', $this->id_produk)
            ->whereHas('pesanan', function($q) {
                $q->where('stock_deducted', true);
            })->sum('qty');
            
        return max(0, $stokMasuk - $terjual);
    }

    public function cuci_gudangs()
    {
        return $this->hasMany(CuciGudang::class, 'id_produk', 'id_produk');
    }

    public function images()
    {
        return $this->hasMany(ProdukImage::class, 'id_produk', 'id_produk')->orderBy('sort_order');
    }

    public function ulasans()
    {
        return $this->hasMany(Ulasan::class, 'id_produk', 'id_produk');
    }
}
