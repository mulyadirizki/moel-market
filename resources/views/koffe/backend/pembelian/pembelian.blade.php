@extends('_partials.default')

@push('meta')
    <meta name="author" content="HPV">
    <meta name="keywords" content="">
    <meta name="description" content=""/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0)">DataTable</a></li>
                            <li class="breadcrumb-item" aria-current="page">Data Pembelian</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Data Pembelian</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- DOM/Jquery table start -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <small>Data Refund Transaksi</small>
                    </div>
                    <div class="card-body">
                    <div class="row row-cols-md-auto g-2 align-items-center">
                            <div class="col-12">
                                <label for="awaltgl">Periode </label>
                                <input type="datetime-local" class="form-control form-control-sm" id="tgl_pengeluaran">
                            </div>
                            <div class="col-12">
                                <label for="akhirtgl">- Pembelian</label>
                                <input type="datetime-local" class="form-control form-control-sm" id="tgl_pengeluaranAkhir">
                            </div>
                            <div class="col-12">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-cari btn-sm form-control" style="margin-top: -10px;">Search</button>
                            </div>
                        </div>
                        <br>
                        <div class="dt-responsive">
                            <table id="data-pembelian" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Tgl Pengeluaran</th>
                                        <th>Nama Barang</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Keterangan</th>
                                        <th>Harga Barang</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <!-- <th colspan="4" style="text-align:right"></th> -->
                                        <!-- <th colspan="4" style="text-align:right"></th> -->
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
                </div>
            </div>
            <!-- DOM/Jquery table end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
@endsection

@push('script')
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
                let tgl_pengeluaran = year + '-' + month + '-' + day + ' ' + timeAwal;
                $('#tgl_pengeluaran').val(tgl_pengeluaran);

                let timeAkhir = '23:59:00'
                let tgl_pengeluaranAkhir = year + '-' + month + '-' + day + ' ' + timeAkhir;
                $('#tgl_pengeluaranAkhir').val(tgl_pengeluaranAkhir);

                var listQuery = {
                    page: 1,
                    limit: 2000,
                    sort: '+tgl_pengeluaran',
                    tgl_pengeluaran: tgl_pengeluaran,
                    tgl_pengeluaranAkhir: tgl_pengeluaranAkhir
                }

                var table = $('#data-pembelian').DataTable({
                    ajax: {
                        url: "{{ route('data.pembelian') }}",
                        type: 'get',
                        dataType: 'JSON',
                        data: listQuery
                    },
                    buttons: [
                        { extend: 'copy', footer: true },
                        { extend: 'excel', footer: true },
                        { extend: 'csv', footer: true },
                        { extend: 'pdf', footer: true },
                        { extend: 'print', footer: true }
                    ],
                    columns: [
                        { data: 'tgl_pengeluaran', name: 'tgl_pengeluaran' },
                        { data: 'nama_barang', name: 'nama_barang' },
                        { data: 'jenispembayaran', name: 'jenispembayaran' },
                        { data: 'keterangan', name: 'keterangan' },
                        { data: 'harga_barang', name: 'harga_barang' }
                    ],
                    dom: 'Bfrtip',
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
                            .column(4)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        // Total over this page
                        pageTotal = api
                            .column(4, { page: 'current' })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        // Update footer
                        api.column(4).footer().innerHTML =
                            'Total Rp. ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                    }
                });

                $('.btn-cari').click(function (e) {
                    e.preventDefault();
                    var tgl_pengeluaran = $('#tgl_pengeluaran').val();
                    var tgl_pengeluaranAkhir = $('#tgl_pengeluaranAkhir').val();

                    var tgl_pengeluaran_parts = tgl_pengeluaran.split('T'); // Pisahkan tanggal dan waktu
                    var tgl_pengeluaran_date = tgl_pengeluaran_parts[0] + ' ' + timeAwal;
                    listQuery.tgl_pengeluaran = tgl_pengeluaran_date

                    var tgl_pengeluaran_parts_akhir = tgl_pengeluaranAkhir.split('T'); // Pisahkan tanggal dan waktu
                    var tgl_pengeluaran_date_akhir = tgl_pengeluaran_parts_akhir[0] + ' ' + timeAkhir;
                    listQuery.tgl_pengeluaranAkhir = tgl_pengeluaran_date_akhir

                    var table2 = $('#data-pembelian').DataTable({
                        ajax: {
                            url: "{{ route('data.pembelian') }}",
                            type: 'get',
                            dataType: 'JSON',
                            data: listQuery
                        },
                        buttons: [
                            { extend: 'copy', footer: true },
                            { extend: 'excel', footer: true },
                            { extend: 'csv', footer: true },
                            { extend: 'pdf', footer: true },
                            { extend: 'print', footer: true }
                        ],
                        columns: [
                            { data: 'tgl_pengeluaran', name: 'tgl_pengeluaran' },
                            { data: 'nama_barang', name: 'nama_barang' },
                            { data: 'jenispembayaran', name: 'jenispembayaran' },
                            { data: 'keterangan', name: 'keterangan' },
                            { data: 'harga_barang', name: 'harga_barang' }
                        ],
                        "bDestroy": true,
                        dom: 'Bfrtip',
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
                                .column(4)
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // Total over this page
                            pageTotal = api
                                .column(4, { page: 'current' })
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // Update footer
                            api.column(4).footer().innerHTML =
                                'Total Rp. ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                        }
                    })
                });
            }
            load();
        });
    </script>
@endpush