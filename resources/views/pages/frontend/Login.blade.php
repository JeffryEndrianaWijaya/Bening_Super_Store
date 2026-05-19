<x-customer-layout title="Login">
    <div style="display:flex; align-items:center; justify-content:center; min-height:calc(100vh - 80px); padding:2rem;">
        <div class="glass-card" style="width:100%; max-width:420px; padding:2.5rem;">
            <h2 style="text-align:center; font-size:1.8rem; font-weight:700; margin-bottom:0.4rem;">Selamat Datang 👋</h2>
            <p style="text-align:center; color:var(--text-secondary); margin-bottom:2rem; font-size:0.95rem;">Masuk ke akun Anda untuk berbelanja.</p>

            @if($errors->any())
                <div class="alert-custom alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group-custom">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="nama@email.com" value="{{ old('email') }}" required>
                </div>
                <div class="form-group-custom">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
                </div>
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
                    <label style="display:flex; align-items:center; gap:0.4rem; font-size:0.85rem; color:var(--text-secondary); cursor:pointer;">
                        <input type="checkbox" name="remember" style="accent-color:var(--primary);"> Ingat saya
                    </label>
                </div>
                <button type="submit" class="btn-primary-custom" style="width:100%; padding:0.75rem; font-size:1rem;">Masuk</button>
            </form>

            <p style="text-align:center; margin-top:1.5rem; font-size:0.9rem; color:var(--text-secondary);">
                Belum punya akun? <a href="{{ route('register') }}" style="color:var(--primary); text-decoration:none; font-weight:600;">Daftar Sekarang</a>
            </p>
        </div>
    </div>
</x-customer-layout>
