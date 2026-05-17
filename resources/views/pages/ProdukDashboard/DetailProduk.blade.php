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
