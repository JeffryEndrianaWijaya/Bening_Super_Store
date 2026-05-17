<button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahCuciGudang">
    <i class="fas fa-plus"></i> Tambah Program Cuci Gudang
</button>

<x-dialog id="modalTambahCuciGudang" size="md">
    <form action="{{ route('cuci_gudang.store') }}" method="POST" class="form-ajax">
        @csrf
        <x-dialog.header title="Tambah Program Cuci Gudang" />

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
                <label for="persen_diskon">Persentase Diskon (%)</label>
                <input type="number" name="persen_diskon" id="persen_diskon" min="1" max="100"
                    class="form-control"
                    placeholder="Contoh: 50">
                <div class="invalid-feedback error-persen_diskon"></div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="waktu_mulai">Tanggal Mulai</label>
                        <input type="date" name="waktu_mulai" id="waktu_mulai" class="form-control">
                        <div class="invalid-feedback error-waktu_mulai"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="waktu_selesai">Tanggal Selesai</label>
                        <input type="date" name="waktu_selesai" id="waktu_selesai" class="form-control">
                        <div class="invalid-feedback error-waktu_selesai"></div>
                    </div>
                </div>
            </div>
        </x-dialog.content>

        <x-dialog.footer>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Program</button>
        </x-dialog.footer>
    </form>
</x-dialog>
