<button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahKategori">
    <i class="fas fa-plus"></i> Tambah Kategori
</button>

<x-dialog id="modalTambahKategori" size="md">
    <form action="{{ route('kategori.store') }}" method="POST" class="form-ajax">
        @csrf
        <x-dialog.header title="Tambah Kategori Baru" />

        <x-dialog.content>
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="nama_kategori"
                    class="form-control"
                    placeholder="Masukkan nama kategori...">
                <div class="invalid-feedback error-nama_kategori"></div>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
                <div class="invalid-feedback error-status"></div>
            </div>
        </x-dialog.content>

        {{-- 3. FOOTER DIALOG --}}
        <x-dialog.footer>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
        </x-dialog.footer>

    </form>
</x-dialog>
