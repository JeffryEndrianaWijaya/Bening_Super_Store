<x-dialog id="modalEditProduk-{{ $item->id_produk }}" size="md">
    <form action="{{ route('produk.update', $item->id_produk) }}" method="POST" class="form-ajax">
        @csrf
        @method('PUT')
        <input type="hidden" name="id_produk" value="{{ $item->id_produk }}">
        <x-dialog.header title="Edit Produk" />

        <x-dialog.content>
            <div class="form-group">
                <label for="nama_produk_{{ $item->id_produk }}">Nama Produk</label>
                <input type="text" name="nama_produk" id="nama_produk_{{ $item->id_produk }}"
                    class="form-control"
                    placeholder="Masukkan nama produk..." value="{{ $item->nama_produk }}">
                <div class="invalid-feedback error-nama_produk"></div>
            </div>

            <div class="form-group">
                <label for="harga_{{ $item->id_produk }}">Harga</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="number" name="harga" id="harga_{{ $item->id_produk }}" min="0"
                        class="form-control"
                        placeholder="Masukkan harga produk..." value="{{ intval($item->harga) }}">
                    <div class="invalid-feedback error-harga"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="id_kategori_{{ $item->id_produk }}">Kategori</label>
                <select name="id_kategori" id="id_kategori_{{ $item->id_produk }}" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id_kategori }}" {{ $item->id_kategori == $kategori->id_kategori ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback error-id_kategori"></div>
            </div>

            <div class="form-group">
                <label for="deskripsi_{{ $item->id_produk }}">Deskripsi Produk</label>
                <textarea name="deskripsi" id="deskripsi_{{ $item->id_produk }}" class="textarea form-control" placeholder="Masukkan deskripsi produk..."
                    style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ $item->deskripsi }}</textarea>
                <div class="invalid-feedback error-deskripsi"></div>
            </div>
        </x-dialog.content>

        <x-dialog.footer>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </x-dialog.footer>
    </form>
</x-dialog>
