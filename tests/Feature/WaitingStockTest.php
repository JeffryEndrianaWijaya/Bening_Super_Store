<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WaitingStockTest extends TestCase
{
    use DatabaseTransactions;

    public function test_order_transitions_to_waiting_stock_when_stock_is_insufficient()
    {
        // 1. Create admin and product with low stock
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Produk::factory()->create([
            'nama_produk' => 'Test Item',
            'harga' => 100000,
        ]);
        
        // Ensure stock record exists and set it to 1
        $product->stok()->updateOrCreate([], ['total_stok' => 1]);
        $product->refresh();

        // 2. Create a pending order for 2 items (exceeds stock of 1)
        $pesanan = Pesanan::create([
            'user_id' => $admin->id,
            'order_id' => 'ORD-TEST-' . time(),
            'total_harga' => 200000,
            'status' => 'pending',
        ]);

        PesananDetail::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'id_produk' => $product->id_produk,
            'nama_produk' => $product->nama_produk,
            'qty' => 2,
            'harga_satuan' => 100000,
            'diskon_persen' => 0,
            'subtotal' => 200000,
        ]);

        // 3. Act as admin and simulate payment
        $response = $this->actingAs($admin)
            ->from(route('pesanan.show', $pesanan->id_pesanan))
            ->get(route('pesanan.simulasi_bayar', $pesanan->id_pesanan));

        // 4. Assert that payment fails (redirects with error) and status is set to waiting_stock
        $response->assertRedirect();
        $response->assertSessionHas('error');
        
        $pesanan->refresh();
        $this->assertEquals('waiting_stock', $pesanan->status);
        $this->assertFalse($pesanan->stock_deducted);

        // 5. Replenish stock to 5
        $product->stok()->update(['total_stok' => 5]);
        $product->refresh();

        // 6. Act as admin and approve/potong stok (set status to paid)
        $response2 = $this->actingAs($admin)
            ->from(route('pesanan.show', $pesanan->id_pesanan))
            ->put(route('pesanan_admin.update', $pesanan->id_pesanan), [
                'status' => 'paid',
            ]);

        // 7. Assert that status is now paid, stock is deducted, and stock_deducted is true
        $response2->assertRedirect();
        $response2->assertSessionHas('success');

        $pesanan->refresh();
        $this->assertEquals('paid', $pesanan->status);
        $this->assertTrue($pesanan->stock_deducted);

        $product->refresh();
        $this->assertEquals(3, $product->total_stok); // 5 - 2 = 3
    }
}
