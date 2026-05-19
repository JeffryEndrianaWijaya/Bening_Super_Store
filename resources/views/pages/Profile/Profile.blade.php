<x-dashboard-layout title="Profil Pengguna" activeMenu="profile">
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
                                <form action="{{ route('profile.update') }}" method="POST" class="form-horizontal">
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
</x-dashboard-layout>
