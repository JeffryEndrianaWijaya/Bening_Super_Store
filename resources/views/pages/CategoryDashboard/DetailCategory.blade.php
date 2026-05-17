<x-dialog id="modalDetailKategori-{{ $item->id_kategori }}" size="md">
    <x-dialog.header title="Detail Kategori" />
    <x-dialog.content>
        <table class="table table-bordered">
            <tr>
                <th width="35%">ID Kategori</th>
                <td>{{ $item->id_kategori }}</td>
            </tr>
            <tr>
                <th>Nama Kategori</th>
                <td>{{ $item->nama_kategori }}</td>
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
