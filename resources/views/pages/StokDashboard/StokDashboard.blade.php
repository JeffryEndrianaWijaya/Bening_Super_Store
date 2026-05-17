<x-dashboard-layout title="Stok Produk" activeMenu="stok">
    @push('css')
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    @endpush

    <div class="content">

        @include('pages.StokDashboard.AddStok')

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Stok Produk</h3>
            </div>

            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="7%">No</th>
                            <th>Produk</th>
                            <th>Jumlah Stok Masuk</th>
                            <th>Tanggal Masuk</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stoks as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->produk ? $item->produk->nama_produk : '-' }}</td>
                                <td>{{ number_format($item->jumlah_stok, 0, ',', '.') }}</td>
                                <td>{{ $item->created_at ? $item->created_at->translatedFormat('l, d F Y H:i') : '-' }}</td>
                                <td>
                                    <form action="{{ route('stok.destroy', $item->id_stok) }}" method="POST"
                                        class="d-inline form-delete">
                                        @csrf
                                        @method('DELETE')

                                        <a href="#" class="btn btn-sm btn-info mr-1" data-toggle="modal"
                                            data-target="#modalDetailStok-{{ $item->id_stok }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-warning mr-1" data-toggle="modal"
                                            data-target="#modalEditStok-{{ $item->id_stok }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    @include('pages.StokDashboard.DetailStok', ['item' => $item])
                                    @include('pages.StokDashboard.EditStok', ['item' => $item, 'produks' => $produks])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada riwayat stok.</td>
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
                $('.form-delete').on('submit', function (e) {
                    e.preventDefault();
                    var form = this;
                    Swal.fire({
                        title: 'Hapus Riwayat?',
                        text: "Data riwayat stok ini akan dihapus permanen!",
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
                    "order": [[ 3, "desc" ]] // Default urut berdasarkan tanggal terbaru
                });

                // Handle AJAX form submission
                $('.form-ajax').on('submit', function (e) {
                    e.preventDefault();
                    var form = $(this);
                    var url = form.attr('action');
                    var formData = new FormData(this);

                    // Reset error states
                    form.find('.form-control').removeClass('is-invalid');
                    form.find('.select2-selection').removeClass('border-danger');
                    form.find('.invalid-feedback').html('');

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
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
                                    
                                    if(input.hasClass('select2-produk')) {
                                        input.next('.select2-container').find('.select2-selection').addClass('border-danger');
                                    }
                                    
                                    form.find('.error-' + field).html(messages[0]).show();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan sistem. Silakan coba lagi.'
                                });
                            }
                        }
                    });
                });

                // Inisialisasi Select2
                $('.select2-produk').select2({
                    theme: 'bootstrap4',
                    placeholder: "-- Pilih Produk --",
                    allowClear: true
                });

                // Reset Select2 saat modal ditutup
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
