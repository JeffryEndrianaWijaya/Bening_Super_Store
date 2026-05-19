<div class="modal fade" id="modalDetailPesanan-{{ $item->id_pesanan }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-shopping-bag mr-2"></i>Detail Pesanan - {{ $item->order_id }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Order Metadata -->
                <div class="card card-outline card-primary mb-3">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <h5><strong><i class="fas fa-user mr-2 text-primary"></i>Informasi Pelanggan</strong></h5>
                                <hr class="my-2">
                                <strong>Nama:</strong> {{ $item->user->name }} <br>
                                <strong>Email:</strong> {{ $item->user->email }} <br>
                                <strong>Tanggal Pesanan:</strong> {{ $item->created_at->format('d M Y H:i:s') }}
                            </div>
                            <div class="col-md-6 pl-md-4">
                                <h5><strong><i class="fas fa-receipt mr-2 text-primary"></i>Status & Pembayaran</strong></h5>
                                <hr class="my-2">
                                <strong>Total Pembayaran:</strong> <br>
                                <span class="text-lg text-primary font-weight-bold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span> <br>
                                <strong>Status Saat Ini:</strong> 
                                @php
                                    $badgeClass = 'secondary';
                                    if ($item->status == 'paid') $badgeClass = 'success';
                                    elseif ($item->status == 'pending') $badgeClass = 'warning';
                                    elseif ($item->status == 'shipped') $badgeClass = 'info';
                                    elseif ($item->status == 'completed') $badgeClass = 'primary';
                                    elseif ($item->status == 'cancelled' || $item->status == 'expired') $badgeClass = 'danger';
                                @endphp
                                <span class="badge badge-{{ $badgeClass }} px-2 py-1">{{ ucfirst($item->status) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Purchased -->
                <h5 class="mb-2"><strong><i class="fas fa-box-open mr-2 text-primary"></i>Daftar Item yang Dibeli</strong></h5>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered text-center">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Gambar</th>
                                <th>Produk</th>
                                <th width="20%">Harga Satuan</th>
                                <th width="10%">Qty</th>
                                <th width="20%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->details as $idx => $detail)
                                <tr>
                                    <td class="align-middle">{{ $idx + 1 }}</td>
                                    <td class="align-middle">
                                        @php
                                            $firstImage = $detail->produk && $detail->produk->images->first() 
                                                ? asset('storage/' . $detail->produk->images->first()->image_path) 
                                                : asset('assets/img/default-product.png');
                                        @endphp
                                        <img src="{{ $firstImage }}" alt="{{ $detail->nama_produk }}" class="img-thumbnail" style="max-height: 50px; max-width: 50px; object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('assets/img/default-product.png') }}';">
                                    </td>
                                    <td class="align-middle text-left font-weight-bold">
                                        {{ $detail->nama_produk }}
                                        @if($detail->diskon_persen > 0)
                                            <span class="badge badge-danger ml-1">{{ $detail->diskon_persen }}% OFF</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="align-middle font-weight-bold">{{ $detail->qty }}</td>
                                    <td class="align-middle text-right font-weight-bold text-success">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-light">
                                <td colspan="5" class="text-right font-weight-bold align-middle">TOTAL AKHIR:</td>
                                <td class="text-right font-weight-bold text-lg text-primary">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr class="my-4">
                
                <!-- Action Form to Change Status -->
                <div class="card card-outline card-secondary mb-0">
                    <div class="card-header p-2">
                        <h6 class="card-title font-weight-bold m-0"><i class="fas fa-edit mr-1"></i>Ubah Status Pesanan</h6>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('pesanan_admin.update', $item->id_pesanan) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group row mb-0 align-items-center">
                                <label class="col-sm-3 col-form-label font-weight-bold">Status Pesanan:</label>
                                <div class="col-sm-6">
                                    <select name="status" class="form-control select2">
                                        <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu Pembayaran)</option>
                                        <option value="paid" {{ $item->status == 'paid' ? 'selected' : '' }}>Paid (Sudah Dibayar)</option>
                                        <option value="shipped" {{ $item->status == 'shipped' ? 'selected' : '' }}>Shipped (Sedang Dikirim)</option>
                                        <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Completed (Pesanan Selesai)</option>
                                        <option value="cancelled" {{ $item->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                                        <option value="expired" {{ $item->status == 'expired' ? 'selected' : '' }}>Expired (Kedaluwarsa)</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i> Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
