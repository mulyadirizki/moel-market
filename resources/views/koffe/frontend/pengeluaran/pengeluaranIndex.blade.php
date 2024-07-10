@extends('koffe.frontend.default')

@section('content')
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('kasir') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Koffea</a></li>
                            <li class="breadcrumb-item" aria-current="page">Pengeluaran</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Pengeluaran</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- [ sample-page ] start -->
            <div class="col-xl-12 col-md-12">
                <div class="card table-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5>Pengeluaran</h5>
                        <div class="btn btn-sm btn-shadow btn-info" onclick="addPengeluaran()">Tambah Pengeluaran</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless mb-0" id="data-pengeluaran">
                                <thead>
                                    <tr>
                                        <th>Tgl Pengeluaran</th>
                                        <th>Nama Barang</th>
                                        <th>Harga Barang</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Keterangan</th>
                                        <th class="text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

    <div class="modal fade" id="modalAddPengeluaran" tabindex="-1" role="dialog" aria-labelledby="modalAddPengeluaranTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Pengeluaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                            <label class="form-label" for="tglpengeluaran">Tgl Pengeluaran</label>
                            <input type="datetime-local" class="form-control" id="tglpengeluaran">
                            </div>
                        </div>
                        <div class="col-lg-4" id="priceitem">
                            <div class="form-group">
                            <label class="form-label" for="namabarang">Nama Barang</label>
                            <input type="text" class="form-control" placeholder="Enter Nama Barang" id="namabarang">
                            </div>
                        </div>
                        <div class="col-lg-4" id="priceitem">
                            <div class="form-group">
                            <label class="form-label" for="hargabarang">Harga Barang</label>
                            <input type="number" class="form-control" placeholder="Enter Harga Barang" id="hargabarang">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label" for="jenispembayaran">Jenis Pembayaran</label>
                                <select class="form-select" id="jenispembayaran">
                                    <option selected value="1">Cash</option>
                                    <option value="2">Transfer</option>
                                    <option value="3">Hutang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4" id="priceitem">
                            <div class="form-group">
                            <label class="form-label" for="keterangan">Keterangan</label>
                            <input type="text" class="form-control form-control-lg" placeholder="Enter Keterangan" id="keterangan">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <a href="{{ route('kasir') }}">
                    <button class="btn btn-sm btn-info">Kembali</button>
                  </a>
                  <button type="button" class="btn btn-sm btn-primary btn-bayar" onclick="pembayaranSave()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        var table
        getDataPengeluaran = () => {
            const result = new Date();
            const formattedDate = `${result.getFullYear()}-${String(result.getMonth() + 1).padStart(2, '0')}-${String(result.getDate()).padStart(2, '0')} ${String(result.getHours()).padStart(2, '0')}:${String(result.getMinutes()).padStart(2, '0')}:${String(result.getSeconds()).padStart(2, '0')}`;
            $('#tglpengeluaran').val(formattedDate);

            table = $('#data-pengeluaran').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pengeluaran') }}",
                columns: [
                    { data: 'tgl_pengeluaran', name: 'tgl_pengeluaran' },
                    { data: 'nama_barang', name: 'nama_barang' },
                    { data: 'harga_barang', name: 'harga_barang' },
                    { data: 'jenispembayaran', name: 'jenispembayaran'},
                    { data: 'keterangan', name: 'keterangan' },
                    { data: 'action', name: 'action', orderable: true, searchable: true }
                ]
            });
        }

        function addPengeluaran() {
            $('#modalAddPengeluaran').modal('show');
        }

        function resetInputData() {
            $('#tglpengeluaran').val('');
            $('#namabarang').val('');
            $('#hargabarang').val('');
            $('#jenispembayaran').val('');
            $('#keterangan').val('');
        }

        function pembayaranSave() {
            var tgl_pengeluaran = $('#tglpengeluaran').val();
            var nama_barang = $('#namabarang').val();
            var harga_barang = $('#hargabarang').val();
            var jenis_pembayaran = $('#jenispembayaran').val();
            var keterangan = $('#keterangan').val();
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                type: "POST",
                url: "{{ route('pengeluaran.add') }}",
                dataType: "JSON",
                data: {
                    tgl_pengeluaran: tgl_pengeluaran,
                    nama_barang: nama_barang,
                    harga_barang: harga_barang,
                    jenis_pembayaran: jenis_pembayaran,
                    keterangan: keterangan,
                    "_token": token
                },
                success: function(response) {
                    if (response.success == true) {
                        $.Toast("Berhasil", response.message, "success", {
                            has_icon:true,
                            has_close_btn:true,
                            stack: true,
                            fullscreen:false,
                            timeout:8000,
                            sticky:false,
                            has_progress:true,
                            rtl:false,
                            position_class: "toast-top-right",
                            width: 150,
                        });
                        setTimeout(function() {
                            $('#modalAddPengeluaran').modal('hide');
                            resetInputData();
                            getDataPengeluaran();
                        }, 1000);
                    }
                },
                error: function(err) {
                    console.log(err)
                    if(err.status == 422) {
                        $.Toast("Berhasil", err.responseJSON.message, "error", {
                            has_icon:true,
                            has_close_btn:true,
                            stack: true,
                            fullscreen:false,
                            timeout:8000,
                            sticky:false,
                            has_progress:true,
                            rtl:false,
                            position_class: "toast-top-right",
                            width: 150,
                        });
                    } else if (err.status == 400) {
                        err.responseJSON.error.map((e) => {
                            $.Toast("Berhasil", e, "error", {
                                has_icon:true,
                                has_close_btn:true,
                                stack: true,
                                fullscreen:false,
                                timeout:8000,
                                sticky:false,
                                has_progress:true,
                                rtl:false,
                                position_class: "toast-top-right",
                                width: 150,
                            });
                        })
                    }
                }
            })
        }

        $('body').on('click', '.btn-delete', function () {

            var id_pengeluaran = $(this).attr("dataId");
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                type: "post",
                url: "{{ route('pengeluaran.delete', ['id' => ':id']) }}".replace(':id', id_pengeluaran),
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                    $.Toast("Success", "Hapus data berhasil", "success", {
                        has_icon:true,
                        has_close_btn:true,
                        stack: true,
                        fullscreen:false,
                        timeout:8000,
                        sticky:false,
                        has_progress:true,
                        rtl:false,
                        position_class: "toast-top-right",
                        width: 250,
                    });
                    table.ajax.reload();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });

        $(document).ready(function() {
            getDataPengeluaran();
        });
    </script>
@endpush