<x-customer-layout title="Pesanan Saya">
    <div class="section" style="max-width:900px;">
        <h2 class="section-title"><i class="fas fa-receipt" style="color:var(--primary);"></i> Pesanan Saya</h2>
        <p class="section-subtitle">Lacak status semua pesanan Anda di sini.</p>

        @if($pesanans->isEmpty())
            <div class="glass-card" style="padding:3rem; text-align:center;">
                <i class="fas fa-receipt" style="font-size:3rem; color:var(--text-secondary); margin-bottom:1rem;"></i>
                <p style="color:var(--text-secondary); font-size:1.1rem; margin-bottom:1rem;">Belum ada pesanan.</p>
                <a href="{{ route('shop.category.index') }}" class="btn-primary-custom">Mulai Belanja</a>
            </div>
        @else
            @foreach($pesanans as $pesanan)
                @php
                    $statusColor = match($pesanan->status) {
                        'paid' => '#4ECDC4',
                        'pending' => '#FFD93D',
                        'shipped' => '#007BFF',
                        'completed' => '#28A745',
                        'expired' => '#A7A9BE',
                        'cancelled' => '#FF6B6B',
                        default => '#A7A9BE',
                    };
                    $statusLabel = match($pesanan->status) {
                        'paid' => 'Diproses',
                        'pending' => 'Menunggu Pembayaran',
                        'shipped' => 'Dikirim',
                        'completed' => 'Diterima',
                        'expired' => 'Kadaluarsa',
                        'cancelled' => 'Dibatalkan',
                        default => ucfirst($pesanan->status),
                    };
                @endphp
                <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}" style="text-decoration:none; color:inherit;">
                    <div class="glass-card" style="padding:1.3rem; margin-bottom:1rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; cursor:pointer;">
                        <div>
                            <div style="font-weight:600; font-size:1rem;">{{ $pesanan->order_id }}</div>
                            <div style="font-size:0.85rem; color:var(--text-secondary); margin-top:0.2rem;">
                                {{ $pesanan->created_at->translatedFormat('d M Y, H:i') }} &bull; {{ $pesanan->details->count() }} item
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-weight:700; font-size:1.1rem; color:var(--primary);">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                            <span style="display:inline-block; margin-top:0.3rem; padding:0.25rem 0.8rem; border-radius:20px; font-size:0.75rem; font-weight:600; background:{{ $statusColor }}22; color:{{ $statusColor }}; border:1px solid {{ $statusColor }}44;">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        @endif
    </div>
</x-customer-layout>
