<x-customer-layout title="Daftar Akun">
    <div style="display:flex; align-items:center; justify-content:center; min-height:calc(100vh - 80px); padding:2rem;">
        <div class="glass-card" style="width:100%; max-width:420px; padding:2.5rem;">
            <h2 style="text-align:center; font-size:1.8rem; font-weight:700; margin-bottom:0.4rem;">Buat Akun Baru 🚀</h2>
            <p style="text-align:center; color:var(--text-secondary); margin-bottom:2rem; font-size:0.95rem;">Daftar gratis dan mulai belanja.</p>

            @if($errors->any())
                <div class="alert-custom alert-error">
                    <ul style="list-style:none; margin:0; padding:0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group-custom">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-input" placeholder="Nama Anda" value="{{ old('name') }}" required>
                </div>
                <div class="form-group-custom">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-input" placeholder="nama@email.com" value="{{ old('email') }}" required>
                </div>
                <div class="form-group-custom">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-input" placeholder="Minimal 6 karakter" required>
                </div>
                <div class="form-group-custom">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="Ulangi password" required>
                </div>
                <button type="submit" class="btn-primary-custom" style="width:100%; padding:0.75rem; font-size:1rem; margin-top:0.5rem;">Daftar Sekarang</button>
            </form>

            <p style="text-align:center; margin-top:1.5rem; font-size:0.9rem; color:var(--text-secondary);">
                Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--primary); text-decoration:none; font-weight:600;">Masuk di sini</a>
            </p>
        </div>
    </div>
</x-customer-layout>
