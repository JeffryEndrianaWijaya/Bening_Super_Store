<x-dashboard-layout title="Stok Produk" activeMenu="stok">
    @push('css')
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    @endpush

    <div class="content">

        {{-- Alert notifications --}}
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 2000, showConfirmButton: false });
                });
            </script>
        @endif
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}' });
                });
            </script>
        @endif

        @include('pages.StokDashboard.AddStok')

        {{-- ======================= STOCK SUMMARY PER PRODUCT ======================= --}}
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-cubes mr-2 text-primary"></i>
                    Ringkasan Stok Tersedia per Produk
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th class="text-center">Stok Masuk (Total)</th>
                                <th class="text-center">Stok Terjual</th>
                                <th class="text-center">Sisa Stok</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produks as $p)
                                @php
                                    $stokMasuk  = $p->stoks->where('status', 'approved')->sum('jumlah_stok');
                                    $stokKeluar = \App\Models\PesananDetail::where('id_produk', $p->id_produk)
                                        ->whereHas('pesanan', function($q) {
                                            $q->where('stock_deducted', true);
                                        })->sum('qty');
                                    $sisaStok   = max(0, $stokMasuk - $stokKeluar);
                                @endphp
                                <tr>
                                    <td class="font-weight-bold">{{ $p->nama_produk }}</td>
                                    <td class="text-muted">{{ $p->kategori->nama_kategori ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="text-success font-weight-bold">+{{ number_format($stokMasuk, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-danger font-weight-bold">-{{ number_format($stokKeluar, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <strong style="font-size: 1.05rem;">{{ number_format($sisaStok, 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($sisaStok <= 0)
                                            <span class="badge badge-danger">Stok Habis</span>
                                        @elseif($sisaStok <= 5)
                                            <span class="badge badge-warning">Hampir Habis</span>
                                        @else
                                            <span class="badge badge-success">Tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ======================= LEDGER RIWAYAT STOK MASUK ======================= --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-2 text-success"></i>
                    Riwayat Stok Masuk (Ditambahkan oleh Admin)
                </h3>
                <div class="card-tools">
                    <span class="badge badge-info p-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Riwayat stok bersifat permanen — tidak dapat diedit atau dihapus
                    </span>
                </div>
            </div>

            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="7%">No</th>
                            <th>Produk</th>
                            <th class="text-center">Jumlah Stok Ditambahkan</th>
                            <th>Tanggal Dicatat</th>
                            <th width="20%" class="text-center">Status / Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stoks as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="font-weight-bold">{{ $item->produk ? $item->produk->nama_produk : '-' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-success px-3 py-2" style="font-size: 0.95rem;">
                                        +{{ number_format($item->jumlah_stok, 0, ',', '.') }} unit
                                    </span>
                                </td>
                                <td>{{ $item->created_at ? $item->created_at->translatedFormat('l, d F Y H:i') : '-' }}</td>
                                <td class="text-center">
                                    @if($item->status == 'pending')
                                        <form action="{{ route('stok.approve', $item->id_stok) }}" method="POST" class="d-inline mr-1">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success" title="Setujui (Approve) Stok Ini">
                                                <i class="fas fa-check mr-1"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('stok.cancel', $item->id_stok) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Batalkan Stok Ini">
                                                <i class="fas fa-times mr-1"></i> Cancel
                                            </button>
                                        </form>
                                    @elseif($item->status == 'approved')
                                        <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 0.9rem;">
                                            <i class="fas fa-check-circle mr-1"></i> Approved
                                        </span>
                                    @elseif($item->status == 'cancelled')
                                        <span class="badge badge-danger px-3 py-2 font-weight-bold" style="font-size: 0.9rem;">
                                            <i class="fas fa-times-circle mr-1"></i> Cancelled
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-folder-open mb-2 d-block" style="font-size:2rem; opacity:0.4;"></i>
                                    Belum ada riwayat stok masuk.
                                </td>
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
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

        <script>
            $(function () {
                // Confirm before delete
                $('.form-delete').on('submit', function (e) {
                    e.preventDefault();
                    var form = this;
                    Swal.fire({
                        title: 'Hapus Riwayat Stok?',
                        text: "Data stok masuk ini akan dihapus. Total stok produk akan berkurang!",
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
                    "order": [[ 3, "desc" ]]
                });

                // AJAX form for Add Stok modal
                $('.form-ajax').on('submit', function (e) {
                    e.preventDefault();
                    var form = $(this);
                    var url = form.attr('action');
                    var formData = new FormData(this);

                    form.find('.form-control').removeClass('is-invalid');
                    form.find('.select2-selection').removeClass('border-danger');
                    form.find('.invalid-feedback').html('');

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        success: function (response) {
                            if (response.success) {
                                $('.modal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function (field, messages) {
                                    var input = form.find('[name="' + field + '"]');
                                    input.addClass('is-invalid');
                                    if (input.hasClass('select2-produk')) {
                                        input.next('.select2-container').find('.select2-selection').addClass('border-danger');
                                    }
                                    form.find('.error-' + field).html(messages[0]).show();
                                });
                            } else {
                                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan sistem. Silakan coba lagi.' });
                            }
                        }
                    });
                });

                // Select2 init
                $('.select2-produk').select2({
                    theme: 'bootstrap4',
                    placeholder: "-- Pilih Produk --",
                    allowClear: true
                });

                $('.modal').on('hidden.bs.modal', function () {
                    $(this).find('.select2-produk').val('').trigger('change');
                    $(this).find('.form-control').removeClass('is-invalid');
                    $(this).find('.select2-selection').removeClass('border-danger');
                    $(this).find('.invalid-feedback').html('');
                });
            });
        </script>
    @endpush
</x-dashboard-layout>
