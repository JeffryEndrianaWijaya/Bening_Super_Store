<x-dialog id="modalDetailStok-{{ $item->id_stok }}" size="md">
    <x-dialog.header title="Detail Riwayat Stok" />

    <x-dialog.content>
        <table class="table table-striped table-bordered">
            <tr>
                <th width="40%">ID Pencatatan</th>
                <td>STK-{{ str_pad($item->id_stok, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <th>Produk</th>
                <td>{{ $item->produk ? $item->produk->nama_produk : '-' }}</td>
            </tr>
            <tr>
                <th>Jumlah Masuk</th>
                <td>{{ number_format($item->jumlah_stok, 0, ',', '.') }} Item</td>
            </tr>
            <tr>
                <th>Dicatat Pada</th>
                <td>{{ $item->created_at ? $item->created_at->translatedFormat('l, d F Y H:i:s') : '-' }}</td>
            </tr>
            <tr>
                <th>Terakhir Diperbarui</th>
                <td>{{ $item->updated_at ? $item->updated_at->translatedFormat('l, d F Y H:i:s') : '-' }}</td>
            </tr>
        </table>
    </x-dialog.content>

    <x-dialog.footer>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </x-dialog.footer>
</x-dialog>
