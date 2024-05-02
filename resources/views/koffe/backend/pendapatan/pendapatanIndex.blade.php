@extends('koffe.backend.default')

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
                            <li class="breadcrumb-item" aria-current="page">Data Pendapatan</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Data Pendapatan</h2>
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
                        <small>Data Pendapatan</small>
                    </div>
                    <div class="card-body">
                    <div class="row row-cols-md-auto g-2 align-items-center">
                            <div class="col-12">
                                <label for="awaltgl">Periode </label>
                                <input type="date" class="form-control form-control-sm" id="tgl_nota">
                            </div>
                            <div class="col-12">
                                <label for="akhirtgl">- Pendapatan</label>
                                <input type="date" class="form-control form-control-sm" id="tgl_notaAkhir">
                            </div>
                            <div class="col-12">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-cari btn-sm form-control" style="margin-top: -10px;">Search</button>
                            </div>
                        </div>
                        <br>
                        <div class="dt-responsive">
                            <table id="data-pendapatan" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Total Penjualan</th>
                                        <th>Total Pengeluaran</th>
                                        <th>Laba Bersih</th>
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
                let today = new Date();

                let year = today.getFullYear();
                let month = (today.getMonth() + 1).toString().padStart(2, '0');
                let day = today.getDate().toString().padStart(2, '0');

                let formattedDate = `${year}-${month}-${day}`;

                console.log(formattedDate);

                let tgl_nota = year + '-' + month + '-' + day;
                $('#tgl_nota').val(tgl_nota);

                let tgl_notaAkhir = year + '-' + month + '-' + day ;
                $('#tgl_notaAkhir').val(tgl_notaAkhir);

                var listQuery = {
                    page: 1,
                    limit: 2000,
                    sort: '+tgl_nota',
                    tgl_nota: tgl_nota,
                    tgl_notaAkhir: tgl_notaAkhir
                };

                var table = $('#data-pendapatan').DataTable({
                    ajax: {
                        url: "{{ route('data.pendapatan') }}",
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
                        { data: 'tgl_nota', name: 'tgl_nota' },
                        {
                            data: 'total_penjualan',
                            name: 'total_penjualan',
                            render: function(data, type, row) {
                                // Format data angka ke dalam format mata uang, misalnya: Rp 1,000,000
                                return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                            }
                        },
                        {
                            data: 'total_pendapatan',
                            name: 'total_pendapatan',
                            render: function(data, type, row) {
                                // Format data angka ke dalam format mata uang, misalnya: Rp 1,000,000
                                return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                            }
                        },
                        {
                            data: 'selisih',
                            name: 'selisih',
                            render: function(data, type, row) {
                                // Format data angka ke dalam format mata uang, misalnya: Rp 1,000,000
                                return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                            }
                        }
                    ],
                    "bDestroy": true,
                    footerCallback: function (row, data, start, end, display) {
                        let api = this.api();
                        let api2 = this.api();
                        let api3 = this.api();

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
                            .column(3)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        total2 = api2
                            .column(2)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        total3 = api3
                            .column(1)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        // Total over this page
                        pageTotal = api
                            .column(3, { page: 'current' })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        pageTotal2 = api2
                            .column(2, { page: 'current' })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        pageTotal3 = api3
                            .column(1, { page: 'current' })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        // Update footer
                        api.column(3).footer().innerHTML =
                            'Total Penjualan Rp. ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                        api2.column(2).footer().innerHTML =
                            'Total Pengeluaran Rp. ' + total2.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                        api3.column(1).footer().innerHTML =
                            'Total Laba Rp. ' + total3.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                    }
                });

                $('.btn-cari').click(function (e) {
                    e.preventDefault();
                    var tgl_nota = $('#tgl_nota').val();
                    var tgl_notaAkhir = $('#tgl_notaAkhir').val();

                    listQuery.tgl_nota = tgl_nota;
                    listQuery.tgl_notaAkhir = tgl_notaAkhir;

                    // Destroy the existing DataTable instance
                    if ($.fn.DataTable.isDataTable('#data-pendapatan')) {
                        $('#data-pendapatan').DataTable().destroy();
                    }

                    // Reinitialize DataTable
                    var table2 = $('#data-pendapatan').DataTable({
                        ajax: {
                            url: "{{ route('data.pendapatan') }}",
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
                            { data: 'tgl_nota', name: 'tgl_nota' },
                            {
                                data: 'total_penjualan',
                                name: 'total_penjualan',
                                render: function(data, type, row) {
                                    // Format data angka ke dalam format mata uang, misalnya: Rp 1,000,000
                                    return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                                }
                            },
                            {
                                data: 'total_pendapatan',
                                name: 'total_pendapatan',
                                render: function(data, type, row) {
                                    // Format data angka ke dalam format mata uang, misalnya: Rp 1,000,000
                                    return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                                }
                            },
                            {
                                data: 'selisih',
                                name: 'selisih',
                                render: function(data, type, row) {
                                    // Format data angka ke dalam format mata uang, misalnya: Rp 1,000,000
                                    return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                                }
                            }
                        ],
                        "bDestroy": true,
                        footerCallback: function (row, data, start, end, display) {
                            let api = this.api();
                            let api2 = this.api();
                            let api3 = this.api();

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
                                .column(3)
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            total2 = api2
                                .column(2)
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            total3 = api3
                                .column(1)
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // Total over this page
                            pageTotal = api
                                .column(3, { page: 'current' })
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            pageTotal2 = api2
                                .column(2, { page: 'current' })
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            pageTotal3 = api3
                                .column(1, { page: 'current' })
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // Update footer
                            api.column(3).footer().innerHTML =
                                'Total Penjualan Rp. ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            api2.column(2).footer().innerHTML =
                                'Total Pengeluaran Rp. ' + total2.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            api3.column(1).footer().innerHTML =
                                'Total Laba Rp. ' + total3.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                        }
                    });
                });
            }

            load();
        });
    </script>
@endpush
