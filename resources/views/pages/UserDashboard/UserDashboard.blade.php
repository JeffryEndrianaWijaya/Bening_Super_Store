<x-dashboard-layout title="Manajemen User" activeMenu="user_admin">
    @push('css')
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.4/build/css/intlTelInput.css">
        <style>
            .iti { width: 100% !important; }
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

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5><i class="icon fas fa-ban"></i> Gagal!</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @include('pages.UserDashboard.AddUser')

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Pengguna</h3>
            </div>

            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="7%">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Terdaftar Pada</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $item->role == 'admin' ? 'badge-primary' : 'badge-secondary' }}">
                                        {{ ucfirst($item->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $item->status ? 'badge-success' : 'badge-danger' }}">
                                        {{ $item->status ? 'Aktif' : 'Disable' }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-warning mr-1" data-toggle="modal"
                                        data-target="#modalEditUser-{{ $item->id }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @include('pages.UserDashboard.EditUser', ['item' => $item])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data pengguna.</td>
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
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.4/build/js/intlTelInput.min.js"></script>

        <script>
            $(function () {
                $('.form-delete').on('submit', function (e) {
                    e.preventDefault();
                    var form = this;
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data pengguna ini akan dihapus permanen!",
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

            document.addEventListener("DOMContentLoaded", function() {
                // 1. Initialize for ADD USER modal
                const addInput = document.querySelector("#phone_add");
                const addHidden = document.querySelector("#phone_add_hidden");
                
                if (addInput && addHidden) {
                    const itiAdd = window.intlTelInput(addInput, {
                        initialCountry: "id",
                        separateDialCode: true,
                        preferredCountries: ["id", "sg", "my"],
                        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.4/build/js/utils.js"
                    });

                    const updateAddValue = () => {
                        addHidden.value = itiAdd.getNumber();
                    };

                    addInput.addEventListener('input', function() {
                        let value = this.value.replace(/\D/g, '');
                        while (value.startsWith('0')) {
                            value = value.substring(1);
                        }
                        if (value.length > 13) {
                            value = value.substring(0, 13);
                        }
                        
                        let formattedValue = '';
                        if (value.length > 0) {
                            if (value.length <= 3) {
                                formattedValue = value;
                            } else if (value.length <= 7) {
                                formattedValue = value.substring(0, 3) + '-' + value.substring(3);
                            } else {
                                formattedValue = value.substring(0, 3) + '-' + value.substring(3, 7) + '-' + value.substring(7);
                            }
                        }
                        this.value = formattedValue;
                        updateAddValue();
                    });

                    addInput.addEventListener('change', updateAddValue);
                    addInput.closest('form').addEventListener('submit', function() {
                        updateAddValue();
                    });

                    if (addHidden.value) {
                        let rawVal = addHidden.value;
                        if (rawVal.startsWith('+62')) {
                            rawVal = rawVal.replace('+62', '');
                        }
                        addInput.value = rawVal;
                        addInput.dispatchEvent(new Event('input'));
                    }
                }

                // 2. Initialize for EDIT USER modals
                const editInputs = document.querySelectorAll(".phone-edit-input");
                editInputs.forEach(function(editInput) {
                    const userId = editInput.getAttribute("data-user-id");
                    const editHidden = document.querySelector("#phone_edit_hidden_" + userId);
                    
                    if (editInput && editHidden) {
                        const itiEdit = window.intlTelInput(editInput, {
                            initialCountry: "id",
                            separateDialCode: true,
                            preferredCountries: ["id", "sg", "my"],
                            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.4/build/js/utils.js"
                        });

                        const updateEditValue = () => {
                            editHidden.value = itiEdit.getNumber();
                        };

                        editInput.addEventListener('input', function() {
                            let value = this.value.replace(/\D/g, '');
                            while (value.startsWith('0')) {
                                value = value.substring(1);
                            }
                            if (value.length > 13) {
                                value = value.substring(0, 13);
                            }
                            
                            let formattedValue = '';
                            if (value.length > 0) {
                                if (value.length <= 3) {
                                    formattedValue = value;
                                } else if (value.length <= 7) {
                                    formattedValue = value.substring(0, 3) + '-' + value.substring(3);
                                } else {
                                    formattedValue = value.substring(0, 3) + '-' + value.substring(3, 7) + '-' + value.substring(7);
                                }
                            }
                            this.value = formattedValue;
                            updateEditValue();
                        });

                        editInput.addEventListener('change', updateEditValue);
                        editInput.closest('form').addEventListener('submit', function() {
                            updateEditValue();
                        });

                        if (editHidden.value) {
                            let rawVal = editHidden.value;
                            if (rawVal.startsWith('+62')) {
                                rawVal = rawVal.replace('+62', '');
                            }
                            while (rawVal.startsWith('0')) {
                                rawVal = rawVal.substring(1);
                            }
                            rawVal = rawVal.replace(/\D/g, '');
                            editInput.value = rawVal;
                            editInput.dispatchEvent(new Event('input'));
                        }
                    }
                });

                // 3. AJAX Form Submission handler
                function handleAjaxForm(formSelector, modalId) {
                    $(formSelector).on('submit', function(e) {
                        e.preventDefault();
                        
                        const form = $(this);
                        const url = form.attr('action');
                        const method = form.attr('method') || 'POST';
                        const data = form.serialize();
                        const submitBtn = form.find('button[type="submit"]');
                        const originalBtnHtml = submitBtn.html();

                        // Clean previous errors
                        form.find('.is-invalid').removeClass('is-invalid');
                        form.find('.invalid-feedback').remove();

                        // Loading spinner
                        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

                        $.ajax({
                            url: url,
                            type: method,
                            data: data,
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: response.message,
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        $(modalId).modal('hide');
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: response.message || 'Terjadi kesalahan.',
                                        icon: 'error',
                                        confirmButtonColor: '#3085d6'
                                    });
                                    submitBtn.prop('disabled', false).html(originalBtnHtml);
                                }
                            },
                            error: function(xhr) {
                                submitBtn.prop('disabled', false).html(originalBtnHtml);
                                
                                if (xhr.status === 422) {
                                    const response = xhr.responseJSON;
                                    Swal.fire({
                                        title: 'Periksa Isian!',
                                        text: response.message || 'Beberapa isian tidak valid.',
                                        icon: 'warning',
                                        confirmButtonColor: '#3085d6'
                                    });

                                    if (response.errors) {
                                        Object.keys(response.errors).forEach(key => {
                                            const messages = response.errors[key];
                                            let inputEl = form.find(`[name="${key}"]`);
                                            
                                            if (inputEl.length) {
                                                inputEl.addClass('is-invalid');
                                                const errorHtml = `<div class="invalid-feedback d-block font-weight-bold" style="font-size: 0.85rem; margin-top: 5px;">${messages[0]}</div>`;
                                                
                                                // Handle phone input within intl-tel-input wrapper
                                                if (key === 'phone') {
                                                    const phoneAddInput = form.find('#phone_add');
                                                    const phoneEditInput = form.find('.phone-edit-input');
                                                    
                                                    if (phoneAddInput.length) {
                                                        phoneAddInput.addClass('is-invalid');
                                                        phoneAddInput.closest('.iti').after(errorHtml);
                                                    } else if (phoneEditInput.length) {
                                                        phoneEditInput.addClass('is-invalid');
                                                        phoneEditInput.closest('.iti').after(errorHtml);
                                                    } else {
                                                        inputEl.after(errorHtml);
                                                    }
                                                } else {
                                                    inputEl.after(errorHtml);
                                                }
                                            }
                                        });
                                    }
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.',
                                        icon: 'error',
                                        confirmButtonColor: '#d33'
                                    });
                                }
                            }
                        });
                    });
                }

                // Initialize AJAX forms
                handleAjaxForm('#formAddUser', '#modalAddUser');
                
                $('.form-edit-user').each(function() {
                    const userId = $(this).attr('data-user-id');
                    handleAjaxForm('#formEditUser-' + userId, '#modalEditUser-' + userId);
                });
            });
        </script>
    @endpush
</x-dashboard-layout>