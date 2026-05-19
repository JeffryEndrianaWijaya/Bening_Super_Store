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
                        <li data-target="#{{ $carouselId }}" data-slide-to="{{ $i }}"
                            class="{{ $i === 0 ? 'active' : '' }}"
                            style="width:8px; height:8px; border-radius:50%; background:var(--primary); border:none;">
                        </li>
                    @endforeach
                </ol>
            @endif
            <div class="carousel-inner" style="border-radius:16px 16px 0 0;">
                @foreach ($images as $i => $img)
                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $produk->nama_produk }}"
                            style="width:100%; height:200px; object-fit:cover;">
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
        <div class="product-img">
            <i class="fas fa-box-open"></i>
        </div>
    @endif

    <div class="product-body">
        <div class="product-category text-secondary">{{ $produk->kategori->nama_kategori ?? '-' }}</div>
        <div class="product-name">{{ $produk->nama_produk }}</div>
        <div class="product-price text-success">
            @if ($diskon)
                <span class="price-original text-danger">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
            @endif
            Rp {{ number_format($hargaFinal, 0, ',', '.') }}
        </div>
        @auth
            @if ($produk->total_stok <= 0)
                <button type="button" class="btn-add-cart w-100 mt-2 rounded btn btn-secondary text-white font-weight-bold" style="background:#6c757d; border-color:#6c757d; cursor:not-allowed;" disabled>
                    <i class="fas fa-ban" style="margin-right:0.3rem;"></i> Stok Habis
                </button>
            @else
                <form action="{{ route('keranjang.store') }}" method="POST" class="form-add-cart">
                    @csrf
                    <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                    <x-button color="primary" class="btn-add-cart w-100 mt-2 rounded" type="submit">
                        <i class="fas fa-cart-plus" style="margin-right:0.3rem;"></i> Tambah ke Keranjang
                    </x-button>
                </form>
            @endif
        @else
            @if ($produk->total_stok <= 0)
                <button type="button" class="btn-add-cart w-100 mt-2 rounded btn btn-secondary text-white font-weight-bold" style="background:#6c757d; border-color:#6c757d; cursor:not-allowed;" disabled>
                    <i class="fas fa-ban" style="margin-right:0.3rem;"></i> Stok Habis
                </button>
            @else
                <x-button color="primary" class="btn-add-cart w-100 mt-2 rounded text-center d-block"
                    style="text-decoration:none;" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt" style="margin-right:0.3rem;"></i> Login untuk Beli
                </x-button>
            @endif
        @endauth
    </div>
</div>
