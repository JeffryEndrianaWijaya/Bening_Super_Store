<x-customer-layout title="{{ $produk->nama_produk }}">
    @push('css')
        <style>
            .product-detail-container {
                max-width: 1100px;
                margin: 0 auto;
                padding: 2rem 1.5rem 4rem;
            }

            .back-link {
                color: var(--text-secondary);
                text-decoration: none;
                font-weight: 500;
                font-size: 0.9rem;
                transition: color 0.2s;
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                margin-bottom: 2rem;
            }

            .back-link:hover {
                color: var(--primary);
                text-decoration: none;
            }

            .detail-grid {
                display: grid;
                grid-template-columns: 1.1fr 1fr;
                gap: 3rem;
                align-items: start;
            }

            .image-gallery {
                position: sticky;
                top: 100px;
            }

            .main-image-container {
                border-radius: 16px;
                overflow: hidden;
                border: 1px solid var(--border-glass);
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0.05));
                margin-bottom: 1rem;
                position: relative;
            }

            .main-image {
                width: 100%;
                height: 450px;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .main-image-container:hover .main-image {
                transform: scale(1.03);
            }

            .gallery-thumbnails {
                display: flex;
                gap: 0.8rem;
                overflow-x: auto;
                padding-bottom: 0.5rem;
            }

            .gallery-thumbnail {
                width: 70px;
                height: 70px;
                border-radius: 8px;
                object-fit: cover;
                border: 2px solid transparent;
                cursor: pointer;
                transition: all 0.2s;
                background: var(--bg-glass);
            }

            .gallery-thumbnail.active {
                border-color: var(--primary);
            }

            .detail-info {
                display: flex;
                flex-direction: column;
            }

            .detail-category {
                font-size: 0.9rem;
                color: var(--accent);
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                margin-bottom: 0.5rem;
            }

            .detail-title {
                font-size: 2.2rem;
                font-weight: 800;
                line-height: 1.2;
                margin-bottom: 0.8rem;
            }

            .rating-summary {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 1.5rem;
            }

            .stars {
                color: #FFD93D;
                font-size: 1rem;
            }

            .rating-text {
                font-size: 0.9rem;
                color: var(--text-secondary);
                font-weight: 500;
            }

            .detail-price-box {
                margin-bottom: 1.8rem;
                display: flex;
                align-items: center;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .price-final {
                font-size: 2rem;
                font-weight: 800;
                color: #28a745;
            }

            .price-original {
                font-size: 1.2rem;
                color: var(--text-secondary);
                text-decoration: line-through;
                font-weight: 500;
            }

            .discount-tag {
                background: var(--secondary);
                color: #fff;
                font-size: 0.85rem;
                padding: 0.3rem 0.7rem;
                border-radius: 8px;
                font-weight: 700;
            }

            .stat-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                background: var(--bg-glass);
                border: 1px solid var(--border-glass);
                padding: 0.4rem 1rem;
                border-radius: 30px;
                font-size: 0.85rem;
                font-weight: 600;
                color: var(--text-primary);
                margin-bottom: 1.8rem;
                width: fit-content;
            }

            .detail-description {
                font-size: 1rem;
                line-height: 1.6;
                color: var(--text-secondary);
                margin-bottom: 2rem;
                border-top: 1px solid var(--border-glass);
                padding-top: 1.5rem;
            }

            .qty-selector {
                display: flex;
                align-items: center;
                gap: 0.8rem;
                margin-bottom: 2rem;
            }

            .qty-btn {
                background: var(--bg-glass);
                border: 1px solid var(--border-glass);
                color: var(--text-primary);
                width: 38px;
                height: 38px;
                border-radius: 8px;
                font-size: 1.1rem;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.2s;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .qty-btn:hover {
                background: var(--primary);
                border-color: var(--primary);
                color: #fff;
            }

            .qty-input {
                width: 60px;
                height: 38px;
                text-align: center;
                border-radius: 8px;
                border: 1px solid var(--border-glass);
                background: rgba(255, 255, 255, 0.05);
                color: var(--text-primary);
                font-family: inherit;
                font-weight: 700;
                font-size: 1.1rem;
            }

            .qty-input:focus {
                outline: none;
                border-color: var(--primary);
            }

            .stock-hint {
                font-size: 0.85rem;
                color: var(--text-secondary);
                font-weight: 500;
            }

            .btn-action-container {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .btn-order-large {
                flex: 1;
                min-width: 200px;
                padding: 0.9rem 2rem;
                font-size: 1.05rem;
                font-weight: 700;
                border-radius: 12px;
                border: none;
                cursor: pointer;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            /* Reviews Section */
            .reviews-container {
                margin-top: 5rem;
                border-top: 1px solid var(--border-glass);
                padding-top: 3rem;
            }

            .reviews-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 2.5rem;
            }

            .review-card {
                padding: 1.5rem;
                border-radius: 16px;
                background: var(--bg-glass);
                border: 1px solid var(--border-glass);
                margin-bottom: 1.5rem;
            }

            .review-meta {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 0.8rem;
            }

            .review-author {
                font-weight: 700;
                font-size: 0.95rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .review-author-avatar {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary), var(--accent));
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.8rem;
                font-weight: 800;
            }

            .review-date {
                font-size: 0.8rem;
                color: var(--text-secondary);
            }

            .review-comment {
                font-size: 0.95rem;
                line-height: 1.5;
                color: var(--text-primary);
                font-style: italic;
            }

            .review-reply {
                margin-top: 1rem;
                padding: 1rem 1.2rem;
                border-radius: 10px;
                background: rgba(108, 99, 255, 0.08);
                border-left: 3px solid var(--primary);
                font-size: 0.9rem;
            }

            .review-reply-header {
                font-weight: 700;
                color: var(--primary);
                margin-bottom: 0.3rem;
                font-size: 0.85rem;
            }

            @media (max-width: 768px) {
                .detail-grid {
                    grid-template-columns: 1fr;
                    gap: 2rem;
                }

                .main-image {
                    height: 300px;
                }
            }
        </style>
    @endpush

    @php
        $hargaFinal = $diskon ? $produk->harga * (1 - $diskon->persen_diskon / 100) : $produk->harga;
        $images = $produk->images ?? collect();
        $avgRating = $produk->ulasans->avg('rating') ?? 0;
        $totalUlasan = $produk->ulasans->count();
    @endphp

    <div class="product-detail-container">
        {{-- Breadcrumb back --}}
        <a href="{{ route('shop.category.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Galeri
        </a>

        {{-- Success & Error Alerts --}}
        @if(session('success'))
            <div class="alert-custom alert-success">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-custom alert-error">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Detail Grid --}}
        <div class="detail-grid">
            {{-- Column Left: Images --}}
            <div class="image-gallery">
                <div class="main-image-container">
                    @if($images->count() > 0)
                        <img src="{{ asset('storage/' . $images->first()->image_path) }}" id="mainProductImage"
                            class="main-image" alt="{{ $produk->nama_produk }}">
                    @else
                        <div class="main-image"
                            style="background: linear-gradient(135deg, rgba(13,110,253,0.1), rgba(78,205,196,0.1)); display:flex; align-items:center; justify-content:center; color:var(--primary); font-size:5rem;">
                            <i class="fas fa-box-open"></i>
                        </div>
                    @endif
                </div>

                @if($images->count() > 1)
                    <div class="gallery-thumbnails">
                        @foreach($images as $index => $img)
                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                class="gallery-thumbnail {{ $index === 0 ? 'active' : '' }}"
                                onclick="changeMainImage('{{ asset('storage/' . $img->image_path) }}', this)" alt="Thumbnail">
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Column Right: Detail Info --}}
            <div class="detail-info">
                <div class="detail-category">{{ $produk->kategori->nama_kategori ?? 'Umum' }}</div>
                <h1 class="detail-title text-white">{{ $produk->nama_produk }}</h1>

                {{-- Rating Summary --}}
                <div class="rating-summary">
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= round($avgRating) ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                    <span class="rating-text">
                        {{ number_format($avgRating, 1) }} ({{ $totalUlasan }} Ulasan)
                    </span>
                </div>

                {{-- Price --}}
                <div class="detail-price-box">
                    <span class="price-final">Rp {{ number_format($hargaFinal, 0, ',', '.') }}</span>
                    @if($diskon)
                        <span class="price-original">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                        <span class="discount-tag">Promo -{{ $diskon->persen_diskon }}%</span>
                    @endif
                </div>

                {{-- Stats Badges --}}
                <div style="display:flex; gap:0.8rem; flex-wrap:wrap; margin-bottom:1.5rem;">
                    <div class="stat-badge mb-0">
                        <i class="fas fa-shopping-bag text-primary"></i> {{ $jumlahTerjual }} Terjual
                    </div>
                    <div class="stat-badge mb-0">
                        @if($produk->total_stok > 0)
                            <i class="fas fa-cubes text-success"></i> Stok Tersedia
                        @else
                            <i class="fas fa-ban text-danger"></i> Stok Habis
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                <div class="detail-description">
                    <h5 class="text-white font-weight-bold mb-3" style="font-size: 1.1rem;"><i
                            class="fas fa-info-circle mr-2 text-primary"></i> Deskripsi Produk</h5>
                    <p style="white-space: pre-line;">
                        {!!  $produk->deskripsi ?? 'Tidak ada deskripsi untuk produk ini.' !!}</p>
                </div>

                {{-- Purchase Actions --}}
                @auth
                    @if($produk->total_stok <= 0)
                        <button type="button" class="btn-order-large btn-secondary text-white font-weight-bold"
                            style="background:#6c757d; border-color:#6c757d; cursor:not-allowed;" disabled>
                            <i class="fas fa-ban"></i> Stok Habis
                        </button>
                    @else
                        <form action="{{ route('keranjang.store') }}" method="POST" class="form-add-cart">
                            @csrf
                            <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">

                            {{-- Qty --}}
                            <div class="qty-selector">
                                <span class="stock-hint">Jumlah:</span>
                                <div style="display:flex; align-items:center;">
                                    <button type="button" class="qty-btn" onclick="adjustQty(-1)">-</button>
                                    <input type="number" id="purchaseQty" name="qty" value="1" min="1"
                                        max="{{ $produk->total_stok }}" class="qty-input">
                                    <button type="button" class="qty-btn" onclick="adjustQty(1)">+</button>
                                </div>
                                <span class="stock-hint">Sisa {{ $produk->total_stok }} unit</span>
                            </div>

                            <div class="btn-action-container">
                                <button type="submit" class="btn-order-large btn-primary-custom">
                                    <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                                </button>
                            </div>
                        </form>
                    @endif
                @else
                    @if($produk->total_stok <= 0)
                        <button type="button" class="btn-order-large btn-secondary text-white font-weight-bold"
                            style="background:#6c757d; border-color:#6c757d; cursor:not-allowed;" disabled>
                            <i class="fas fa-ban"></i> Stok Habis
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn-order-large btn-primary-custom text-center"
                            style="text-decoration:none;">
                            <i class="fas fa-sign-in-alt"></i> Login untuk Membeli
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Section: Reviews --}}
        <div class="reviews-container">
            <div class="reviews-header">
                <h3 class="text-white font-weight-bold m-0"><i class="fas fa-comments text-primary mr-2"></i>Ulasan
                    Pelanggan</h3>
                <span class="badge badge-primary px-3 py-2 font-weight-bold"
                    style="font-size:0.9rem; border-radius:30px;">{{ $totalUlasan }} Ulasan</span>
            </div>

            @forelse($produk->ulasans as $ulasan)
                <div class="review-card">
                    <div class="review-meta">
                        <div class="review-author text-white">
                            <div class="review-author-avatar">
                                {{ strtoupper(substr($ulasan->user->name ?? 'T', 0, 1)) }}
                            </div>
                            <div>
                                <div>{{ $ulasan->user->name ?? 'Tamu' }}</div>
                                <div style="color: #FFD93D; font-size:0.75rem; margin-top:0.15rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $ulasan->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="review-date">
                            {{ $ulasan->created_at ? $ulasan->created_at->diffForHumans() : '-' }}
                        </div>
                    </div>

                    <div class="review-comment">
                        "{{ $ulasan->komentar ?? 'Tidak ada komentar tertulis.' }}"
                    </div>

                    {{-- Admin reply --}}
                    @if($ulasan->balasan)
                        <div class="review-reply">
                            <div class="review-reply-header">
                                <i class="fas fa-reply mr-1"></i> Balasan Admin:
                            </div>
                            <div class="text-white">
                                "{{ $ulasan->balasan }}"
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="glass-card text-center py-5 text-muted" style="border-style:dashed;">
                    <i class="far fa-comments mb-3 d-block" style="font-size:3rem; opacity:0.3;"></i>
                    Belum ada ulasan untuk produk ini.
                </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
        <script>
            // Image switcher
            function changeMainImage(src, element) {
                document.getElementById('mainProductImage').src = src;

                // Reset thumbnails active border
                const thumbs = document.querySelectorAll('.gallery-thumbnail');
                thumbs.forEach(t => t.classList.remove('active'));

                // Add active border to clicked
                element.classList.add('active');
            }

            // Quantity adjust helper
            function adjustQty(amount) {
                const qtyInput = document.getElementById('purchaseQty');
                if (!qtyInput) return;

                let val = parseInt(qtyInput.value) || 1;
                val += amount;

                const min = parseInt(qtyInput.min) || 1;
                const max = parseInt(qtyInput.max) || 1;

                if (val >= min && val <= max) {
                    qtyInput.value = val;
                }
            }

            // AJAX add to cart
            $('.form-add-cart').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function (res) {
                        if (res.success) {
                            var badge = $('.cart-badge');
                            if (badge.length) {
                                badge.text(res.cart_count);
                            } else {
                                $('.btn-cart').append('<span class="cart-badge">' + res.cart_count + '</span>');
                            }

                            // Visual cue on button
                            var btn = form.find('.btn-order-large');
                            var origHtml = btn.html();
                            btn.html('<i class="fas fa-check"></i> Berhasil Ditambahkan!');
                            btn.css('background', 'linear-gradient(135deg, #28a745, #218838)');

                            setTimeout(() => {
                                btn.html(origHtml);
                                btn.css('background', '');
                            }, 2000);
                        }
                    },
                    error: function (xhr) {
                        var msg = 'Gagal menambahkan ke keranjang.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        alert(msg);
                    }
                });
            });
        </script>
    @endpush
</x-customer-layout>