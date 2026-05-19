<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Ulasan extends Model
{
    protected $table = 'ulasans';
    protected $primaryKey = 'id_ulasan';
    protected $fillable = [
        'id_user',
        'id_produk',
        'rating',
        'komentar',
        'balasan'
    ];
 
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
 
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
