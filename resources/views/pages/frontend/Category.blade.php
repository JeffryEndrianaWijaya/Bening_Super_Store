<x-customer-layout title="{{ $kategoriAktif ? $kategoriAktif->nama_kategori : 'Semua Produk' }}">
    <div class="section">
        <h2 class="section-title">{{ $kategoriAktif ? $kategoriAktif->nama_kategori : 'Semua Produk' }}</h2>
        <p class="section-subtitle">{{ $kategoriAktif ? 'Menampilkan produk dalam kategori ini.' : 'Jelajahi seluruh produk kami.' }}</p>

        {{-- Category Pills --}}
        <div style="display:flex; gap:0.8rem; flex-wrap:wrap; margin-bottom:2rem;">
            <a href="{{ route('shop.category.index') }}" class="category-chip {{ !$kategoriAktif ? 'active' : '' }}">Semua</a>
            @foreach($kategoris as $kat)
                <a href="{{ route('shop.category.index', $kat->id_kategori) }}"
                    class="category-chip {{ $kategoriAktif && $kategoriAktif->id_kategori == $kat->id_kategori ? 'active' : '' }}">{{ $kat->nama_kategori }}</a>
            @endforeach
        </div>

        @if($produks->isEmpty())
            <div class="glass-card" style="padding:3rem; text-align:center;">
                <i class="fas fa-box-open" style="font-size:3rem; color:var(--text-secondary); margin-bottom:1rem;"></i>
                <p style="color:var(--text-secondary); font-size:1.1rem;">Belum ada produk di kategori ini.</p>
            </div>
        @else
            <div class="product-grid">
                @foreach($produks as $produk)
                    @php $diskon = $diskonAktif->get($produk->id_produk); @endphp
                    <x-product-card :produk="$produk" :diskon="$diskon" />
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        $('.form-add-cart').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr('action'), method: 'POST', data: form.serialize(),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(res) {
                    if (res.success) {
                        var badge = $('.cart-badge');
                        if (badge.length) { badge.text(res.cart_count); }
                        else { $('.btn-cart').append('<span class="cart-badge">' + res.cart_count + '</span>'); }
                        form.find('.btn-add-cart').text('✓ Ditambahkan!');
                        setTimeout(() => {
                            form.find('.btn-add-cart').html('<i class="fas fa-cart-plus" style="margin-right:0.3rem;"></i> Tambah ke Keranjang');
                        }, 1500);
                    }
                }
            });
        });
    </script>
    @endpush
</x-customer-layout>
