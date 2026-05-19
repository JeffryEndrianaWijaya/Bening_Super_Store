<x-customer-layout title="Keranjang Belanja">
    <div class="section" style="max-width:900px;">
        <h2 class="section-title"><i class="fas fa-shopping-cart" style="color:var(--primary);"></i> Keranjang Belanja</h2>
        <p class="section-subtitle">Review item Anda sebelum checkout.</p>

        @if(session('success'))
            <div class="alert-custom alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-custom alert-error">{{ session('error') }}</div>
        @endif

        @if($items->isEmpty())
            <div class="glass-card" style="padding:3rem; text-align:center;">
                <i class="fas fa-shopping-cart" style="font-size:3rem; color:var(--text-secondary); margin-bottom:1rem;"></i>
                <p style="color:var(--text-secondary); font-size:1.1rem; margin-bottom:1rem;">Keranjang Anda masih kosong.</p>
                <a href="{{ route('shop.category.index') }}" class="btn-primary-custom">Mulai Belanja</a>
            </div>
        @else
            @php 
                $grandTotal = 0; 
                $hasStockIssue = false;
            @endphp

            @foreach($items as $item)
                @php
                    $diskon = $diskonAktif->get($item->id_produk);
                    $hargaAsli = $item->produk->harga;
                    $hargaFinal = $diskon ? $hargaAsli * (1 - $diskon->persen_diskon / 100) : $hargaAsli;
                    $subtotal = $hargaFinal * $item->qty;
                    $grandTotal += $subtotal;
                    
                    $isOut = $item->produk->total_stok < $item->qty;
                    if ($isOut) {
                        $hasStockIssue = true;
                    }
                @endphp
                <div class="glass-card" style="padding:1.2rem; margin-bottom:1rem; display:flex; align-items:center; gap:1.2rem; flex-wrap:wrap; {!! $isOut ? 'border: 1px solid rgba(220, 53, 69, 0.4);' : '' !!}">
                    <div style="width:60px; height:60px; border-radius:12px; background:linear-gradient(135deg, rgba(108,99,255,0.15), rgba(78,205,196,0.15)); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:var(--primary); flex-shrink:0;">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div style="flex:1; min-width:150px;">
                        <div style="font-weight:600; font-size:1rem;">{{ $item->produk->nama_produk }}</div>
                        <div style="font-size:0.85rem; color:var(--text-secondary);">{{ $item->produk->kategori->nama_kategori ?? '-' }}</div>
                        <div style="margin-top:0.3rem;">
                            @if($diskon)
                                <span style="text-decoration:line-through; color:var(--text-secondary); font-size:0.85rem; margin-right:0.4rem;">Rp {{ number_format($hargaAsli, 0, ',', '.') }}</span>
                                <span style="color:var(--secondary); font-size:0.75rem; font-weight:600;">-{{ $diskon->persen_diskon }}%</span>
                            @endif
                            <div style="color:var(--primary); font-weight:700;">Rp {{ number_format($hargaFinal, 0, ',', '.') }}</div>
                        </div>
                        @if($isOut)
                            <div style="margin-top:0.4rem;">
                                <span style="background:#dc3545; color:white; font-size:0.75rem; font-weight:700; padding:0.25rem 0.5rem; border-radius:4px; display:inline-block;">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Stok Tidak Mencukupi (Tersisa: {{ $item->produk->total_stok }})
                                </span>
                            </div>
                        @endif
                    </div>
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <form action="{{ route('keranjang.update', $item->id_keranjang) }}" method="POST" style="display:flex; align-items:center; gap:0.4rem;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="qty" value="{{ max(1, $item->qty - 1) }}" class="btn-outline-custom" style="padding:0.3rem 0.6rem; font-size:0.85rem;">-</button>
                            <span style="font-weight:600; min-width:30px; text-align:center;">{{ $item->qty }}</span>
                            <button type="submit" name="qty" value="{{ $item->qty + 1 }}" class="btn-outline-custom" style="padding:0.3rem 0.6rem; font-size:0.85rem;" {{ $item->produk->total_stok <= $item->qty ? 'disabled' : '' }}>+</button>
                        </form>
                    </div>
                    <div style="text-align:right; min-width:120px;">
                        <div style="font-weight:700; font-size:1.05rem;">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                        <form action="{{ route('keranjang.destroy', $item->id_keranjang) }}" method="POST" style="margin-top:0.3rem;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; color:var(--secondary); font-size:0.8rem; cursor:pointer; font-family:'Outfit',sans-serif;">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            {{-- TOTAL & CHECKOUT --}}
            <div class="glass-card" style="padding:1.5rem; margin-top:1.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
                <div>
                    <div style="color:var(--text-secondary); font-size:0.9rem;">Total Pembayaran</div>
                    <div style="font-size:1.6rem; font-weight:800; color:var(--primary);">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
                </div>
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary-custom" {{ $hasStockIssue ? 'disabled' : '' }} style="padding:0.8rem 2.5rem; font-size:1.05rem; @if($hasStockIssue) background:#6c757d; border-color:#6c757d; cursor:not-allowed; opacity:0.65; @endif">
                        <i class="fas fa-lock" style="margin-right:0.4rem;"></i> {{ $hasStockIssue ? 'Stok Tidak Mencukupi' : 'Checkout Sekarang' }}
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-customer-layout>
