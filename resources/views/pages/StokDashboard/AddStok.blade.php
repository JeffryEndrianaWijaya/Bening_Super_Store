<button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahStok">
    <i class="fas fa-plus"></i> Tambah Stok Baru
</button>

<x-dialog id="modalTambahStok" size="md">
    <form action="{{ route('stok.store') }}" method="POST" class="form-ajax">
        @csrf
        <x-dialog.header title="Catat Stok Masuk" />

        <x-dialog.content>
            <div class="form-group">
                <label for="id_produk">Pilih Produk</label>
                <select name="id_produk" id="id_produk" class="form-control select2-produk" style="width: 100%;">
                    <option value=""></option>
                    @foreach ($produks as $produk)
                        <option value="{{ $produk->id_produk }}">
                            {{ $produk->nama_produk }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback error-id_produk"></div>
            </div>

            <div class="form-group">
                <label for="jumlah_stok">Jumlah Stok Ditambahkan</label>
                <input type="number" name="jumlah_stok" id="jumlah_stok" min="1"
                    class="form-control"
                    placeholder="Masukkan jumlah stok...">
                <div class="invalid-feedback error-jumlah_stok"></div>
            </div>
        </x-dialog.content>

        <x-dialog.footer>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Stok</button>
        </x-dialog.footer>
    </form>
</x-dialog>
