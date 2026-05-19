<x-customer-layout title="Checkout">
    @push('css')
    <style>
        .checkout-container { max-width:600px; margin:0 auto; padding:3rem 2rem; text-align:center; }
        .checkout-icon { font-size:4rem; color:var(--primary); margin-bottom:1.5rem; }
        .checkout-title { font-size:1.6rem; font-weight:700; margin-bottom:0.5rem; }
        .checkout-subtitle { color:var(--text-secondary); margin-bottom:2rem; }
        .checkout-total { font-size:2rem; font-weight:800; color:var(--primary); margin-bottom:2rem; }
    </style>
    @endpush

    <div class="checkout-container">
        <div class="glass-card" style="padding:3rem;">
            <div class="checkout-icon"><i class="fas fa-credit-card"></i></div>
            <div class="checkout-title">Pembayaran Pesanan</div>
            <div class="checkout-subtitle">Order ID: <strong>{{ $pesanan->order_id }}</strong></div>
            <div class="checkout-total">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>

            <button id="pay-button" class="btn-primary-custom" style="padding:0.9rem 3rem; font-size:1.1rem; width:100%; max-width:320px; margin:0 auto 0.8rem; display:block;">
                <i class="fas fa-lock" style="margin-right:0.4rem;"></i> Bayar Sekarang (Midtrans)
            </button>
            <form action="{{ route('pesanan.simulasi_bayar', $pesanan->id_pesanan) }}" method="POST" style="width:100%; max-width:320px; margin:0 auto; display:block;">
                @csrf
                <button type="submit" class="btn-primary-custom" style="padding:0.9rem 3rem; font-size:1.1rem; width:100%; background:linear-gradient(135deg, #4ECDC4, #3baea6); border:none; box-shadow: 0 4px 15px rgba(78,205,196,0.3);">
                    <i class="fas fa-magic" style="margin-right:0.4rem;"></i> Simulasikan Bayar Lunas (Testing)
                </button>
            </form>

            <p style="margin-top:1.5rem; color:var(--text-secondary); font-size:0.85rem;">
                Pembayaran diproses secara aman melalui Midtrans.
            </p>
        </div>
    </div>

    @push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        document.getElementById('pay-button').addEventListener('click', function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    fetch('{{ route("pesanan.update_status_after_pay") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: result.order_id,
                            status: 'paid'
                        })
                    }).finally(function() {
                        window.location.href = '{{ route("pesanan.index") }}';
                    });
                },
                onPending: function(result) {
                    fetch('{{ route("pesanan.update_status_after_pay") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: result.order_id,
                            status: 'paid' // in sandbox, we treat pending as success/paid for frictionless testing
                        })
                    }).finally(function() {
                        window.location.href = '{{ route("pesanan.index") }}';
                    });
                },
                onError: function(result) {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                    window.location.href = '{{ route("pesanan.index") }}';
                },
                onClose: function() {
                    window.location.href = '{{ route("pesanan.index") }}';
                }
            });
        });
    </script>
    @endpush
</x-customer-layout>
