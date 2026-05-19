<x-dashboard-layout title="Ulasan Pelanggan" activeMenu="ulasan_admin">
    @push('css')
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    @endpush
 
    <div class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
 
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5><i class="icon fas fa-ban"></i> Gagal!</h5>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
 
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kelola Ulasan Pelanggan</h3>
            </div>
 
            <div class="card-body">
                <table id="ulasanTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Pelanggan</th>
                            <th width="20%">Produk</th>
                            <th width="15%">Rating</th>
                            <th>Ulasan</th>
                            <th>Balasan Anda</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ulasans as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $item->user->name }}</strong><br>
                                    <small class="text-muted">{{ $item->user->email }}</small>
                                </td>
                                <td>
                                    <strong>{{ $item->produk->nama_produk }}</strong>
                                </td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $item->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    <span class="ml-1 text-bold">{{ $item->rating }}/5</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $item->created_at ? $item->created_at->translatedFormat('d M Y H:i') : '-' }}</span><br>
                                    {{ $item->komentar ?? '-' }}
                                </td>
                                <td>
                                    @if($item->balasan)
                                        <span class="badge badge-success mb-1">Sudah Dibalas</span><br>
                                        {{ $item->balasan }}
                                    @else
                                        <span class="badge badge-warning">Belum Dibalas</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary mr-1" data-toggle="modal"
                                        data-target="#modalBalasUlasan-{{ $item->id_ulasan }}" title="Balas Ulasan">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                    <form action="{{ route('ulasan_admin.destroy', $item->id_ulasan) }}" method="POST" class="d-inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus Ulasan">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
 
                                    <!-- Modal Balas Ulasan -->
                                    <x-dialog id="modalBalasUlasan-{{ $item->id_ulasan }}" size="md">
                                        <x-dialog.header title="Balas Ulasan Pelanggan" />
                                        <form action="{{ route('ulasan_admin.update', $item->id_ulasan) }}" method="POST" class="form-ajax">
                                            @csrf
                                            @method('PUT')
                                            <x-dialog.content>
                                                <div class="form-group">
                                                    <label>Pelanggan</label>
                                                    <input type="text" class="form-control" value="{{ $item->user->name }}" readonly disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label>Produk</label>
                                                    <input type="text" class="form-control" value="{{ $item->produk->nama_produk }}" readonly disabled>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Rating</label>
                                                    <div>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= $item->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Ulasan Pelanggan</label>
                                                    <textarea class="form-control bg-light" rows="3" readonly disabled>{{ $item->komentar }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="balasan-{{ $item->id_ulasan }}">Balasan Anda <span class="text-danger">*</span></label>
                                                    <textarea name="balasan" id="balasan-{{ $item->id_ulasan }}" class="form-control" rows="4" placeholder="Ketik balasan ulasan di sini..." required>{{ $item->balasan }}</textarea>
                                                    <div class="invalid-feedback error-balasan"></div>
                                                </div>
                                            </x-dialog.content>
                                            <x-dialog.footer>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Kirim Balasan</button>
                                            </x-dialog.footer>
                                        </form>
                                    </x-dialog>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada ulasan dari pelanggan.</td>
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
                // Delete confirmation using SweetAlert2
                $('.form-delete').on('submit', function (e) {
                    e.preventDefault();
                    var form = this;
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Ulasan pelanggan ini akan dihapus secara permanen!",
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
 
                // Initialize DataTable
                $("#ulasanTable").DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "order": [[ 0, "asc" ]]
                });
 
                // Handle AJAX form submission for replying
                $('.form-ajax').on('submit', function (e) {
                    e.preventDefault();
                    var form = $(this);
                    var url = form.attr('action');
                    var formData = new FormData(this);
 
                    // Reset error states
                    form.find('.form-control').removeClass('is-invalid');
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
                                // Close all modals
                                $('.modal').modal('hide');
 
                                // Show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    // Reload page to reflect changes
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
                                    form.find('.error-' + field).html(messages[0]);
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
            });
        </script>
    @endpush
</x-dashboard-layout>
