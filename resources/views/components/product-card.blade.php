{{-- Product Card Component with Bootstrap Carousel --}}
@props(['produk', 'diskon' => null])

@php
    $hargaFinal = $diskon ? $produk->harga * (1 - $diskon->persen_diskon / 100) : $produk->harga;
    $images = $produk->images ?? collect();
    $carouselId = 'carousel-' . $produk->id_produk;
@endphp

<div class="glass-card product-card">
    @if ($diskon)
        <div class="discount-badge">-{{ $diskon->persen_diskon }}%</div>
    @endif

    {{-- Bootstrap Carousel --}}
    @if ($images->count() > 0)
        <div id="{{ $carouselId }}" class="carousel slide" data-ride="carousel" data-interval="3000">
            @if ($images->count() > 1)
                <ol class="carousel-indicators" style="margin-bottom:0.3rem;">
                    @foreach ($images as $i => $img)
                        <li data-target="#{{ $carouselId }}" data-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"
                            style="width:8px; height:8px; border-radius:50%; background:var(--primary); border:none;">
                        </li>
                    @endforeach
                </ol>
            @endif
            <div class="carousel-inner" style="border-radius:16px 16px 0 0;">
                @foreach ($images as $i => $img)
                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                        <a href="{{ route('shop.product.detail', $produk->id_produk) }}" style="display: block;">
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $produk->nama_produk }}"
                                style="width:100%; height:200px; object-fit:cover; cursor: pointer;">
                        </a>
                    </div>
                @endforeach
            </div>
            @if ($images->count() > 1)
                <a class="carousel-control-prev" href="#{{ $carouselId }}" role="button" data-slide="prev"
                    style="width:25px; opacity:0; transition:opacity 0.3s;">
                    <span class="carousel-control-prev-icon"></span>
                </a>
                <a class="carousel-control-next" href="#{{ $carouselId }}" role="button" data-slide="next"
                    style="width:25px; opacity:0; transition:opacity 0.3s;">
                    <span class="carousel-control-next-icon"></span>
                </a>
            @endif
        </div>
    @else
        <a href="{{ route('shop.product.detail', $produk->id_produk) }}"
            style="text-decoration: none; color: inherit; display: block;">
            <div class="product-img" style="cursor: pointer;">
                <i class="fas fa-box-open"></i>
            </div>
        </a>
    @endif

    <a href="{{ route('shop.product.detail', $produk->id_produk) }}"
        style="text-decoration: none; color: inherit; display: block;">
        <div class="product-body" style="cursor: pointer; padding: 1.2rem;">
            <div class="product-category text-secondary"
                style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.3rem;">
                {{ $produk->kategori->nama_kategori ?? '-' }}
            </div>
            <div class="product-name"
                style="font-weight: 700; font-size: 1.15rem; margin-bottom: 0.4rem; transition: color 0.2s;"
                onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='inherit'">
                {{ $produk->nama_produk }}
            </div>
            <div class="product-price text-success" style="font-weight: 800; font-size: 1.1rem;">
                @if ($diskon)
                    <span class="price-original text-danger"
                        style="text-decoration: line-through; font-size: 0.9rem; margin-right: 0.5rem; font-weight: 600;">Rp
                        {{ number_format($produk->harga, 0, ',', '.') }}</span>
                @endif
                Rp {{ number_format($hargaFinal, 0, ',', '.') }}
            </div>
        </div>
    </a>
</div>