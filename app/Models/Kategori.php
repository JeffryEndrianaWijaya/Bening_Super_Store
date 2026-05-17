<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Import trait SoftDeletes

class Kategori extends Model
{
    use SoftDeletes; // 2. Gunakan trait di dalam class

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $fillable = ['nama_kategori'];

    // 3. Daftarkan kolom deleted_at sebagai tipe data date/datetime
    protected $dates = ['deleted_at']; 

    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_kategori', 'id_kategori');
    }
}