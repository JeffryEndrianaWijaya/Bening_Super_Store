<x-dashboard-layout title="Produk" activeMenu="produk">
    @push('css')
        <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    @endpush

    <div class="content">

        {{-- Alert Penanganan Error & Success --}}
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


        @include('pages.ProdukDashboard.AddProduk')

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Produk</h3>
            </div>

            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="7%">No</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Kategori</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produks as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->nama_produk }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>{{ $item->kategori ? $item->kategori->nama_kategori : '-' }}</td>
                                <td>
                                    <form action="{{ route('produk.destroy', $item->id_produk) }}" method="POST"
                                        class="d-inline form-delete">
                                        @csrf
                                        @method('DELETE')

                                        <a href="#" class="btn btn-sm btn-info mr-1" data-toggle="modal"
                                            data-target="#modalDetailProduk-{{ $item->id_produk }}">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <a href="#" class="btn btn-sm btn-warning mr-1" data-toggle="modal"
                                            data-target="#modalEditProduk-{{ $item->id_produk }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>

                                    @include('pages.ProdukDashboard.DetailProduk', ['item' => $item])
                                    @include('pages.ProdukDashboard.EditProduk', ['item' => $item, 'kategoris' => $kategoris])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data produk.</td>
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
                $('.form-delete').on('submit', function (e) {
                    e.preventDefault();
                    var form = this;
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data produk ini akan dihapus permanen!",
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

                // Inisialisasi Summernote
                $('.textarea').summernote({
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });

                // Handle AJAX form submission
                $('.form-ajax').on('submit', function (e) {
                    e.preventDefault();
                    var form = $(this);
                    var url = form.attr('action');
                    var method = form.attr('method');
                    var formData = new FormData(this);

                    // Reset error states
                    form.find('.form-control').removeClass('is-invalid');
                    form.find('.invalid-feedback').html('');

                    $.ajax({
                        url: url,
                        method: 'POST', // Always POST for FormData, Laravel uses _method for PUT
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
                                    // Find the input and add is-invalid class
                                    var input = form.find('[name="' + field + '"]');
                                    input.addClass('is-invalid');
                                    
                                    // If it's summernote, we might need to target its container
                                    if(input.hasClass('textarea')) {
                                        input.next('.note-editor').addClass('is-invalid'); // Optional styling
                                    }
                                    
                                    // Display error message
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