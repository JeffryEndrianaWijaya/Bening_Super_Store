<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Toko Online' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #6C63FF;
            --primary-dark: #5A52D5;
            --secondary: #FF6B6B;
            --accent: #4ECDC4;
            --bg-dark: #0F0E17;
            --bg-card: rgba(255,255,255,0.06);
            --bg-glass: rgba(255,255,255,0.08);
            --text-primary: #FFFFFE;
            --text-secondary: #A7A9BE;
            --border-glass: rgba(255,255,255,0.12);
            --shadow-glow: 0 0 30px rgba(108,99,255,0.15);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* ===== NAVBAR ===== */
        .navbar-customer {
            position: fixed; top:0; left:0; right:0; z-index:1000;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.8rem 2rem;
            background: rgba(15,14,23,0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-glass);
        }
        .navbar-brand {
            font-size: 1.5rem; font-weight: 800; color: var(--primary);
            text-decoration: none; letter-spacing: -0.5px;
        }
        .navbar-brand span { color: var(--secondary); }
        .nav-links { display: flex; gap: 1.8rem; align-items: center; }
        .nav-links a {
            color: var(--text-secondary); text-decoration: none; font-size: 0.95rem;
            font-weight: 500; transition: color 0.3s; position: relative;
        }
        .nav-links a:hover, .nav-links a.active { color: var(--text-primary); }
        .nav-links a.active::after {
            content:''; position:absolute; bottom:-6px; left:0; right:0;
            height:2px; background:var(--primary); border-radius:2px;
        }
        .nav-actions { display: flex; gap: 1rem; align-items: center; }
        .btn-cart {
            position:relative; background:none; border:none; color:var(--text-secondary);
            font-size:1.2rem; cursor:pointer; transition:color 0.3s;
        }
        .btn-cart:hover { color:var(--primary); }
        .cart-badge {
            position:absolute; top:-6px; right:-8px;
            background:var(--secondary); color:#fff; font-size:0.65rem;
            width:18px; height:18px; border-radius:50%; display:flex;
            align-items:center; justify-content:center; font-weight:700;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color:#fff; border:none; padding:0.55rem 1.4rem; border-radius:10px;
            font-family:'Outfit',sans-serif; font-weight:600; font-size:0.9rem;
            cursor:pointer; transition:all 0.3s; text-decoration:none;
        }
        .btn-primary-custom:hover {
            transform:translateY(-2px);
            box-shadow:0 6px 20px rgba(108,99,255,0.4);
        }
        .btn-outline-custom {
            background:transparent; border:1.5px solid var(--border-glass);
            color:var(--text-primary); padding:0.55rem 1.4rem; border-radius:10px;
            font-family:'Outfit',sans-serif; font-weight:500; font-size:0.9rem;
            cursor:pointer; transition:all 0.3s; text-decoration:none;
        }
        .btn-outline-custom:hover {
            border-color:var(--primary); color:var(--primary);
        }
        .btn-danger-custom {
            background: linear-gradient(135deg, var(--secondary), #e55555);
            color:#fff; border:none; padding:0.55rem 1.4rem; border-radius:10px;
            font-family:'Outfit',sans-serif; font-weight:600; font-size:0.9rem;
            cursor:pointer; transition:all 0.3s; text-decoration:none;
        }
        .btn-danger-custom:hover {
            transform:translateY(-2px);
            box-shadow:0 6px 20px rgba(255,107,107,0.4);
        }

        /* ===== PAGE CONTAINER ===== */
        .page-container { padding-top: 80px; min-height: 100vh; }

        /* ===== GLASS CARD ===== */
        .glass-card {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-glow);
        }

        /* ===== PRODUCT CARD ===== */
        .product-card { overflow:hidden; position:relative; }
        .product-card .product-img {
            width:100%; height:200px; object-fit:cover;
            background:linear-gradient(135deg, rgba(108,99,255,0.15), rgba(78,205,196,0.15));
            display:flex; align-items:center; justify-content:center;
            font-size:3rem; color:var(--primary);
        }
        .product-card .product-body { padding:1.2rem; }
        .product-card .product-name {
            font-size:1rem; font-weight:600; margin-bottom:0.3rem;
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        }
        .product-card .product-category {
            font-size:0.8rem; color:var(--accent); margin-bottom:0.6rem;
        }
        .product-card .product-price {
            font-size:1.15rem; font-weight:700; color:var(--primary);
        }
        .product-card .product-price .price-original {
            font-size:0.85rem; color:var(--text-secondary);
            text-decoration:line-through; margin-right:0.5rem; font-weight:400;
        }
        .product-card .discount-badge {
            position:absolute; top:12px; right:12px;
            background:var(--secondary); color:#fff; font-size:0.75rem;
            padding:0.25rem 0.6rem; border-radius:8px; font-weight:700;
        }
        .product-card .btn-add-cart {
            display:block; width:100%; margin-top:0.8rem;
            background:linear-gradient(135deg, var(--primary), var(--primary-dark));
            color:#fff; border:none; padding:0.6rem; border-radius:10px;
            font-family:'Outfit',sans-serif; font-weight:600; font-size:0.85rem;
            cursor:pointer; transition:all 0.3s;
        }
        .product-card .btn-add-cart:hover {
            box-shadow:0 4px 15px rgba(108,99,255,0.4);
        }

        /* ===== GRID ===== */
        .product-grid {
            display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr));
            gap:1.5rem;
        }

        /* ===== SECTION ===== */
        .section { padding:3rem 2rem; max-width:1200px; margin:0 auto; }
        .section-title {
            font-size:1.8rem; font-weight:700; margin-bottom:0.4rem;
        }
        .section-subtitle {
            color:var(--text-secondary); font-size:1rem; margin-bottom:2rem;
        }

        /* ===== FOOTER ===== */
        .footer-customer {
            text-align:center; padding:2rem;
            border-top:1px solid var(--border-glass);
            color:var(--text-secondary); font-size:0.85rem;
        }

        /* ===== FORM INPUTS ===== */
        .form-group-custom { margin-bottom:1.2rem; }
        .form-group-custom label {
            display:block; margin-bottom:0.4rem;
            font-size:0.9rem; font-weight:500; color:var(--text-secondary);
        }
        .form-input {
            width:100%; padding:0.7rem 1rem; border-radius:10px;
            border:1.5px solid var(--border-glass); background:var(--bg-card);
            color:var(--text-primary); font-family:'Outfit',sans-serif; font-size:0.95rem;
            transition:border-color 0.3s;
        }
        .form-input:focus { outline:none; border-color:var(--primary); }
        .form-input.is-invalid { border-color:var(--secondary); }
        .invalid-feedback-custom { color:var(--secondary); font-size:0.8rem; margin-top:0.3rem; }

        /* ===== ALERT ===== */
        .alert-custom {
            padding:0.8rem 1.2rem; border-radius:10px; margin-bottom:1rem;
            font-size:0.9rem;
        }
        .alert-success { background:rgba(78,205,196,0.15); border:1px solid rgba(78,205,196,0.3); color:var(--accent); }
        .alert-error { background:rgba(255,107,107,0.15); border:1px solid rgba(255,107,107,0.3); color:var(--secondary); }

        /* ===== RESPONSIVE ===== */
        @media (max-width:768px) {
            .navbar-customer { padding:0.8rem 1rem; }
            .nav-links { display:none; }
            .section { padding:2rem 1rem; }
            .product-grid { grid-template-columns:repeat(auto-fill, minmax(160px,1fr)); gap:1rem; }
        }
    </style>
    @stack('css')
</head>
<body>
    {{-- NAVBAR --}}
    <nav class="navbar-customer">
        <a href="{{ route('home') }}" class="navbar-brand">Toko<span>Ku</span></a>
        <div class="nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('shop.category.index') }}" class="{{ request()->routeIs('shop.category.*') ? 'active' : '' }}">Kategori</a>
            @auth
                <a href="{{ route('pesanan.index') }}" class="{{ request()->routeIs('pesanan.*') ? 'active' : '' }}">Pesanan Saya</a>
            @endauth
        </div>
        <div class="nav-actions">
            @auth
                <a href="{{ route('keranjang.index') }}" class="btn-cart">
                    <i class="fas fa-shopping-cart"></i>
                    @php $cartCount = \App\Models\Keranjang::where('user_id', auth()->id())->sum('qty'); @endphp
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-outline-custom" style="font-size:0.85rem; padding:0.4rem 1rem;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-outline-custom">Login</a>
                <a href="{{ route('register') }}" class="btn-primary-custom">Daftar</a>
            @endguest
        </div>
    </nav>

    <div class="page-container">
        {{ $slot }}
    </div>

    <footer class="footer-customer">
        &copy; {{ date('Y') }} TokoKu. All rights reserved.
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>
