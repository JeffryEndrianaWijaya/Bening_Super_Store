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
        'stock_deducted',
    ];

    protected $casts = [
        'stock_deducted' => 'boolean',
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
     * Verify stock is sufficient before payment transition and mark as deducted.
     */
    public function decreaseStock()
    {
        // Re-fetch with lock to prevent race condition
        $self = static::where('id_pesanan', $this->id_pesanan)
            ->lockForUpdate()
            ->first();

        // If stock already deducted, skip silently (idempotent)
        if ($self && $self->stock_deducted) {
            return;
        }

        foreach ($this->details as $detail) {
            $produk = $detail->produk;
            if (!$produk) {
                throw new \Exception("Produk '{$detail->nama_produk}' tidak ditemukan.");
            }

            $currentStock = $produk->total_stok;

            if ($currentStock < $detail->qty) {
                throw new \Exception("Stok untuk produk '{$detail->nama_produk}' tidak mencukupi (Tersisa: {$currentStock}, Diminta: {$detail->qty}).");
            }
        }

        // Mark this order as stock-deducted
        static::where('id_pesanan', $this->id_pesanan)
            ->update(['stock_deducted' => true]);
    }
}
