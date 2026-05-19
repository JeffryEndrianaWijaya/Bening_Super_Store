<x-dashboard-layout title="Produk" activeMenu="produk">
    @push('css')
        <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <style>
            /* Premium inline card-style design for file uploads and reordering */
            .upload-preview-container {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 10px;
                align-items: center;
            }
            .img-thumb-wrapper, .upload-dropzone-card {
                width: 100px;
                height: 145px;
                border-radius: 12px;
                overflow: hidden;
                position: relative;
                transition: all 0.2s ease;
                box-sizing: border-box;
            }
            .img-thumb-wrapper {
                border: 2px solid #e2e8f0;
                background: #fff;
                cursor: grab;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            }
            .img-thumb-wrapper:active {
                cursor: grabbing;
            }
            .img-thumb-wrapper.dragging {
                opacity: 0.5;
                transform: scale(0.95);
                border-color: #6366f1;
                box-shadow: 0 8px 25px rgba(99, 102, 241, 0.2);
            }
            .img-thumb-wrapper img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                pointer-events: none;
            }
            .upload-dropzone-card {
                border: 2px dashed #cbd5e0;
                background: #f8fafc;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                color: #a0aec0;
            }
            .upload-dropzone-card:hover, .upload-dropzone-card.dragover {
                border-color: #6366f1;
                background: #f0f4ff;
                color: #6366f1;
                box-shadow: 0 4px 12px rgba(99, 102, 241, 0.08);
            }
            .upload-dropzone-card .upload-icon {
                font-size: 24px;
                margin-bottom: 8px;
                transition: transform 0.2s;
            }
            .upload-dropzone-card:hover .upload-icon {
                transform: translateY(-2px);
            }
            .upload-dropzone-card span {
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 0.5px;
            }
            .img-thumb-wrapper .btn-delete-thumb {
                position: absolute;
                top: 6px;
                right: 6px;
                width: 22px;
                height: 22px;
                background: rgba(239, 68, 68, 0.95);
                border: none;
                border-radius: 50%;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 2px 4px rgba(0,0,0,0.15);
                transition: background 0.2s, transform 0.2s;
                z-index: 10;
                padding: 0;
            }
            .img-thumb-wrapper .btn-delete-thumb:hover {
                background: #dc2626;
                transform: scale(1.1);
            }
            .img-thumb-wrapper .btn-delete-thumb i {
                font-size: 10px;
                pointer-events: none;
            }
            .img-thumb-wrapper .thumb-order-badge {
                position: absolute;
                bottom: 6px;
                left: 6px;
                background: rgba(26, 32, 44, 0.75);
                color: white;
                font-size: 9px;
                padding: 2px 6px;
                border-radius: 4px;
                pointer-events: none;
                font-weight: 600;
                letter-spacing: 0.5px;
            }
        </style>
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
                            <th>Total Stok</th>
                            <th>Status</th>
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
                                <td class="text-center">
                                    @php $sisa = $item->total_stok; @endphp
                                    @if($sisa <= 0)
                                        <span class="badge badge-danger px-2 py-1" style="font-size:0.85rem;">
                                            <i class="fas fa-ban mr-1"></i>Habis
                                        </span>
                                    @elseif($sisa <= 5)
                                        <span class="badge badge-warning px-2 py-1 text-white" style="font-size:0.85rem;">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $sisa }} unit
                                        </span>
                                    @else
                                        <span class="badge badge-success px-2 py-1" style="font-size:0.85rem;">
                                            {{ number_format($sisa, 0, ',', '.') }} unit
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $item->status ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $item->status ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td> 
                                     <a href="#" class="btn btn-sm btn-info mr-1" data-toggle="modal"
                                            data-target="#modalDetailProduk-{{ $item->id_produk }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-warning mr-1" data-toggle="modal"
                                            data-target="#modalEditProduk-{{ $item->id_produk }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    

                                    @include('pages.ProdukDashboard.DetailProduk', ['item' => $item])
                                    @include('pages.ProdukDashboard.EditProduk', [
                                        'item' => $item,
                                        'kategoris' => $kategoris,
                                    ])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data produk.</td>
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
            var addFiles = [];
            var editFiles = {};

            $(function() {
                $('.form-delete').on('submit', function(e) {
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
                    dialogsInBody: true,
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
                $('.form-ajax').on('submit', function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var url = form.attr('action');
                    var method = form.attr('method');
                    var formData = new FormData(this);

                    // Reset error states
                    form.find('.form-control').removeClass('is-invalid');
                    form.find('.select2-selection').removeClass('border-danger'); // For select2
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
                        success: function(response) {
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
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(field, messages) {
                                    // Find the input and add is-invalid class
                                    var input = form.find('[name="' + field + '"]');
                                    input.addClass('is-invalid');

                                    // If it's summernote, we might need to target its container
                                    if (input.hasClass('textarea')) {
                                        input.next('.note-editor').addClass(
                                            'is-invalid'); // Optional styling
                                    }

                                    // If it's select2
                                    if (input.hasClass('select2-kategori')) {
                                        input.next('.select2-container').find(
                                            '.select2-selection').addClass(
                                            'border-danger');
                                    }

                                    // Display error message
                                    form.find('.error-' + field).html(messages[0])
                                        .show(); // Force show for select2
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
                $('.select2-kategori').each(function() {
                    $(this).select2({
                        theme: 'bootstrap4',
                        tags: true, // Memungkinkan input teks bebas
                        placeholder: "-- Pilih atau Ketik Kategori Baru --",
                        allowClear: true,
                        dropdownParent: $(this).closest('.modal')
                    });
                });

                // Reset Select2 and Drag-and-Drop state when modal is closed
                var previewBackups = {};
                
                // Backup original HTML of edit previews on modal open
                $('.modal').on('show.bs.modal', function () {
                    var editContainers = $(this).find('.edit-preview-container');
                    editContainers.each(function() {
                        var produkId = $(this).data('id');
                        if (!previewBackups[produkId]) {
                            previewBackups[produkId] = $(this).html();
                        }
                    });
                });

                $('.modal').on('hidden.bs.modal', function() {
                    var form = $(this).find('form');
                    if (form.length) {
                        form[0].reset();
                        // Reset summernote if initialized
                        form.find('.textarea').each(function() {
                            if ($(this).data('summernote')) {
                                $(this).summernote('code', '');
                            }
                        });
                    }

                    $(this).find('.select2-kategori').val('').trigger('change');
                    $(this).find('.form-control').removeClass('is-invalid');
                    $(this).find('.select2-selection').removeClass('border-danger');
                    $(this).find('.invalid-feedback').html('');
                    
                    // Reset Add modal state
                    addFiles = [];
                    // Keep the dropzone card, only remove preview wrappers
                    $('#preview-add').find('.img-thumb-wrapper').remove();
                    var fileInputAdd = document.getElementById('images-add');
                    if (fileInputAdd) fileInputAdd.value = '';
                    $('#image-order-add').val('');
                    
                    // Reset Edit modal states & restore backup DOM
                    var editContainers = $(this).find('.edit-preview-container');
                    editContainers.each(function() {
                        var produkId = $(this).data('id');
                        if (previewBackups[produkId]) {
                            $(this).html(previewBackups[produkId]);
                            updateOrderBadges($(this));
                            updateImageOrderEdit(produkId);
                        }
                        
                        editFiles[produkId] = [];
                        var fileInputEdit = document.getElementById('images-edit-' + produkId);
                        if (fileInputEdit) fileInputEdit.value = '';
                        
                        var editForm = $('#modalEditProduk-' + produkId).find('form');
                        editForm.find('.delete-images-input').val('');
                    });
                });

                // Sync files to native file input
                function syncFileInput(filesArray, fileInput) {
                    var dt = new DataTransfer();
                    filesArray.forEach(function(file) {
                        dt.items.add(file);
                    });
                    fileInput.files = dt.files;
                }

                // Unified file handling helper
                function handleFilesSelected(files, isAdd, produkId) {
                    var validFiles = [];
                    for (var i = 0; i < files.length; i++) {
                        if (files[i].type.startsWith('image/')) {
                            validFiles.push(files[i]);
                        }
                    }
                    
                    if (validFiles.length === 0) return;

                    if (isAdd) {
                        if (addFiles.length + validFiles.length > 4) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Maksimal 4 gambar!',
                                text: 'Anda hanya dapat mengunggah maksimal 4 gambar per produk.'
                            });
                            return;
                        }
                        addFiles = addFiles.concat(validFiles);
                        var fileInput = document.getElementById('images-add');
                        if (fileInput) syncFileInput(addFiles, fileInput);
                        renderAddPreviews();
                    } else {
                        var currentCount = $('#preview-edit-' + produkId + ' .img-thumb-wrapper').length;
                        if (currentCount + validFiles.length > 4) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Maksimal 4 gambar!',
                                text: 'Total gambar saat ini dan gambar baru tidak boleh melebihi 4.'
                            });
                            return;
                        }
                        
                        if (!editFiles[produkId]) {
                            editFiles[produkId] = [];
                        }
                        
                        editFiles[produkId] = editFiles[produkId].concat(validFiles);
                        
                        var fileInput = document.getElementById('images-edit-' + produkId);
                        if (fileInput) syncFileInput(editFiles[produkId], fileInput);
                        renderEditPreviews(produkId);
                    }
                }

                // Delegated click event to trigger file input
                $(document).on('click', '.upload-dropzone-card', function() {
                    $(this).closest('.form-group').find('input[type="file"]').click();
                });

                // Delegated file selection change event
                $(document).on('change', '#images-add, .edit-images-file-input', function() {
                    var files = this.files;
                    if (files.length === 0) return;
                    
                    var isAdd = this.id === 'images-add';
                    var produkId = $(this).data('id');
                    
                    handleFilesSelected(files, isAdd, produkId);
                });

                // Helper to check if a drag event contains actual files
                function isFileDrag(e) {
                    var types = e.originalEvent.dataTransfer.types;
                    if (types) {
                        for (var i = 0; i < types.length; i++) {
                            if (types[i] === "Files") {
                                return true;
                            }
                        }
                    }
                    return false;
                }

                // Delegated drag & drop events on preview containers
                $(document).on('dragover', '.upload-preview-container', function(e) {
                    if (isFileDrag(e)) {
                        e.preventDefault();
                        e.stopPropagation();
                        $(this).find('.upload-dropzone-card').addClass('dragover');
                    }
                });

                $(document).on('dragleave drop', '.upload-preview-container', function(e) {
                    if (isFileDrag(e)) {
                        e.preventDefault();
                        e.stopPropagation();
                        $(this).find('.upload-dropzone-card').removeClass('dragover');
                    }
                });

                $(document).on('drop', '.upload-preview-container', function(e) {
                    if (isFileDrag(e)) {
                        e.preventDefault();
                        e.stopPropagation();
                        var files = e.originalEvent.dataTransfer.files;
                        if (files.length === 0) return;
                        
                        var isAdd = this.id === 'preview-add';
                        var produkId = $(this).data('id'); // only for edit
                        
                        handleFilesSelected(files, isAdd, produkId);
                    }
                });

                // Update ordering badges ('Utama', 'Foto 2', etc.) to premium GREY colors
                function updateOrderBadges(container) {
                    var badges = container.find('.thumb-order-badge');
                    badges.each(function(index) {
                        if (index === 0) {
                            $(this).text('Utama').css('background', 'rgba(45, 55, 72, 0.95)');
                        } else {
                            $(this).text('Foto ' + (index + 1)).css('background', 'rgba(113, 128, 150, 0.75)');
                        }
                    });
                }

                // Re-render previews for Add Modal (maintains dropzone card at the end)
                function renderAddPreviews() {
                    var container = $('#preview-add');
                    container.find('.img-thumb-wrapper').remove();
                    
                    var dropzoneCard = $('#dropzone-add');
                    
                    if (addFiles.length === 0) {
                        updateOrderBadges(container);
                        return;
                    }
                    
                    addFiles.forEach(function(file, index) {
                        var objectUrl = URL.createObjectURL(file);
                        var html = `
                            <div class="img-thumb-wrapper" draggable="true" data-type="new" data-index="${index}">
                                <img src="${objectUrl}" alt="Preview">
                                <button type="button" class="btn-delete-thumb">
                                    <i class="fas fa-times"></i>
                                </button>
                                <span class="thumb-order-badge"></span>
                            </div>
                        `;
                        dropzoneCard.before(html);
                    });
                    updateOrderBadges(container);
                }



                // HTML5 Drag and Drop Sorting Setup
                function makeSortable(containerSelector, onSort) {
                    var container = $(containerSelector);
                    
                    container.on('dragstart', '.img-thumb-wrapper', function(e) {
                        $(this).addClass('dragging');
                    });

                    container.on('dragend', '.img-thumb-wrapper', function() {
                        $(this).removeClass('dragging');
                        updateOrderBadges(container);
                        onSort();
                    });

                    container.on('dragover', '.img-thumb-wrapper', function(e) {
                        e.preventDefault();
                        var draggingItem = container.find('.dragging');
                        if (draggingItem.length === 0 || draggingItem[0] === this) return;
                        
                        var box = this.getBoundingClientRect();
                        var center = box.left + box.width / 2;
                        if (e.clientX < center) {
                            $(this).before(draggingItem);
                        } else {
                            $(this).after(draggingItem);
                        }
                        
                        // Always ensure dropzone card is appended at the very end of the container
                        var dropzoneCard = container.find('.upload-dropzone-card');
                        container.append(dropzoneCard);

                        updateOrderBadges(container);
                    });
                }

                // Initialize Add Sortable
                makeSortable('#preview-add', function() {
                    var sortedFiles = [];
                    $('#preview-add .img-thumb-wrapper').each(function(newIdx) {
                        var oldIdx = $(this).data('index');
                        sortedFiles.push(addFiles[oldIdx]);
                        $(this).data('index', newIdx).attr('data-index', newIdx);
                    });
                    addFiles = sortedFiles;
                    
                    var fileInput = document.getElementById('images-add');
                    if (fileInput) syncFileInput(addFiles, fileInput);
                });

                // Render new previews in Edit Modal (maintains dropzone card at the end)
                function renderEditPreviews(produkId) {
                    var container = $('#preview-edit-' + produkId);
                    var files = editFiles[produkId] || [];
                    
                    container.find('.img-thumb-wrapper[data-type="new"]').remove();
                    
                    var dropzoneCard = $('#dropzone-edit-' + produkId);
                    
                    if (files.length === 0) {
                        updateOrderBadges(container);
                        updateImageOrderEdit(produkId);
                        return;
                    }
                    
                    files.forEach(function(file, index) {
                        var objectUrl = URL.createObjectURL(file);
                        var html = `
                            <div class="img-thumb-wrapper" draggable="true" data-type="new" data-index="${index}">
                                <img src="${objectUrl}" alt="Preview">
                                <button type="button" class="btn-delete-thumb">
                                    <i class="fas fa-times"></i>
                                </button>
                                <span class="thumb-order-badge"></span>
                            </div>
                        `;
                        dropzoneCard.before(html);
                    });
                    updateOrderBadges(container);
                    updateImageOrderEdit(produkId);
                }

                // Delegated click handler for delete buttons (Add and Edit modals)
                $(document).on('click', '.btn-delete-thumb', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    var wrapper = $(this).closest('.img-thumb-wrapper');
                    var type = wrapper.data('type');
                    
                    if (type === 'new') {
                        var container = wrapper.closest('.upload-preview-container');
                        var isAdd = container.attr('id') === 'preview-add';
                        var index = wrapper.data('index');
                        
                        if (isAdd) {
                            addFiles.splice(index, 1);
                            var fileInput = document.getElementById('images-add');
                            if (fileInput) syncFileInput(addFiles, fileInput);
                            renderAddPreviews();
                        } else {
                            var produkId = container.data('id');
                            var files = editFiles[produkId] || [];
                            files.splice(index, 1);
                            editFiles[produkId] = files;
                            
                            var fileInput = document.getElementById('images-edit-' + produkId);
                            if (fileInput) syncFileInput(files, fileInput);
                            renderEditPreviews(produkId);
                        }
                    } else if (type === 'existing') {
                        var imageId = wrapper.data('id');
                        var produkId = wrapper.closest('.edit-preview-container').data('id');
                        
                        var form = $('#modalEditProduk-' + produkId).find('form');
                        var input = form.find('.delete-images-input');
                        var current = input.val();
                        var ids = current ? current.split(',') : [];
                        ids.push(imageId);
                        input.val(ids.join(','));
                        
                        wrapper.fadeOut(300, function() {
                            var container = $('#preview-edit-' + produkId);
                            $(this).remove();
                            updateOrderBadges(container);
                            updateImageOrderEdit(produkId);
                        });
                    }
                });

                // Update the hidden input field image_order for Edit Modal
                function updateImageOrderEdit(produkId) {
                    var container = $('#preview-edit-' + produkId);
                    var order = [];
                    container.find('.img-thumb-wrapper').each(function() {
                        var type = $(this).data('type');
                        if (type === 'existing') {
                            order.push('existing-' + $(this).data('id'));
                        } else if (type === 'new') {
                            order.push('new-' + $(this).data('index'));
                        }
                    });
                    $('#image-order-edit-' + produkId).val(order.join(','));
                }

                // Initialize for each Edit Modal dynamically
                $('.edit-preview-container').each(function() {
                    var produkId = $(this).data('id');
                    var containerId = '#preview-edit-' + produkId;
                    
                    makeSortable(containerId, function() {
                        var container = $(containerId);
                        updateOrderBadges(container);
                        updateImageOrderEdit(produkId);
                    });
                    
                    updateOrderBadges($(containerId));
                    updateImageOrderEdit(produkId);
                });

                // Preview Image Pop Up (SweetAlert2)
                $(document).on('click', '.btn-preview-image', function(e) {
                    e.preventDefault();
                    var src = $(this).data('src');
                    Swal.fire({
                        imageUrl: src,
                        imageAlt: 'Foto Produk',
                        showConfirmButton: false,
                        showCloseButton: true,
                        background: '#fff',
                        width: 'auto',
                        imageWidth: 'auto',
                        imageHeight: 'auto',
                        customClass: {
                            image: 'img-fluid rounded shadow-sm'
                        }
                    });
                });
            });
        </script>
    @endpush
</x-dashboard-layout>
