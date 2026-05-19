<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukImage extends Model
{
    protected $table = 'produk_images';
    protected $primaryKey = 'id_image';

    protected $fillable = [
        'id_produk',
        'image_path',
        'sort_order',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
