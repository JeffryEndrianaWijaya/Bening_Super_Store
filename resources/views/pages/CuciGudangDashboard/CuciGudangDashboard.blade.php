<x-dashboard-layout title="Manajemen Cuci Gudang" activeMenu="cuci_gudang">
    @push('css')
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    @endpush

    <div class="content">

        @include('pages.CuciGudangDashboard.AddCuciGudang')

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Program Cuci Gudang</h3>
            </div>

            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Produk</th>
                            <th>Diskon (%)</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Status</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cuci_gudangs as $index => $item)
                            @php
                                $now = \Carbon\Carbon::now();
                                $isUpcoming = $now->lt($item->waktu_mulai);
                                $isActive = $now->between($item->waktu_mulai, $item->waktu_selesai);
                                $isEnded = $now->gt($item->waktu_selesai);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->produk ? $item->produk->nama_produk : '-' }}</td>
                                <td><span class="badge badge-danger">{{ $item->persen_diskon }}%</span></td>
                                <td>{{ $item->waktu_mulai ? $item->waktu_mulai->translatedFormat('d M Y') : '-' }}</td>
                                <td>{{ $item->waktu_selesai ? $item->waktu_selesai->translatedFormat('d M Y') : '-' }}</td>
                                <td>
                                    @if($isUpcoming)
                                        <span class="badge badge-warning">Akan Datang</span>
                                    @elseif($isActive)
                                        <span class="badge badge-success">Aktif</span>
                                    @elseif($isEnded)
                                        <span class="badge badge-secondary">Berakhir</span>
                                    @endif
                                </td>
                                <td>
                                    @include('pages.CuciGudangDashboard.DetailCuciGudang', ['item' => $item])
                                    @include('pages.CuciGudangDashboard.EditCuciGudang', ['item' => $item, 'produks' => $produks])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada program cuci gudang.</td>
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
        <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
        <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

        <script>
            $(function () {
                $('.form-delete').on('submit', function (e) {
                    e.preventDefault();
                    var form = this;
                    Swal.fire({
                        title: 'Hapus Program?',
                        text: "Data cuci gudang ini akan dihapus permanen!",
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

                // Handle AJAX form submission
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

                                    if (input.hasClass('select2-produk')) {
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

                // Reset modal
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