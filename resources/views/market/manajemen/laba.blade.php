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
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Market</a></li>
                            <li class="breadcrumb-item" aria-current="page">Laba Pendapatan</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Laba Pendapatan</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row" style="margin-top: -20px;">
            <!-- DOM/Jquery table start -->
            <div class="col-sm-12">
                <div class="card">
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
                            <table id="data-laba-pendapatan" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>'
                                        <th>Tgl Penjualan</th>
                                        <th>Total Penjualan</th>
                                        <th>Total Harga Pokok</th>
                                        <th>Laba</th>
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
                    tgl_penjualan: tgl_penjualan_date,
                    tgl_penjualanAkhir: tgl_penjualanAkhir
                }

                var table = $('#data-laba-pendapatan').DataTable({
                    ajax: {
                        url: "{{ route('manajemen.laba.pendapatan') }}",
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
                        { data: null, name: 'no', render: function (data, type, row, meta) {
                            return meta.row + 1; // Nomor urut berdasarkan indeks baris
                        }},
                        { data: 'tgl_penjualan', name: 'tgl_penjualan' },
                        { data: 'total_penjualan', name: 'total_penjualan', render: function (data, type, row) {
                            return formatRupiah(data);
                        }},
                        { data: 'total_harga_pokok', name: 'total_harga_pokok', render: function (data, type, row) {
                            return formatRupiah(data);
                        }},
                        { data: 'laba', name: 'laba', render: function (data, type, row) {
                            return formatRupiah(data);
                        }}
                    ],
                    dom: 'Bfrtip',
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
                            .column(4)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        total2 = api2
                            .column(3)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        total3 = api3
                            .column(2)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        // Total over this page
                        pageTotal = api
                            .column(4, { page: 'current' })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        pageTotal2 = api2
                            .column(3, { page: 'current' })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        pageTotal3 = api3
                            .column(2, { page: 'current' })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);


                        // Update footer
                        api.column(4).footer().innerHTML =
                            'Total Rp. ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                        api2.column(3).footer().innerHTML =
                            'Total Rp. ' + total2.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                        api3.column(2).footer().innerHTML =
                            'Total Rp. ' + total3.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                });

                $('.btn-cari').click(function (e) {
                    e.preventDefault();
                    var tgl_penjualan = $('#tgl_penjualan').val();
                    var tgl_penjualanAkhir = $('#tgl_penjualanAkhir').val();

                    var tgl_penjualan_parts = tgl_penjualan.split('T'); // Pisahkan tanggal dan waktu
                    var tgl_penjualan_date = tgl_penjualan_parts[0] + ' ' + timeAwal;
                    listQuery.tgl_penjualan = tgl_penjualan_date

                    var tgl_penjualan_parts_akhir = tgl_penjualanAkhir.split('T'); // Pisahkan tanggal dan waktu
                    var tgl_penjualan_date_akhir = tgl_penjualan_parts_akhir[0] + ' ' + timeAkhir;
                    listQuery.tgl_penjualanAkhir = tgl_penjualan_date_akhir

                    var table2 = $('#data-laba-pendapatan').DataTable({
                        ajax: {
                            url: "{{ route('manajemen.laba.pendapatan') }}",
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
                            { data: null, name: 'no', render: function (data, type, row, meta) {
                                return meta.row + 1; // Nomor urut berdasarkan indeks baris
                            }},
                            { data: 'tgl_penjualan', name: 'tgl_penjualan' },
                            { data: 'total_penjualan', name: 'total_penjualan', render: function (data, type, row) {
                                return formatRupiah(data);
                            }},
                            { data: 'total_harga_pokok', name: 'total_harga_pokok', render: function (data, type, row) {
                                return formatRupiah(data);
                            }},
                            { data: 'laba', name: 'laba', render: function (data, type, row) {
                                return formatRupiah(data);
                            }}
                        ],
                        "bDestroy": true,
                        dom: 'Bfrtip',
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
                                .column(4)
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            total2 = api2
                                .column(3)
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            total3 = api3
                                .column(2)
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // Total over this page
                            pageTotal = api
                                .column(4, { page: 'current' })
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            pageTotal2 = api2
                                .column(3, { page: 'current' })
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            pageTotal3 = api3
                                .column(2, { page: 'current' })
                                .data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);


                            // Update footer
                            api.column(4).footer().innerHTML =
                                'Total Rp. ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            api2.column(3).footer().innerHTML =
                                'Total Rp. ' + total2.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            api3.column(2).footer().innerHTML =
                                'Total Rp. ' + total3.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    })
                });
            }
            load();

            function formatRupiah(angka) {
                let number_string = angka.toString(),
                    sisa = number_string.length % 3,
                    rupiah = number_string.substr(0, sisa),
                    ribuan = number_string.substr(sisa).match(/\d{3}/g);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                return 'Rp. ' + rupiah;
            }
        });
    </script>
@endpush