<button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahProduk">
    <i class="fas fa-plus"></i> Tambah Produk
</button>

<x-dialog id="modalTambahProduk" size="md">
    <form action="{{ route('produk.store') }}" method="POST" class="form-ajax">
        @csrf
        <x-dialog.header title="Tambah Produk Baru" />

        <x-dialog.content>
            <div class="form-group">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" name="nama_produk" id="nama_produk"
                    class="form-control"
                    placeholder="Masukkan nama produk...">
                <div class="invalid-feedback error-nama_produk"></div>
            </div>

            <div class="form-group">
                <label for="harga">Harga</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="number" name="harga" id="harga" min="0"
                        class="form-control"
                        placeholder="Masukkan harga produk...">
                    <div class="invalid-feedback error-harga"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="id_kategori">Kategori</label>
                <select name="id_kategori" id="id_kategori"
                    class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id_kategori }}">
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback error-id_kategori"></div>
            </div>
            
            <div class="form-group">
                <label for="deskripsi">Deskripsi Produk</label>
                <textarea name="deskripsi" id="deskripsi" class="textarea form-control" placeholder="Masukkan deskripsi produk..."
                    style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                <div class="invalid-feedback error-deskripsi"></div>
            </div>
        </x-dialog.content>

        <x-dialog.footer>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
        </x-dialog.footer>
    </form>
    @push('scripts')
        <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    @endpush
</x-dialog>
