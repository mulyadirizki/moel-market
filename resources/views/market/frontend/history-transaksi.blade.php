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
                    <div class="card-body p-3">
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
                    </div>
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
                                <input type="hidden" id="id_penjualan_market">
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
    <script>

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
            var id_penjualan_market = $(this).attr("dataId");

            $('#id_penjualan_market').val(id_penjualan_market)
        });

        function btnHapus() {
            $('#modalTransaksiRefund').modal('hide');
        }

        function btnProsesHapus() {
            var id_penjualan_market = $('#id_penjualan_market').val();
            var keteranganhapus = $('#keteranganhapus').val();
            var token = $("meta[name='csrf-token']").attr("content");

            var jsonSave = {
                id_penjualan_market: id_penjualan_market,
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
    </script>
@endpush