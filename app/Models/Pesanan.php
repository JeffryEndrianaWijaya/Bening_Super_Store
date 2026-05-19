<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanans';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'user_id',
        'order_id',
        'total_harga',
        'status',
        'snap_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(PesananDetail::class, 'id_pesanan', 'id_pesanan');
    }

    /**
     * Decrease product stock for all details in the order
     */
    public function decreaseStock()
    {
        foreach ($this->details as $detail) {
            $produk = $detail->produk;
            if (!$produk) {
                throw new \Exception("Produk '{$detail->nama_produk}' tidak ditemukan.");
            }

            $currentStock = $produk->total_stok;
            if ($currentStock < $detail->qty) {
                throw new \Exception("Stok untuk produk '{$detail->nama_produk}' tidak mencukupi (Tersisa: {$currentStock}, Diminta: {$detail->qty}).");
            }

            // Deduct stock by creating a new entry with a negative quantity
            \App\Models\Stok::create([
                'id_produk' => $detail->id_produk,
                'jumlah_stok' => -$detail->qty
            ]);
        }
    }
}
