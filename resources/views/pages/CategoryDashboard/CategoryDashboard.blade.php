<x-dashboard-layout title="Kategori" activeMenu="category">
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">DataTable with default features</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nos</th>
                            <th>Nama Kategori</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                Handphone
                            </td>
                            <td>senin, 19 januari 2026</td>
                            <td>senin, 19 januari 2026</td>
                            <td>
                                <x-button label="Edit" color="warning" className="mr-2"
                                    onclick="window.location.href='{{ route('kategori.edit') }}" />
                                <x-button color="danger" label="Hapus Data" />
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Rendering engine</th>
                            <th>Browser</th>
                            <th>Platform(s)</th>
                            <th>Engine version</th>
                            <th>CSS grade</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</x-dashboard-layout>
