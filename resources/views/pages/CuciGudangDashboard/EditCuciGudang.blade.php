<x-dialog id="modalEditCuciGudang-{{ $item->id_cuci_gudang }}" size="md">
    <form action="{{ route('cuci_gudang.update', $item->id_cuci_gudang) }}" method="POST" class="form-ajax">
        @csrf
        @method('PUT')
        <input type="hidden" name="id_cuci_gudang" value="{{ $item->id_cuci_gudang }}">
        
        <x-dialog.header title="Edit Program Cuci Gudang" />

        <x-dialog.content>
            <div class="form-group">
                <label for="id_produk_{{ $item->id_cuci_gudang }}">Pilih Produk</label>
                <select name="id_produk" id="id_produk_{{ $item->id_cuci_gudang }}" class="form-control select2-produk" style="width: 100%;">
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
                <label for="persen_diskon_{{ $item->id_cuci_gudang }}">Persentase Diskon (%)</label>
                <input type="number" name="persen_diskon" id="persen_diskon_{{ $item->id_cuci_gudang }}" min="1" max="100"
                    class="form-control"
                    placeholder="Contoh: 50" value="{{ $item->persen_diskon }}">
                <div class="invalid-feedback error-persen_diskon"></div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="waktu_mulai_{{ $item->id_cuci_gudang }}">Tanggal Mulai</label>
                        <input type="date" name="waktu_mulai" id="waktu_mulai_{{ $item->id_cuci_gudang }}" class="form-control" value="{{ $item->waktu_mulai ? $item->waktu_mulai->format('Y-m-d') : '' }}">
                        <div class="invalid-feedback error-waktu_mulai"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="waktu_selesai_{{ $item->id_cuci_gudang }}">Tanggal Selesai</label>
                        <input type="date" name="waktu_selesai" id="waktu_selesai_{{ $item->id_cuci_gudang }}" class="form-control" value="{{ $item->waktu_selesai ? $item->waktu_selesai->format('Y-m-d') : '' }}">
                        <div class="invalid-feedback error-waktu_selesai"></div>
                    </div>
                </div>
            </div>
        </x-dialog.content>

        <x-dialog.footer>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </x-dialog.footer>
    </form>
</x-dialog>
