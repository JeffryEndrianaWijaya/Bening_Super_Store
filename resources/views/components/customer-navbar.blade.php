{{-- Navbar Component --}}
<nav class="navbar-customer fixed-top">
    <a href="{{ route('home') }}" class="navbar-brand">
        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" width="50px" height="50px">
    </a>
    <div class="nav-links">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('shop.category.index') }}"
            class="{{ request()->routeIs('shop.category.*') ? 'active' : '' }}">Kategori</a>

        @auth
            <a href="{{ route('pesanan.index') }}" class="{{ request()->routeIs('pesanan.*') ? 'active' : '' }}">Pesanan
                Saya</a>
        @endauth
        @auth
            @if (auth()->user()->role == 'admin')
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}">Dashboard</a>
            @endif
        @endauth
    </div>
    <div class="nav-actions">
        {{-- Theme Toggle --}}
        <button class="btn-theme-toggle" id="themeToggle" title="Toggle Dark/Light Mode">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>

        @auth
            <a href="{{ route('profile') }}" class="btn-cart" title="Profil Saya" style="margin-right: 0.5rem;">
                <i class="fas fa-user-circle"></i>
            </a>
            <a href="{{ route('keranjang.index') }}" class="btn-cart">
                <i class="fas fa-shopping-cart"></i>
                @php $cartCount = \App\Models\Keranjang::where('user_id', auth()->id())->sum('qty'); @endphp
                @if ($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                @endif
            </a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <x-button color="dark" class="btn-outline-custom rounded" style="font-size:0.85rem; padding:0.4rem 1rem;"
                    type="submit">
                    Logout
                </x-button>
            </form>
        @else
        <x-button color="light" class="btn-outline-custom rounded" href="{{ route('login') }}">
            Login
        </x-button>
        <x-button color="primary" class="btn-primary-custom rounded" href="{{ route('register') }}">
            Daftar
        </x-button>
        @endauth
    </div>
</nav>