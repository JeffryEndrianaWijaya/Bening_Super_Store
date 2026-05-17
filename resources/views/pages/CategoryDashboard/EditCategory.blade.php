<x-dialog id="modalEditKategori-{{ $item->id_kategori }}" size="md">
    <form action="{{ route('kategori.update', $item->id_kategori) }}" method="POST" class="form-ajax">
        @csrf
        @method('PUT')
        <input type="hidden" name="id_kategori" value="{{ $item->id_kategori }}">
        <x-dialog.header title="Edit Kategori" />

        <x-dialog.content>
            <div class="form-group">
                <label for="nama_kategori_{{ $item->id_kategori }}">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="nama_kategori_{{ $item->id_kategori }}"
                    class="form-control"
                    placeholder="Masukkan nama kategori..." value="{{ $item->nama_kategori }}">
                <div class="invalid-feedback error-nama_kategori"></div>
            </div>
        </x-dialog.content>

        <x-dialog.footer>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </x-dialog.footer>
    </form>
</x-dialog>