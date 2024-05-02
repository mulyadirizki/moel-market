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
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Koffea</a></li>
                            <li class="breadcrumb-item" aria-current="page">Butuh Dibayarkan</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Butuh Dibayarkan</h2>
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
                            <table id="data-penjualan-selesai" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>No Nota</th>
                                        <th>Tanggal Penjualan</th>
                                        <th>Nama Item</th>
                                        <th>Variant</th>
                                        <th>Category</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>Cara bayar</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
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

                var listQuery = {
                    page: 1,
                    limit: 2000,
                    sort: '+tgl_penjualan',
                    category: '',
                    tgl_penjualan: tgl_penjualan,
                    tgl_penjualanAkhir: tgl_penjualanAkhir
                }

                var table = $('#data-penjualan-selesai').DataTable({
                    ajax: {
                        url: "{{ route('penjualan.butuh.dibayarkan') }}",
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
                        { data: 'no_nota', name: 'no_nota' },
                        { data: 'tgl_nota', name: 'tgl_nota' },
                        { data: 'item_name', name: 'item_name' },
                        { data: 'variant_name', name: 'variant_name' },
                        { data: 'category_name', name: 'category_name' },
                        { data: 'qty', name: 'qty' },
                        { data: 'sub_total', name: 'sub_total' },
                        { data: 'paymentmethod', name: 'paymentmethod' },
                        { data: 'statuspayment', name: 'statuspayment' },
                        { data: 'nm_pelanggan', name: 'nm_pelanggan' }
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
                    var category = $('#category').val();

                    var tgl_penjualan_parts = tgl_penjualan.split('T'); // Pisahkan tanggal dan waktu
                    var tgl_penjualan_date = tgl_penjualan_parts[0] + ' ' + timeAwal;
                    listQuery.tgl_penjualan = tgl_penjualan_date

                    var tgl_penjualan_parts_akhir = tgl_penjualanAkhir.split('T'); // Pisahkan tanggal dan waktu
                    var tgl_penjualan_date_akhir = tgl_penjualan_parts_akhir[0] + ' ' + timeAkhir;
                    listQuery.tgl_penjualanAkhir = tgl_penjualan_date_akhir
                    listQuery.category = category

                    var table2 = $('#data-penjualan-selesai').DataTable({
                        ajax: {
                            url: "{{ route('penjualan.butuh.dibayarkan') }}",
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
                            { data: 'no_nota', name: 'no_nota' },
                            { data: 'tgl_nota', name: 'tgl_nota' },
                            { data: 'item_name', name: 'item_name' },
                            { data: 'variant_name', name: 'variant_name' },
                            { data: 'category_name', name: 'category_name' },
                            { data: 'qty', name: 'qty' },
                            { data: 'sub_total', name: 'sub_total' },
                            { data: 'paymentmethod', name: 'paymentmethod' },
                            { data: 'statuspayment', name: 'statuspayment' },
                            { data: 'nm_pelanggan', name: 'nm_pelanggan' }
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
        });
    </script>
@endpush