  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link">
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
                      alt="{{ $user ?? 'user' }}'s Initial" class="brand-image img-circle elevation-3"
                      style="opacity: .8; width: 33px; height: 33px;">
                  {{-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> --}}
              </div>
              <div class="info">
                  <a href="#" class="d-block">{{ $user ?? 'user' }}</a>
              </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                  data-accordion="false">

                  <li class="nav-item {{ $isActive('dashboard') }}">
                      <a href="/" class="nav-link">
                          <i class="nav-icon fas fa-th"></i>
                          <p>
                              Dashboard
                          </p>
                      </a>
                  </li>

                  <li class="nav-item">
            <a href="{{ route('kategori.index') }}" class="nav-link {{ $activeMenu == 'category' ? 'active' : '' }}">
                <i class="nav-icon fas fa-tags"></i>
                <p>
                    Kategori
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('produk.index') }}" class="nav-link {{ $activeMenu == 'produk' ? 'active' : '' }}">
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

                  <li class="nav-item">
                      <a href="pages/widgets.html" class="nav-link">
                          <i class="nav-icon fas fa-th"></i>
                          <p>
                              Widgets
                              <span class="right badge badge-danger">New</span>
                          </p>
                      </a>
                  </li>
              </ul>
          </nav>
          <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
  </aside>
