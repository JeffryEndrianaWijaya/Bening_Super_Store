<x-dialog id="modalEditProduk-{{ $item->id_produk }}" size="md">
    <form action="{{ route('produk.update', $item->id_produk) }}" method="POST" class="form-ajax" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id_produk" value="{{ $item->id_produk }}">
        <input type="hidden" name="delete_images" class="delete-images-input" value="">
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
                <select name="id_kategori" id="id_kategori_{{ $item->id_produk }}" class="form-control select2-kategori" style="width: 100%;">
                    <option value=""></option>
                    @foreach($kategoris as $kategori)
                        @if($kategori->status || $item->id_kategori == $kategori->id_kategori)
                            <option value="{{ $kategori->id_kategori }}" {{ $item->id_kategori == $kategori->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endif
                    @endforeach
                </select>
                <div class="invalid-feedback error-id_kategori"></div>
            </div>

            <div class="form-group">
                <label for="status_{{ $item->id_produk }}">Status</label>
                <select name="status" id="status_{{ $item->id_produk }}" class="form-control">
                    <option value="1" {{ $item->status ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$item->status ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                <div class="invalid-feedback error-status"></div>
            </div>

            <div class="form-group">
                <label>Foto Produk <small class="text-muted">(Maksimal 4 gambar)</small></label>
                <div class="invalid-feedback error-images d-block"></div>
                <input type="hidden" name="image_order" id="image-order-edit-{{ $item->id_produk }}" class="image-order-edit" value="">
                
                {{-- Preview Grid (Menggabungkan gambar lama, baru, dan dropzone card) --}}
                <div id="preview-edit-{{ $item->id_produk }}" class="upload-preview-container edit-preview-container" data-id="{{ $item->id_produk }}">
                    @foreach($item->images as $img)
                        <div class="img-thumb-wrapper" draggable="true" data-type="existing" data-id="{{ $img->id_image }}" id="img-wrapper-{{ $img->id_image }}">
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="Foto Produk">
                            <button type="button" class="btn-delete-thumb">
                                <i class="fas fa-times"></i>
                            </button>
                            <span class="thumb-order-badge"></span>
                        </div>
                    @endforeach
                    
                    {{-- Upload Dropzone Card (Selalu di akhir) --}}
                    <div class="upload-dropzone-card edit-dropzone" id="dropzone-edit-{{ $item->id_produk }}" data-id="{{ $item->id_produk }}">
                        <i class="fas fa-upload upload-icon"></i>
                        <span>ADD IMAGE</span>
                    </div>
                </div>
                <input type="file" id="images-edit-{{ $item->id_produk }}" name="images[]" class="d-none edit-images-file-input" multiple accept="image/*" data-id="{{ $item->id_produk }}">
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
