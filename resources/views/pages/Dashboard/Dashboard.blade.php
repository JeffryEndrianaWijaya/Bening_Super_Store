<x-dashboard-layout title="Dashboard" activeMenu="dashboard">
    <!-- Content Header (Page header) -->
    <div class="content-header pt-3">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-dark"><i class="fas fa-chart-line mr-2 text-primary"></i>Ringkasan Sistem</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info elevation-2" style="border-radius: 12px; overflow: hidden;">
                        <div class="inner p-3">
                            <h3 class="font-weight-bold" style="font-size: 1.8rem;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                            <p class="mb-0" style="font-size: 0.95rem; opacity: 0.9;">Total Pendapatan (Lunas)</p>
                        </div>
                        <div class="icon" style="opacity: 0.15; font-size: 4.5rem; top: 10px; right: 15px;">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <a href="{{ route('pesanan_admin.index') }}" class="small-box-footer py-2" style="background: rgba(0,0,0,0.1); font-size: 0.85rem;">
                            Kelola Pesanan <i class="fas fa-arrow-circle-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success elevation-2" style="border-radius: 12px; overflow: hidden;">
                        <div class="inner p-3">
                            <h3 class="font-weight-bold" style="font-size: 1.8rem;">{{ $totalPesanan }}</h3>
                            <p class="mb-0" style="font-size: 0.95rem; opacity: 0.9;">Total Pesanan</p>
                        </div>
                        <div class="icon" style="opacity: 0.15; font-size: 4.5rem; top: 10px; right: 15px;">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="{{ route('pesanan_admin.index') }}" class="small-box-footer py-2" style="background: rgba(0,0,0,0.1); font-size: 0.85rem;">
                            Kelola Pesanan <i class="fas fa-arrow-circle-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning elevation-2" style="border-radius: 12px; overflow: hidden;">
                        <div class="inner p-3 text-white">
                            <h3 class="font-weight-bold text-white" style="font-size: 1.8rem;">{{ $totalUser }}</h3>
                            <p class="mb-0 text-white" style="font-size: 0.95rem; opacity: 0.9;">Pelanggan Terdaftar</p>
                        </div>
                        <div class="icon" style="opacity: 0.2; font-size: 4.5rem; top: 10px; right: 15px;">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <a href="{{ route('user_admin.index') }}" class="small-box-footer py-2 text-white" style="background: rgba(0,0,0,0.15); font-size: 0.85rem;">
                            Kelola Pengguna <i class="fas fa-arrow-circle-right ml-1 text-white"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger elevation-2" style="border-radius: 12px; overflow: hidden;">
                        <div class="inner p-3">
                            <h3 class="font-weight-bold" style="font-size: 1.8rem;">{{ $totalProduk }}</h3>
                            <p class="mb-0" style="font-size: 0.95rem; opacity: 0.9;">Total Produk</p>
                        </div>
                        <div class="icon" style="opacity: 0.15; font-size: 4.5rem; top: 10px; right: 15px;">
                            <i class="fas fa-box"></i>
                        </div>
                        <a href="{{ route('produk.index') }}" class="small-box-footer py-2" style="background: rgba(0,0,0,0.1); font-size: 0.85rem;">
                            Kelola Produk <i class="fas fa-arrow-circle-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

            <!-- Main row -->
            <div class="row mt-3">
                <!-- Left col -->
                <div class="col-lg-8">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="card card-primary card-outline elevation-1" style="border-radius: 8px;">
                        <div class="card-header border-transparent py-3">
                            <h3 class="card-title font-weight-bold" style="font-size: 1.1rem;"><i class="fas fa-shopping-bag mr-2 text-primary"></i> Pesanan Terbaru</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0 table-hover table-striped">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="pl-4">Order ID</th>
                                            <th>Pelanggan</th>
                                            <th>Total Harga</th>
                                            <th>Status</th>
                                            <th>Waktu Transaksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $order)
                                            <tr>
                                                <td class="pl-4">
                                                    <a href="{{ route('pesanan_admin.index') }}" class="font-weight-bold text-primary">{{ $order->order_id }}</a>
                                                </td>
                                                <td>{{ $order->user->name ?? 'Tamu' }}</td>
                                                <td class="text-success font-weight-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                                <td>
                                                    @switch($order->status)
                                                        @case('paid')
                                                            <span class="badge badge-success px-2 py-1" style="font-size: 0.8rem; border-radius: 4px;">Lunas</span>
                                                            @break
                                                        @case('pending')
                                                            <span class="badge badge-warning px-2 py-1 text-white" style="font-size: 0.8rem; border-radius: 4px;">Menunggu</span>
                                                            @break
                                                        @case('cancelled')
                                                            <span class="badge badge-danger px-2 py-1" style="font-size: 0.8rem; border-radius: 4px;">Dibatalkan</span>
                                                            @break
                                                        @case('expired')
                                                            <span class="badge badge-secondary px-2 py-1" style="font-size: 0.8rem; border-radius: 4px;">Kedaluwarsa</span>
                                                            @break
                                                        @default
                                                            <span class="badge badge-info px-2 py-1" style="font-size: 0.8rem; border-radius: 4px;">{{ $order->status }}</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <span class="text-muted" style="font-size:0.85rem;">
                                                        <i class="far fa-clock mr-1"></i> {{ $order->created_at ? $order->created_at->diffForHumans() : '-' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted">
                                                    <i class="fas fa-folder-open mb-3 d-block" style="font-size:2.5rem; opacity: 0.5;"></i>
                                                    Belum ada pesanan terbaru.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-center bg-white border-top py-3">
                            <a href="{{ route('pesanan_admin.index') }}" class="btn btn-sm btn-primary py-1 px-4 font-weight-bold" style="border-radius: 20px;">Lihat Semua Pesanan</a>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->

                <div class="col-lg-4">
                    <!-- PRODUCT LIST -->
                    <div class="card card-warning card-outline elevation-1" style="border-radius: 8px;">
                        <div class="card-header py-3">
                            <h3 class="card-title text-warning font-weight-bold" style="font-size: 1.1rem;"><i class="fas fa-exclamation-triangle mr-2"></i> Peringatan Stok Menipis</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-3 pr-3">
                                @forelse($lowStockProducts as $prod)
                                    <li class="item py-3 d-flex align-items-center justify-content-between border-bottom">
                                        <div class="product-info" style="margin-left: 0; flex: 1;">
                                            <a href="{{ route('produk.index') }}" class="product-title font-weight-bold text-dark" style="font-size: 0.95rem;">
                                                {{ $prod->nama_produk }}
                                            </a>
                                            <span class="product-description text-muted d-block" style="font-size:0.8rem;">
                                                Kategori: {{ $prod->kategori->nama_kategori ?? '-' }}
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            @if($prod->total_stok <= 0)
                                                <span class="badge badge-danger px-2 py-1 font-weight-bold text-uppercase" style="font-size:0.75rem; border-radius: 4px;">Stok Habis</span>
                                            @else
                                                <span class="badge badge-warning px-2 py-1 font-weight-bold text-white" style="font-size:0.75rem; border-radius: 4px;">{{ $prod->total_stok }} Unit</span>
                                            @endif
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-center py-5 text-muted">
                                        <i class="fas fa-check-circle text-success mb-3 d-block" style="font-size:2.5rem; opacity: 0.7;"></i>
                                        Semua stok produk aman dan mencukupi!
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-center bg-white border-top py-3">
                            <a href="{{ route('stok.index') }}" class="btn btn-sm btn-warning py-1 px-4 text-white font-weight-bold" style="border-radius: 20px;">Kelola Stok Produk</a>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </section>
</x-dashboard-layout>
