<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('assets/images/logo-white-bg.png') }}" alt="AdminLTE Logo" class="brand-image elevation-2"
            style="opacity: .8" width="30">
        <span class="brand-text font-weight-light">Bening Super Store</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user ?? 'user') }}&background=0D6EFD&color=fff&rounded=true&size=128"
                    alt="{{Auth::user()->name ?? 'user' }}'s Initial" class="brand-image img-circle elevation-3"
                    style="opacity: .8; width: 33px; height: 33px;">
                {{-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> --}}
            </div>
            <div class="info">
                <a href="{{ route('profile_admin') }}" class="d-block">{{ Auth::user()->name ?? 'user' }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                @if(in_array(auth()->user()->role, ['admin', 'gudang']))
                <li class="nav-item">
                    <a href="{{ route('kategori.index') }}"
                        class="nav-link {{ $activeMenu == 'category' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                            Kategori
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('produk.index') }}"
                        class="nav-link {{ $activeMenu == 'produk' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Produk
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('stok.index') }}" class="nav-link {{ $activeMenu == 'stok' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>
                            Stok Produk
                        </p>
                    </a>
                </li>
                @endif

                @if(in_array(auth()->user()->role, ['admin']))
                <li class="nav-item">
                    <a href="{{ route('cuci_gudang.index') }}"
                        class="nav-link {{ $activeMenu == 'cuci_gudang' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-percentage"></i>
                        <p>
                            Cuci Gudang
                        </p>
                    </a>
                </li>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'kasir']))
                <li class="nav-item">
                    <a href="{{ route('pesanan_admin.index') }}"
                        class="nav-link {{ $activeMenu == 'pesanan' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Pesanan
                            @php
                                $belumDilayani = \App\Models\Pesanan::whereIn('status', ['paid', 'waiting_stock'])->count();
                            @endphp
                            @if($belumDilayani > 0)
                                <span class="right badge badge-danger">{{ $belumDilayani }}</span>
                            @endif
                        </p>
                    </a>
                </li>
                @endif

                @if(in_array(auth()->user()->role, ['admin']))
                <li class="nav-item">
                    <a href="{{ route('user_admin.index') }}"
                        class="nav-link {{ $activeMenu == 'user_admin' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Manajemen User
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ulasan_admin.index') }}"
                        class="nav-link {{ $activeMenu == 'ulasan_admin' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>
                            Ulasan Pelanggan
                        </p>
                    </a>
                </li>
                @endif

                <!-- SYSTEM OUT -->
                <li class="nav-header text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px; padding: 15px 16px 5px;">Sistem</li>
                <li class="nav-item">
                    <a href="#" class="nav-link bg-light-danger" onclick="event.preventDefault(); 
                        Swal.fire({
                            title: 'Keluar dari Sistem?',
                            text: 'Anda harus memasukkan kembali kredensial Anda untuk masuk lagi!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, Keluar!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('logout-form').submit();
                            }
                        });">
                        <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                        <p class="text-danger font-weight-bold">
                            Logout
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>