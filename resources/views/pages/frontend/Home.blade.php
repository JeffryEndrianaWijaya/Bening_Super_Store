<x-customer-layout title="Home">
    @push('css')
        <style>
            .page-container {
                padding-top: 0 !important;
            }

            .hero {
                position: relative;
                padding: 8rem 2rem 4rem;
                text-align: center;
                background: radial-gradient(ellipse at 50% 0%, rgba(13, 110, 253, 0.2) 0%, transparent 70%);
            }

            .hero h1 {
                font-size: 3rem;
                font-weight: 800;
                line-height: 1.15;
                margin-bottom: 1rem;
            }

            .hero h1 .gradient-text {
                background: linear-gradient(135deg, var(--primary), var(--accent));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .hero p {
                color: var(--text-secondary);
                font-size: 1.15rem;
                max-width: 550px;
                margin: 0 auto 2rem;
            }

            .hero-actions {
                display: flex;
                gap: 1rem;
                justify-content: center;
                flex-wrap: wrap;
            }

            .sale-banner {
                background: linear-gradient(135deg, rgba(255, 107, 107, 0.15), rgba(13, 110, 253, 0.15));
                border: 1px solid rgba(255, 107, 107, 0.25);
                border-radius: 16px;
                padding: 2rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .sale-banner h3 {
                font-size: 1.4rem;
                font-weight: 700;
            }

            .sale-banner h3 span {
                color: var(--secondary);
            }

            .sale-banner p {
                color: var(--text-secondary);
                font-size: 0.95rem;
                margin-top: 0.3rem;
            }

            /* Light mode banner contrast */
            [data-theme="light"] .sale-banner {
                background: linear-gradient(135deg, rgba(220, 53, 69, 0.12), rgba(13, 110, 253, 0.12));
                border: 1px solid rgba(220, 53, 69, 0.25);
            }

            [data-theme="light"] .sale-banner h3 {
                color: #0c0c1d !important;
            }

            [data-theme="light"] .sale-banner h3 span {
                color: #b02a37 !important;
            }

            [data-theme="light"] .sale-banner p {
                color: #3d3d4f !important;
            }
        </style>
    @endpush

    <div class="hero">
        <h1>Belanja Mudah,<br><span class="text-primary">Harga Terbaik.</span></h1>
        <p>Temukan produk berkualitas dengan harga terjangkau. Nikmati promo Cuci Gudang eksklusif setiap minggunya!</p>
        <div class="hero-actions">
            <x-button color="primary" class="rounded px-4 py-2" style="font-size:1rem;"
                href="{{ route('shop.category.index') }}">
                <i class="fas fa-shopping-bag" style="margin-right:0.4rem;"></i> Belanja Sekarang
            </x-button>
            @guest
                <x-button color="light" class="rounded px-4 py-2" style="font-size:1rem;" href="{{ route('register') }}">
                    <i class="fas fa-shopping-bag" style="margin-right:0.4rem;"></i> Belanja Sekarang
                </x-button>
            @endguest
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Jelajahi Kategori</h2>
        <p class="section-subtitle">Temukan produk berdasarkan kategori favoritmu.</p>

        <div class="marquee-wrapper">
            <div class="marquee-track">
                <a href="{{ route('shop.category.index') }}" class="category-chip active">Semua</a>
                @foreach ($kategoris as $kat)
                    <a href="{{ route('shop.category.index', $kat->id_kategori) }}"
                        class="category-chip">{{ $kat->nama_kategori }}</a>
                @endforeach

                <a href="{{ route('shop.category.index') }}" class="category-chip active">Semua</a>
                @foreach ($kategoris as $kat)
                    <a href="{{ route('shop.category.index', $kat->id_kategori) }}"
                        class="category-chip">{{ $kat->nama_kategori }}</a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- CUCI GUDANG / PROMO --}}
    @if ($cuciGudangAktif->count() > 0)
        <div class="section" style="padding-top:0;">
            <div class="sale-banner">
                <div>
                    <h3>🔥 Promo <span>Cuci Gudang</span> Sedang Berlangsung!</h3>
                    <p>Diskon hingga {{ $cuciGudangAktif->max('persen_diskon') }}% untuk produk pilihan. Jangan sampai
                        kehabisan!</p>
                </div>
                <x-button color="danger" class="btn-danger-custom rounded px-4 py-2"
                    href="{{ route('shop.category.index') }}">
                    Lihat Promo
                </x-button>
            </div>
        </div>
    @endif

    {{-- PRODUK TERBARU --}}
    <div class="section" style="padding-top:0;">
        <h2 class="section-title">Produk Terbaru</h2>
        <p class="section-subtitle">Koleksi terbaru yang bisa kamu tambahkan ke keranjang.</p>
        <div class="product-grid">
            @foreach ($produks as $produk)
                @php
                    $diskon = $cuciGudangAktif->where('id_produk', $produk->id_produk)->first();
                @endphp
                <x-product-card :produk="$produk" :diskon="$diskon" />
            @endforeach
        </div>
    </div>

    @push('scripts')
        <script>
            $('.form-add-cart').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (res) {
                        if (res.success) {
                            var badge = $('.cart-badge');
                            if (badge.length) {
                                badge.text(res.cart_count);
                            } else {
                                $('.btn-cart').append('<span class="cart-badge">' + res.cart_count +
                                    '</span>');
                            }
                            form.find('.btn-add-cart').text('✓ Ditambahkan!');
                            setTimeout(() => {
                                form.find('.btn-add-cart').html(
                                    '<i class="fas fa-cart-plus" style="margin-right:0.3rem;"></i> Tambah ke Keranjang'
                                );
                            }, 1500);
                        }
                    }
                });
            });
        </script>
    @endpush
</x-customer-layout>