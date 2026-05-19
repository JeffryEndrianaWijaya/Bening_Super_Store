<button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahProduk">
    <i class="fas fa-plus"></i> Tambah Produk
</button>

<x-dialog id="modalTambahProduk" size="md">
    <form action="{{ route('produk.store') }}" method="POST" class="form-ajax" enctype="multipart/form-data">
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
                    class="form-control select2-kategori" style="width: 100%;">
                    <option value=""></option>
                    @foreach ($kategoris as $kategori)
                        @if($kategori->status)
                            <option value="{{ $kategori->id_kategori }}">
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endif
                    @endforeach
                </select>
                <div class="invalid-feedback error-id_kategori"></div>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
                <div class="invalid-feedback error-status"></div>
            </div>

            <div class="form-group">
                <label>Foto Produk <small class="text-muted">(Maksimal 4 gambar)</small></label>
                <div class="invalid-feedback error-images d-block"></div>
                <input type="hidden" name="image_order" id="image-order-add" value="">
                <div id="preview-add" class="upload-preview-container">
                    <div class="upload-dropzone-card" id="dropzone-add">
                        <i class="fas fa-upload upload-icon"></i>
                        <span>ADD IMAGE</span>
                    </div>
                </div>
                <input type="file" id="images-add" name="images[]" class="d-none" multiple accept="image/*">
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
