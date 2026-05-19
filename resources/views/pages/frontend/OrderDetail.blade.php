<x-customer-layout title="Detail Pesanan">
    <div class="section" style="max-width:800px;">
        <a href="{{ route('pesanan.index') }}" style="color:var(--primary); text-decoration:none; font-size:0.9rem; font-weight:500;">
            <i class="fas fa-arrow-left" style="margin-right:0.3rem;"></i> Kembali ke Pesanan
        </a>

        <div class="glass-card" style="padding:2rem; margin-top:1.5rem;">
            {{-- Success & Error Alerts --}}
            @if(session('success'))
                <div style="background: rgba(78, 205, 196, 0.15); border: 1px solid #4ECDC4; color: #3baea6; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div style="background: rgba(255, 107, 107, 0.15); border: 1px solid #FF6B6B; color: #e05c5c; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

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

            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem; padding-bottom:1.5rem; border-bottom:1px solid var(--border-glass);">
                <div>
                    <div style="font-size:0.85rem; color:var(--text-secondary);">Order ID</div>
                    <div style="font-size:1.2rem; font-weight:700;">{{ $pesanan->order_id }}</div>
                    <div style="font-size:0.85rem; color:var(--text-secondary); margin-top:0.2rem;">{{ $pesanan->created_at->translatedFormat('l, d F Y H:i') }}</div>
                </div>
                <span style="padding:0.35rem 1rem; border-radius:20px; font-size:0.8rem; font-weight:600; background:{{ $statusColor }}22; color:{{ $statusColor }}; border:1px solid {{ $statusColor }}44;">
                    {{ $statusLabel }}
                </span>
            </div>

            {{-- Stepper Tracker --}}
            @if(!in_array($pesanan->status, ['expired', 'cancelled']))
                @php
                    $currentStep = 1;
                    if ($pesanan->status === 'paid') {
                        $currentStep = 2;
                    } elseif ($pesanan->status === 'shipped') {
                        $currentStep = 3;
                    } elseif ($pesanan->status === 'completed') {
                        $currentStep = 4;
                    }
                @endphp
                <div class="order-tracker-container" style="margin-bottom: 2.5rem; padding: 1.5rem 0; border-bottom: 1px solid var(--border-glass);">
                    <div style="display: flex; justify-content: space-between; position: relative; width: 100%; max-width: 600px; margin: 0 auto;">
                        {{-- Connecting Line Background --}}
                        <div style="position: absolute; top: 20px; left: 5%; right: 5%; height: 4px; background: #e0e0e0; border-radius: 2px; z-index: 1;"></div>
                        {{-- Connecting Line Active Progress --}}
                        <div style="position: absolute; top: 20px; left: 5%; width: {{ ($currentStep - 1) * 30 }}%; height: 4px; background: var(--primary); border-radius: 2px; z-index: 2; transition: width 0.4s ease;"></div>
 
                        {{-- Step 1: Payment --}}
                        <div style="text-align: center; width: 20%; z-index: 3; position: relative;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $currentStep >= 1 ? 'var(--primary)' : '#e0e0e0' }}; color: {{ $currentStep >= 1 ? '#fff' : '#a0a0a0' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; box-shadow: 0 0 10px {{ $currentStep >= 1 ? 'rgba(108,99,255,0.3)' : 'rgba(0,0,0,0.1)' }}; transition: background 0.3s;">
                                <i class="fas fa-wallet" style="font-size: 0.9rem;"></i>
                            </div>
                            <div style="font-size: 0.75rem; font-weight: 700; color: {{ $currentStep >= 1 ? 'var(--text-primary)' : 'var(--text-secondary)' }};">Payment</div>
                            <div style="font-size: 0.65rem; color: var(--text-secondary);">
                                @if($currentStep > 1) Lunas @else Belum Bayar @endif
                            </div>
                        </div>
 
                        {{-- Step 2: Diproses --}}
                        <div style="text-align: center; width: 20%; z-index: 3; position: relative;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $currentStep >= 2 ? 'var(--primary)' : '#e0e0e0' }}; color: {{ $currentStep >= 2 ? '#fff' : '#a0a0a0' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; box-shadow: 0 0 10px {{ $currentStep >= 2 ? 'rgba(108,99,255,0.3)' : 'rgba(0,0,0,0)' }}; transition: background 0.3s;">
                                <i class="fas fa-cog {{ $currentStep == 2 ? 'fa-spin' : '' }}" style="font-size: 0.9rem;"></i>
                            </div>
                            <div style="font-size: 0.75rem; font-weight: 700; color: {{ $currentStep >= 2 ? 'var(--text-primary)' : 'var(--text-secondary)' }};">Diproses</div>
                            <div style="font-size: 0.65rem; color: var(--text-secondary);">
                                @if($currentStep > 2) Selesai @elseif($currentStep == 2) Diproses @else Menunggu @endif
                            </div>
                        </div>
 
                        {{-- Step 3: Dikirim --}}
                        <div style="text-align: center; width: 20%; z-index: 3; position: relative;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $currentStep >= 3 ? 'var(--primary)' : '#e0e0e0' }}; color: {{ $currentStep >= 3 ? '#fff' : '#a0a0a0' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; box-shadow: 0 0 10px {{ $currentStep >= 3 ? 'rgba(108,99,255,0.3)' : 'rgba(0,0,0,0)' }}; transition: background 0.3s;">
                                <i class="fas fa-truck" style="font-size: 0.9rem;"></i>
                            </div>
                            <div style="font-size: 0.75rem; font-weight: 700; color: {{ $currentStep >= 3 ? 'var(--text-primary)' : 'var(--text-secondary)' }};">Dikirim</div>
                            <div style="font-size: 0.65rem; color: var(--text-secondary);">
                                @if($currentStep > 3) Tiba @elseif($currentStep == 3) Dikirim @else Menunggu @endif
                            </div>
                        </div>
 
                        {{-- Step 4: Diterima --}}
                        <div style="text-align: center; width: 20%; z-index: 3; position: relative;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $currentStep >= 4 ? 'var(--primary)' : '#e0e0e0' }}; color: {{ $currentStep >= 4 ? '#fff' : '#a0a0a0' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; box-shadow: 0 0 10px {{ $currentStep >= 4 ? 'rgba(108,99,255,0.3)' : 'rgba(0,0,0,0)' }}; transition: background 0.3s;">
                                <i class="fas fa-check-circle" style="font-size: 0.9rem;"></i>
                            </div>
                            <div style="font-size: 0.75rem; font-weight: 700; color: {{ $currentStep >= 4 ? 'var(--text-primary)' : 'var(--text-secondary)' }};">Diterima</div>
                            <div style="font-size: 0.65rem; color: var(--text-secondary);">
                                @if($currentStep == 4) Diterima @else Menunggu @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Items --}}
            @foreach($pesanan->details as $detail)
                <div style="display:flex; align-items:center; gap:1rem; padding:0.8rem 0; border-bottom:1px solid var(--border-glass);">
                    <div style="width:45px; height:45px; border-radius:10px; background:linear-gradient(135deg, rgba(108,99,255,0.15), rgba(78,205,196,0.15)); display:flex; align-items:center; justify-content:center; color:var(--primary); flex-shrink:0;">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600; font-size:0.95rem;">{{ $detail->nama_produk }}</div>
                        <div style="font-size:0.8rem; color:var(--text-secondary);">
                            {{ $detail->qty }}x @ Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                            @if($detail->diskon_persen > 0)
                                <span style="color:var(--secondary);">(-{{ $detail->diskon_persen }}%)</span>
                            @endif
                        </div>
                    </div>
                    <div style="font-weight:600; color:var(--text-primary);">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                </div>
            @endforeach

            {{-- Total --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-top:1.5rem; padding-top:1rem;">
                <div style="font-size:1rem; font-weight:600; color:var(--text-secondary);">Total Pembayaran</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--primary);">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
            </div>

            {{-- Pay button if pending --}}
            @if($pesanan->status === 'pending' && $pesanan->snap_token)
                <div style="text-align:center; margin-top:1.5rem; display:flex; flex-direction:column; align-items:center; gap:0.8rem;">
                    <button id="pay-button" class="btn-primary-custom" style="padding:0.8rem 2.5rem; font-size:1rem; width:100%; max-width:320px;">
                        <i class="fas fa-credit-card" style="margin-right:0.3rem;"></i> Bayar Sekarang (Midtrans)
                    </button>
                    <form action="{{ route('pesanan.simulasi_bayar', $pesanan->id_pesanan) }}" method="POST" style="width:100%; max-width:320px;">
                        @csrf
                        <button type="submit" class="btn-primary-custom" style="padding:0.8rem 2.5rem; font-size:1rem; width:100%; background:linear-gradient(135deg, #4ECDC4, #3baea6); border:none; box-shadow: 0 4px 15px rgba(78,205,196,0.3);">
                            <i class="fas fa-magic" style="margin-right:0.3rem;"></i> Simulasikan Bayar Lunas (Testing)
                        </button>
                    </form>
                </div>
            @endif

            {{-- Receipt confirmation button if shipped --}}
            @if($pesanan->status === 'shipped')
                <div style="text-align:center; margin-top:1.5rem; padding-top:1rem; border-top:1px solid var(--border-glass);">
                    <form action="{{ route('pesanan.konfirmasi', $pesanan->id_pesanan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin pesanan sudah diterima dengan baik?');">
                        @csrf
                        <button type="submit" class="btn-primary-custom" style="padding:0.8rem 2.5rem; font-size:1rem; background:linear-gradient(135deg, #28A745, #218838); border:none; box-shadow: 0 4px 15px rgba(40,167,69,0.3);">
                            <i class="fas fa-check-circle" style="margin-right:0.3rem;"></i> Konfirmasi Barang Diterima
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    @if($pesanan->status === 'pending' && $pesanan->snap_token)
        @push('scripts')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            document.getElementById('pay-button').addEventListener('click', function () {
                window.snap.pay('{{ $pesanan->snap_token }}', {
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
                            window.location.reload();
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
                                status: 'paid'
                            })
                        }).finally(function() {
                            window.location.reload();
                        });
                    },
                    onError: function(result) { 
                        alert('Pembayaran gagal.'); 
                        window.location.reload();
                    },
                    onClose: function() {}
                });
            });
        </script>
        @endpush
    @endif
</x-customer-layout>
