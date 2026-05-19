<x-dashboard-layout title="Profil Pengguna" activeMenu="profile">
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.4/build/css/intlTelInput.css">
        <style>
            .iti {
                display: block;
                width: 100%;
            }
            .iti__flag-container {
                z-index: 5;
            }
        </style>
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

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D6EFD&color=fff&rounded=true&size=128"
                                alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center mt-3">{{ $user->name }}</h3>
                        <p class="text-muted text-center">{{ ucfirst($user->role) }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>No. Telepon</b> <a class="float-right">{{ $user->phone ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b> <a class="float-right badge badge-success">Aktif</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Pengaturan Profil</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="settings">
                                <form action="{{ route('profile_admin.update') }}" method="POST" class="form-horizontal">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label">Nama Lengkap</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="phone_input" class="col-sm-3 col-form-label">No. Telepon</label>
                                        <div class="col-sm-9">
                                            <input type="tel" class="form-control" id="phone_input" value="{{ old('phone', $user->phone) }}" placeholder="Masukkan nomor telepon">
                                            <input type="hidden" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                        </div>
                                    </div>

                                    <hr>
                                    <h5 class="mb-3 mt-4 text-muted" style="font-size: 1rem;"><i class="fas fa-lock mr-2"></i>Ubah Password</h5>
                                    
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-3 col-form-label">Password Baru</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                            <small class="text-muted">Minimal 5 karakter</small>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label for="password_confirmation" class="col-sm-3 col-form-label">Konfirmasi Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <div class="offset-sm-3 col-sm-9 mt-3">
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.4/build/js/intlTelInput.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const phoneInput = document.querySelector("#phone_input");
                const phoneHidden = document.querySelector("#phone");
                
                const iti = window.intlTelInput(phoneInput, {
                    initialCountry: "id",
                    separateDialCode: true,
                    preferredCountries: ["id", "sg", "my"],
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.4/build/js/utils.js"
                });

                // Update hidden input on any change
                const updatePhoneValue = () => {
                    phoneHidden.value = iti.getNumber();
                };

                // Filter input: no leading zero, only digits, max 13 digits, format with hyphens (e.g., 812-3456-7890)
                phoneInput.addEventListener('input', function() {
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
                    updatePhoneValue();
                });

                phoneInput.addEventListener('change', updatePhoneValue);
                
                // Form submit validation/normalization check
                phoneInput.closest('form').addEventListener('submit', function() {
                    updatePhoneValue();
                });

                // Format initial value if present
                if (phoneInput.value) {
                    // Strip non-numeric and country code for raw format first
                    let rawVal = phoneInput.value;
                    if (rawVal.startsWith('+62')) {
                        rawVal = rawVal.replace('+62', '');
                    }
                    phoneInput.value = rawVal;
                    phoneInput.dispatchEvent(new Event('input'));
                }
            });
        </script>
    @endpush
</x-dashboard-layout>
