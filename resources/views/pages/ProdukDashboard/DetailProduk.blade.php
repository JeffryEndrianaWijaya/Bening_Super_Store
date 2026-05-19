<x-dialog id="modalDetailProduk-{{ $item->id_produk }}" size="md">
    <x-dialog.header title="Detail Produk" />
    <x-dialog.content>
        <table class="table table-bordered">
            <tr>
                <th width="35%">ID Produk</th>
                <td>{{ $item->id_produk }}</td>
            </tr>
            <tr>
                <th>Nama Produk</th>
                <td>{{ $item->nama_produk }}</td>
            </tr>
            <tr>
                <th>Harga</th>
                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $item->kategori ? $item->kategori->nama_kategori : '-' }}</td>
            </tr>
            <tr>
                <th>Foto Produk</th>
                <td>
                    @if($item->images && $item->images->count() > 0)
                        <div class="d-flex flex-wrap">
                            @foreach($item->images as $img)
                                <div class="mr-2 mb-2" style="width: 80px; height: 80px; border-radius: 8px; overflow: hidden; border: 1px solid #dee2e6; box-shadow: 0 1px 3px rgba(0,0,0,0.1); cursor: pointer;">
                                    <a href="#" class="btn-preview-image" data-src="{{ asset('storage/' . $img->image_path) }}">
                                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="Foto Produk" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <span class="text-muted"><i>Tidak ada foto</i></span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{!! $item->deskripsi ?? '-' !!}</td>
            </tr>
            <tr>
                <th>Dibuat Pada</th>
                <td>{{ $item->created_at ? $item->created_at->translatedFormat('l, d F Y H:i') : '-' }}</td>
            </tr>
            <tr>
                <th>Diperbarui Pada</th>
                <td>{{ $item->updated_at ? $item->updated_at->translatedFormat('l, d F Y H:i') : '-' }}</td>
            </tr>
        </table>
    </x-dialog.content>
    <x-dialog.footer>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </x-dialog.footer>
</x-dialog>
