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
                            <li class="breadcrumb-item"><a href="javascript: void(0)">market</a></li>
                            <li class="breadcrumb-item" aria-current="page">Stok Barang</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Stok Barang</h2>
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
                        <div class="dt-responsive">
                            <table id="data-stok-barang" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barcode</th>
                                        <th>Nama Barang</th>
                                        <th>Satuan</th>
                                        <th>Total Stok</th>
                                        <th>Harga Satuan</th>
                                        <th>Total Rupiah</th>
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

                var table = $('#data-stok-barang').DataTable({
                    ajax: {
                        url: "{{ route('manajemen.stok.barang') }}",
                        type: 'get',
                        dataType: 'JSON'
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
                        { data: 'kode_barcode', name: 'kode_barcode' },
                        { data: 'nama_barang', name: 'nama_barang' },
                        { data: 'desc_satuan', name: 'desc_satuan' },
                        { data: 'total_stok', name: 'total_stok' },
                        { data: 'harga_jual_default', name: 'harga_jual_default', render: function (data, type, row) {
                            return formatRupiah(data);
                        }},
                        { data: 'total_rupiah', name: 'total_rupiah', render: function (data, type, row) {
                            return formatRupiah(data);
                        }}
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