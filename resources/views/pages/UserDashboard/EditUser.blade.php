<!-- Modal Edit User -->
<div class="modal fade" id="modalEditUser-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User - {{ $item->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditUser-{{ $item->id }}" class="form-edit-user" data-user-id="{{ $item->id }}" action="{{ route('user_admin.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $item->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label>No. Telepon</label>
                        <div class="d-block w-100">
                            <!-- We can use a unique class or specific id per user since there is a loop or modal per user, wait! -->
                            <!-- Is EditUser modal inside a loop? Let's check UserDashboard.blade.php. -->
                            <!-- Usually it's in a loop like foreach($items as $item) include('EditUser'). -->
                            <!-- If so, we need unique IDs using $item->id. Yes! ID like id="phone_edit_{{ $item->id }}" is perfect! -->
                            <input type="tel" id="phone_edit_{{ $item->id }}" data-user-id="{{ $item->id }}" class="form-control phone-edit-input w-100" placeholder="821-xxxx-xxxx">
                            <input type="hidden" name="phone" id="phone_edit_hidden_{{ $item->id }}" value="{{ old('phone', $item->phone) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="pelanggan" {{ (old('role', $item->role) == 'pelanggan') ? 'selected' : '' }}>Pelanggan</option>
                            <option value="kasir" {{ (old('role', $item->role) == 'kasir') ? 'selected' : '' }}>Kasir</option>
                            <option value="gudang" {{ (old('role', $item->role) == 'gudang') ? 'selected' : '' }}>Gudang</option>
                            <option value="admin" {{ (old('role', $item->role) == 'admin') ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="1" {{ (old('status', $item->status) == 1) ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ (old('status', $item->status) == 0) ? 'selected' : '' }}>Disable</option>
                        </select>
                    </div>
                    
                    <hr>
                    <p class="text-muted"><small><i>Biarkan kosong jika tidak ingin mengubah password.</i></small></p>

                    <div class="form-group">
                        <label>Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control" minlength="8">
                    </div>
                    
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" minlength="8">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
