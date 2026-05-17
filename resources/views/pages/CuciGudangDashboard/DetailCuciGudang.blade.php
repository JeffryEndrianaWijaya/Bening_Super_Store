<x-dialog id="modalDetailCuciGudang-{{ $item->id_cuci_gudang }}" size="md">
    <x-dialog.header title="Detail Program Cuci Gudang" />

    <x-dialog.content>
        <table class="table table-striped table-bordered">
            <tr>
                <th width="40%">ID Program</th>
                <td>CG-{{ str_pad($item->id_cuci_gudang, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <th>Produk</th>
                <td>{{ $item->produk ? $item->produk->nama_produk : '-' }}</td>
            </tr>
            <tr>
                <th>Persentase Diskon</th>
                <td><span class="badge badge-danger">{{ $item->persen_diskon }}%</span></td>
            </tr>
            <tr>
                <th>Waktu Mulai</th>
                <td>{{ $item->waktu_mulai ? $item->waktu_mulai->translatedFormat('l, d F Y H:i:s') : '-' }}</td>
            </tr>
            <tr>
                <th>Waktu Selesai</th>
                <td>{{ $item->waktu_selesai ? $item->waktu_selesai->translatedFormat('l, d F Y H:i:s') : '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @php
                        $now = \Carbon\Carbon::now();
                        $isUpcoming = $item->waktu_mulai ? $now->lt($item->waktu_mulai) : false;
                        $isActive = $item->waktu_mulai && $item->waktu_selesai ? $now->between($item->waktu_mulai, $item->waktu_selesai) : false;
                        $isEnded = $item->waktu_selesai ? $now->gt($item->waktu_selesai) : false;
                    @endphp
                    @if($isUpcoming)
                        <span class="badge badge-warning">Akan Datang</span>
                    @elseif($isActive)
                        <span class="badge badge-success">Aktif</span>
                    @elseif($isEnded)
                        <span class="badge badge-secondary">Berakhir</span>
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>
    </x-dialog.content>

    <x-dialog.footer>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </x-dialog.footer>
</x-dialog>
