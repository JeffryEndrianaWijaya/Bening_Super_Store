<x-dialog id="modalEditStok-{{ $item->id_stok }}" size="md">
    <form action="{{ route('stok.update', $item->id_stok) }}" method="POST" class="form-ajax">
        @csrf
        @method('PUT')
        <input type="hidden" name="id_stok" value="{{ $item->id_stok }}">
        
        <x-dialog.header title="Edit Catatan Stok" />

        <x-dialog.content>
            <div class="form-group">
                <label for="id_produk_{{ $item->id_stok }}">Pilih Produk</label>
                <select name="id_produk" id="id_produk_{{ $item->id_stok }}" class="form-control select2-produk" style="width: 100%;">
                    <option value=""></option>
                    @foreach ($produks as $produk)
                        <option value="{{ $produk->id_produk }}" {{ $item->id_produk == $produk->id_produk ? 'selected' : '' }}>
                            {{ $produk->nama_produk }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback error-id_produk"></div>
            </div>

            <div class="form-group">
                <label for="jumlah_stok_{{ $item->id_stok }}">Jumlah Stok Ditambahkan</label>
                <input type="number" name="jumlah_stok" id="jumlah_stok_{{ $item->id_stok }}" min="1"
                    class="form-control"
                    placeholder="Masukkan jumlah stok..." value="{{ $item->jumlah_stok }}">
                <div class="invalid-feedback error-jumlah_stok"></div>
            </div>
        </x-dialog.content>

        <x-dialog.footer>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </x-dialog.footer>
    </form>
</x-dialog>
