@extends('market.frontend.default')

@push('css')
<style>
    #dataTable tbody td {
      padding: 5px; /* Atur padding sesuai kebutuhan */
      font-size: 14px; /* Atur ukuran font sesuai kebutuhan */
      line-height: 1.4; /* Sesuaikan line-height sesuai kebutuhan */
    }
</style>
@endpush

@section('content')
    <div class="pc-content">
        <div class="row">
            <!-- [ sample-page ] start -->
            <div class="ecom-content">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Histori Penjualan </h4>
                        <p class="text-muted mb-0">Kantin IGD. -
                        {{ auth()->user()->nama }}
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row row-cols-md-auto g-2 align-items-center">
                            <div class="col-12">
                                <label for="awaltgl">Periode </label>
                                <input type="datetime-local" class="form-control form-control-sm" id="tgl_penjualan">
                            </div>
                            <div class="col-12">
                                <label for="akhirtgl">- Penjualan</label>
                                <input type="datetime-local" class="form-control form-control-sm" id="tgl_penjualanAkhir">
                            </div>
                            <div class="col-12">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-cari btn-sm form-control" style="margin-top: -10px;">Search</button>
                            </div>
                        </div>
                        <br>
                        <div class="dt-responsive">
                            <table id="data-penjualan-selesai" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>ID Barang</th>
                                        <th>Tanggal Penjualan</th>
                                        <th>Nama Barang</th>
                                        <th>Satuan</th>
                                        <th>Qty</th>
                                        <th>Harga Peritem</th>
                                        <th>Subtotal</th>
                                        <th>Kasir</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="card-body p-3">
                        <div class="dt-responsive">
                            <table id="history-penjualan" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Uang Bayar</th>
                                        <th>Uang Kembali</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div> -->
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="button-items" style="float: right;">
                                    <a href="{{ route('logout') }}">
                                        <button type="button" class="btn btn-danger btn-sm"><i class="mdi mdi-power me-2"></i>Keluar</button>
                                    </a>
                                    <a href="{{ route('market.kasir') }}">
                                        <button type="button" class="btn btn-info btn-sm"><i class="mdi mdi-power me-2"></i>Kembali</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Pembayaran</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="btnClose()">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="general-label">
                        <form>
                            <div class="mb-3 row">
                                <label for="horizontalInput2" class="col-sm-2 col-form-label">Pembayaran</label>
                                <div class="col-sm-9">
                                <select class="form-select" aria-label="Default select example" name="pembayaran" id="pembayaran" onchange="changePembayaran()">
                                        <option value="1" selected >Lunas</option>
                                        <option value="2">Piutang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row" hidden id="nmpiutang">
                                <label for="nm_pelanggan" class="col-sm-2 col-form-label">Nama Piutang</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nm_pelanggan" placeholder="">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="no_nota" class="col-sm-2 col-form-label">Total</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="total_belanja" placeholder="">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="tgl_nota" class="col-sm-2 col-form-label">Bayar</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="bayar" placeholder="">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="no_nota" class="col-sm-2 col-form-label">Kembali</label>
                                <div class="card text-white bg-primary">
                                    <div class="card-body">
                                        <blockquote class="card-bodyquote mb-0">
                                            <h1 style="color: white; text-align: right; font-weight: bold;" class="kembali">Rp. 0</h1>
                                        </blockquote>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="btnClose()">Close</button>
            <button type="button" class="btn btn-primary btn-submit btn-bayar" disabled onclick="saveTransaksi()">Simpan (F9)</button>
        </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="modalTransaksiRefund" tabindex="-1" role="dialog" aria-labelledby="modalTransaksiRefundTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Hapus Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 id="iduangkembali">Yakin akan Hapus transaksi ini ?</h5>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="hidden" id="id_penjualan_market_det">
                                <input type="hidden" id="qty">
                                <input type="hidden" id="id_barang">
                                <label class="form-label" for="keteranganhapus">Keterangan Hapus</label>
                                <input type="text" class="form-control" id="keteranganhapus">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info" onclick="btnHapus()">No</button>
                    <button type="button" class="btn btn-primary btn-submit" onclick="btnProsesHapus()">Yes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <!-- <script>

        var table
        function tableSatuan () {
            table = $('#history-penjualan').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('history.transaksi') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'no', orderable: false, searchable: false },
                    { data: 'no_nota', name: 'no_nota' },
                    { data: 'tgl_nota', name: 'tgl_nota' },
                    { data: 'total', name: 'total', render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') },
                    { data: 'uang_bayar', name: 'uang_bayar', render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') },
                    { data: 'uang_kembali', name: 'uang_kembali', render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') },
                    { data: 'action', name: 'action', orderable: true, searchable: true }
                ]
            });

        }

        $('body').on('click', '.btn-edit', function() {
            save_method_form = 'edit'
            var id = $(this).attr("dataId");
            $.Toast("Warning", "Fitur belum aktif", "warning", {
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
        });

        $('body').on('click', '.btn-delete', function () {
            $('#modalTransaksiRefund').modal('show');
            var id_penjualan_market_det = $(this).attr("dataId");

            $('#id_penjualan_market_det').val(id_penjualan_market_det)
        });

        function btnHapus() {
            $('#modalTransaksiRefund').modal('hide');
        }

        function btnProsesHapus() {
            var id_penjualan_market_det = $('#id_penjualan_market_det').val();
            var keteranganhapus = $('#keteranganhapus').val();
            var token = $("meta[name='csrf-token']").attr("content");

            var jsonSave = {
                id_penjualan_market_det: id_penjualan_market_det,
                keteranganhapus: keteranganhapus
            }

            if (keteranganhapus != '') {
                $.ajax({
                    url: "{{ route('history.transaksi.delete') }}",
                    type: "POST",
                    dataType: "JSON",
                    contentType: "application/json",
                    data: JSON.stringify({
                        "dataObj": jsonSave,
                        "_token": token
                    }),
                    success: function (data) {
                        if(data.success === true) {
                            $.Toast("Success", data.message, "success", {
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
                            $('#modalTransaksiRefund').modal('hide');
                            table.ajax.reload();
                        }
                    },
                    error: function (err) {
                        $.Toast("Failed", err.responseJSON.message, "error", {
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
                    }
                });
            } else {
                $.Toast("Failed", 'Keterangan hapus tidak boleh kosong', "error", {
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
            }
        }

        $(document).ready(function() {
            tableSatuan();
        })
    </script> -->

    <script>
        $(document).ready(function() {

            function load() {
                let today = new Date()

                let year = today.getFullYear();
                let month = (today.getMonth() + 1).toString().padStart(2, '0');
                let day = today.getDate().toString().padStart(2, '0');

                let formattedDate = `${year}-${month}-${day}`;

                console.log(formattedDate);

                let timeAwal = '00:00:00'
                let tgl_penjualan = year + '-' + month + '-' + day + ' ' + timeAwal;
                $('#tgl_penjualan').val(tgl_penjualan);

                let timeAkhir = '23:59:00'
                let tgl_penjualanAkhir = year + '-' + month + '-' + day + ' ' + timeAkhir;
                $('#tgl_penjualanAkhir').val(tgl_penjualanAkhir);

                var tgl_penjualan_parts = tgl_penjualan.split('T'); // Pisahkan tanggal dan waktu
                var tgl_penjualan_date = tgl_penjualan_parts[0];

                var listQuery = {
                    page: 1,
                    limit: 2000,
                    sort: '+tgl_penjualan',
                    kasir: '',
                    tgl_penjualan: tgl_penjualan_date,
                    tgl_penjualanAkhir: tgl_penjualanAkhir
                }

                var table = $('#data-penjualan-selesai').DataTable({
                    ajax: {
                        url: "{{ route('history.transaksi') }}",
                        type: 'get',
                        dataType: 'JSON',
                        data: listQuery
                    },
                    columns: [
                        { data: 'id_barang', name: 'id_barang' },
                        { data: 'tgl_penjualan', name: 'tgl_penjualan' },
                        { data: 'nama_barang', name: 'nama_barang' },
                        { data: 'desc_satuan', name: 'desc_satuan' },
                        { data: 'qty', name: 'qty' },
                        { data: 'harga_jual_default', name: 'harga_jual_default' },
                        { data: 'sub_total', name: 'sub_total' },
                        { data: 'nama', name: 'nama' },
                        { data: 'action', name: 'action', orderable: true, searchable: true }
                    ],
                    footerCallback: function (row, data, start, end, display) {
                        let api = this.api();

                        // Remove the formatting to get integer data for summation
                        let intVal = function (i) {
                            return typeof i === 'string'
                                ? i.replace(/[\$,]/g, '') * 1
                                : typeof i === 'number'
                                ? i
                                : 0;
                        };

                        // Total over all pages
                        total = api
                            .column(6)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        // Total over this page
                        pageTotal = api
                            .column(6, { page: 'current' })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);


                        // Update footer
                        api.column(6).footer().innerHTML =
                            'Total Rp. ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                });

                $('.btn-cari').click(function (e) {
                    e.preventDefault();
                    var tgl_penjualan = $('#tgl_penjualan').val();
                    var tgl_penjualanAkhir = $('#tgl_penjualanAkhir').val();
                    var kasir = $('#kasir').val();

                    var tgl_penjualan_parts = tgl_penjualan.split('T'); // Pisahkan tanggal dan waktu
                    var tgl_penjualan_date = tgl_penjualan_parts[0] + ' ' + timeAwal;
                    listQuery.tgl_penjualan = tgl_penjualan_date

                    var tgl_penjualan_parts_akhir = tgl_penjualanAkhir.split('T'); // Pisahkan tanggal dan waktu
                    var tgl_penjualan_date_akhir = tgl_penjualan_parts_akhir[0] + ' ' + timeAkhir;
                    listQuery.tgl_penjualanAkhir = tgl_penjualan_date_akhir
                    listQuery.kasir = kasir

                    var table2 = $('#data-penjualan-selesai').DataTable({
                        ajax: {
                            url: "{{ route('history.transaksi') }}",
                            type: 'get',
                            dataType: 'JSON',
                            data: listQuery
                        },
                        columns: [
                            { data: 'id_barang', name: 'id_barang' },
                            { data: 'tgl_penjualan', name: 'tgl_penjualan' },
                            { data: 'nama_barang', name: 'nama_barang' },
                            { data: 'desc_satuan', name: 'desc_satuan' },
                            { data: 'qty', name: 'qty' },
                            { data: 'harga_jual_default', name: 'harga_jual_default' },
                            { data: 'sub_total', name: 'sub_total' },
                            { data: 'nama', name: 'nama' },
                            { data: 'action', name: 'action', orderable: true, searchable: true }
                        ],
                        "bDestroy": true,
                        footerCallback: function (row, data, start, end, display) {
                            let api = this.api();

                            // Remove the formatting to get integer data for summation
                            let intVal = function (i) {
                                return typeof i === 'string'
                                    ? i.replace(/[\$,]/g, '') * 1
                                    : typeof i === 'number'
                                    ? i
                                    : 0;
                            };

                            // Total over all pages
                            total = api
                                .column(6)
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // Total over this page
                            pageTotal = api
                                .column(6, { page: 'current' })
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // Update footer
                            api.column(6).footer().innerHTML =
                                'Total Rp. ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                        }
                    })
                });
            }
            load();

            $('body').on('click', '.btn-delete', function () {
                $('#modalTransaksiRefund').modal('show');
                var id_penjualan_market_det = $(this).attr("dataId");
                var qty = $(this).attr("qtyId");
                var id_barang = $(this).attr("brgId");

                $('#id_penjualan_market_det').val(id_penjualan_market_det);
                $('#qty').val(qty);
                $('#id_barang').val(id_barang);
            });
        });

        function btnProsesHapus() {
            var id_penjualan_market_det = $('#id_penjualan_market_det').val();
            var keteranganhapus = $('#keteranganhapus').val();
            var qty = $('#qty').val();
            var id_barang = $('#id_barang').val();
            var token = $("meta[name='csrf-token']").attr("content");

            var jsonSave = {
                id_penjualan_market_det: id_penjualan_market_det,
                qty: qty,
                id_barang: id_barang,
                keteranganhapus: keteranganhapus
            }

            if (keteranganhapus != '') {
                $.ajax({
                    url: "{{ route('history.transaksi.delete') }}",
                    type: "POST",
                    dataType: "JSON",
                    contentType: "application/json",
                    data: JSON.stringify({
                        "dataObj": jsonSave,
                        "_token": token
                    }),
                    success: function (data) {
                        if(data.success === true) {
                            $.Toast("Success", data.message, "success", {
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
                            $('#modalTransaksiRefund').modal('hide');
                            window.location.reload();
                        }
                    },
                    error: function (err) {
                        $.Toast("Failed", err.responseJSON.message, "error", {
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
                    }
                });
            } else {
                $.Toast("Failed", 'Keterangan hapus tidak boleh kosong', "error", {
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
            }
        }
    </script>
@endpush