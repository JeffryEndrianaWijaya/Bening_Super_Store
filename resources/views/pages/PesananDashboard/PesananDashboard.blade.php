<x-dashboard-layout title="Pesanan" activeMenu="pesanan">
    @push('css')
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    @endpush

    <div class="content">


        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Pesanan</h3>
            </div>

            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="7%">No</th>
                            <th>Order ID</th>
                            <th>Pelanggan</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanans as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->order_id }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $badgeClass = 'secondary';
                                        $statusText = ucfirst($item->status);
                                        $style = '';
                                        if ($item->status == 'paid') {
                                            $badgeClass = 'success';
                                        } elseif ($item->status == 'pending') {
                                            $badgeClass = 'warning';
                                        } elseif ($item->status == 'waiting_stock') {
                                            $badgeClass = 'warning';
                                            $style = 'background-color: #fd7e14; color: white;';
                                            $statusText = 'Waiting Stock / Approve';
                                        } elseif ($item->status == 'shipped') {
                                            $badgeClass = 'info';
                                        } elseif ($item->status == 'completed') {
                                            $badgeClass = 'primary';
                                        } elseif ($item->status == 'cancelled' || $item->status == 'expired') {
                                            $badgeClass = 'danger';
                                        }
                                    @endphp
                                    <span class="badge badge-{{ $badgeClass }}" style="{{ $style }}">{{ $statusText }}</span>
                                </td>
                                <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                            data-target="#modalDetailPesanan-{{ $item->id_pesanan }}"
                                            title="Detail & Status Pesanan">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                    </div>
                                    @include('pages.PesananDashboard.DetailPesanan', ['item' => $item])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

        <script>
            $(function () {
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: "{{ session('success') }}",
                        showConfirmButton: false,
                        timer: 2000
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: "{{ session('error') }}",
                        showConfirmButton: true
                    });
                @endif

                $('.form-delete').on('submit', function (e) {
                    e.preventDefault();
                    var form = this;
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data pesanan ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });

                $("#example1").DataTable({
                    "responsive": true,
                    "autoWidth": false,
                });
            });
        </script>
    @endpush
</x-dashboard-layout>