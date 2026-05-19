<x-customer-layout title="Profil Saya">
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
            /* Fix styling in dark/light mode for intl-tel-input */
            .iti__country-list {
                background-color: var(--bg-glass) !important;
                border: 1px solid var(--border-glass) !important;
                color: var(--text-primary) !important;
            }
            .iti__country {
                padding: 8px 10px !important;
            }
            .iti__country:hover {
                background-color: rgba(255, 255, 255, 0.1) !important;
            }
            .profile-container {
                max-width: 800px;
                margin: 0 auto;
                padding: 4rem 1.5rem;
            }
            .profile-card {
                background: var(--bg-glass);
                border: 1px solid var(--border-glass);
                border-radius: 16px;
                padding: 2.5rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            }
            .profile-header {
                display: flex;
                align-items: center;
                gap: 1.5rem;
                margin-bottom: 2.5rem;
                padding-bottom: 1.5rem;
                border-bottom: 1px solid var(--border-glass);
            }
            .profile-avatar {
                width: 100px;
                height: 100px;
                border-radius: 50%;
                object-fit: cover;
                border: 3px solid var(--primary);
                padding: 3px;
            }
            .profile-title {
                font-size: 1.8rem;
                font-weight: 800;
                margin: 0;
                color: var(--text-primary);
            }
            .profile-role {
                font-size: 0.95rem;
                color: var(--text-secondary);
                text-transform: uppercase;
                letter-spacing: 1px;
                font-weight: 600;
                margin-top: 0.3rem;
            }
            .form-group {
                margin-bottom: 1.5rem;
            }
            .form-group label {
                display: block;
                font-size: 0.95rem;
                font-weight: 600;
                color: var(--text-primary);
                margin-bottom: 0.5rem;
            }
            .form-control {
                width: 100%;
                padding: 0.8rem 1rem;
                font-size: 1rem;
                border-radius: 8px;
                border: 1px solid var(--border-glass);
                background: rgba(255, 255, 255, 0.05);
                color: var(--text-primary);
                transition: all 0.3s;
            }
            .form-control:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
            }
            .section-divider {
                margin: 2.5rem 0 1.5rem;
                font-size: 1.2rem;
                font-weight: 700;
                color: var(--text-primary);
                border-bottom: 2px dashed var(--border-glass);
                padding-bottom: 0.8rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            .btn-save {
                background: var(--primary);
                color: white;
                border: none;
                padding: 0.9rem 2rem;
                font-size: 1.05rem;
                font-weight: 700;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.3s;
                width: 100%;
                margin-top: 1rem;
            }
            .btn-save:hover {
                background: var(--accent);
                transform: translateY(-2px);
            }
        </style>
    @endpush

    <div class="profile-container">
        @if (session('success'))
            <div style="background: rgba(40, 167, 69, 0.1); border-left: 4px solid #28a745; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; color: #28a745; font-weight: 600;">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="background: rgba(220, 53, 69, 0.1); border-left: 4px solid #dc3545; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; color: #dc3545;">
                <h6 style="font-weight: 700; margin-top: 0;"><i class="fas fa-exclamation-circle mr-2"></i> Terdapat Kesalahan:</h6>
                <ul style="margin-bottom: 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-card">
            <div class="profile-header">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D6EFD&color=fff&rounded=true&size=128" alt="Profile Avatar" class="profile-avatar">
                <div>
                    <h1 class="profile-title">{{ $user->name }}</h1>
                    <div class="profile-role"><i class="fas fa-user-circle mr-1"></i> {{ ucfirst($user->role) }}</div>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label for="phone_input">Nomor Telepon</label>
                    <input type="tel" id="phone_input" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="Masukkan nomor telepon">
                    <input type="hidden" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="section-divider">
                    <i class="fas fa-lock"></i> Keamanan Akun
                </div>
                
                <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1.5rem;">Biarkan kosong jika Anda tidak ingin mengubah password saat ini.</p>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password baru">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="btn-save"><i class="fas fa-save mr-2"></i> Simpan Perubahan Profil</button>
            </form>
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
</x-customer-layout>
