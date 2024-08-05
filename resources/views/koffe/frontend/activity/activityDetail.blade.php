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
                            <li class="breadcrumb-item" aria-current="page">Detail Activity</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Detail Activity</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="tab-content">
                <div class="tab-pane show active" id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">
                    <div class="row">
                        <div class="col-12" style="margin-bottom: 10px;">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-warning btn-shadow dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Print
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" onclick="printDocument()">Print</a>
                                <a class="dropdown-item" href="#!">Print Copy</a>
                                </div>
                            </div>
                            <div class="btn btn-sm btn-shadow btn-success" onclick="changePayment()">Change Payment Method</div>
                            <div class="btn btn-sm btn-shadow btn-danger" onclick="btnRefund()">Refund</div>
                        </div>
                        <div class="col-lg-12 col-xxl-3">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Details</h5>
                                </div>
                                <div class="card-body position-relative" style="margin-top: -15px;">
                                    <div class="text-center mt-3">
                                        <div class="d-inline-flex align-items-center justify-content-between w-100">
                                            <p>Payment Method</p>
                                            <p id="paymentmeth"></p>
                                        </div>
                                        @if($dataPenj->status === 2)
                                            <div class="d-inline-flex align-items-center justify-content-between w-100">
                                                <p>Pay Later Name</p>
                                                <p id="paylatername"></p>
                                            </div>
                                        @elseif($dataPenj->status !== 2 && $dataPenj->tgl_pembayaran != null)
                                            <div class="d-inline-flex align-items-center justify-content-between w-100" >
                                                <p>Tanggal Pembayaran</p>
                                                <p>{{ date("d M Y H:i", strtotime($dataPenj->tgl_pembayaran)) }}</p>
                                            </div>
                                        @endif
                                        <div class="d-inline-flex align-items-center justify-content-between w-100">
                                            <p>Nomor Nota</p>
                                            <p>{{ $dataPenj->no_nota }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-between w-100" >
                                            <p>Tanggal Transaksi</p>
                                            <p>{{ date("d M Y H:i", strtotime($dataPenj->tgl_nota)) }}</p>
                                        </div>
                                    </div>
                                </div><hr>
                                <div class="card-header" style="margin-top: -20px;">
                                    <h5>Items</h5>
                                </div>
                                <div class="card-body position-relative" style="margin-top: -15px;">
                                        <div style="margin-top: 20px;">
                                        @foreach($result as $category => $items)
                                            <div class="row" style="margin-top: -15px; padding-bottom: 15px;">
                                                <div class="col">
                                                    <span><b>{{ $category }}</b></span>
                                                </div>
                                            </div>
                                            @foreach($items as $itm)
                                                <div class="row" style="margin-top: -10px; margin-left: 1px;">
                                                    <div class="col">
                                                        <p>{{ $itm->item_name }}
                                                            @if($itm->variant_name != null)
                                                                ({{ $itm->variant_name }})
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col" style="margin-top: -10px; margin-left: 13px;">
                                                        <div class="float-end">
                                                            <p>Rp. {{ number_format($itm->sub_total) }}</p>
                                                        </div>
                                                        <p>{{ $itm->qty }} x <span style="margin-left: 10px;">Rp. {{ number_format($itm->harga_peritem) }}</span></p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                        <hr style="margin-top: -10px;">
                                        <div class="d-inline-flex align-items-center justify-content-between w-100">
                                            <p>Subtoal</p>
                                            <p>Rp. {{ number_format($dataPenj->total) }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-between w-100">
                                            <p>Total</p>
                                            <p>Rp. {{ number_format($dataPenj->total) }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-between w-100" >
                                            <p>Uang Bayar</p>
                                            <p>
                                                <?php
                                                    if (is_numeric($dataPenj->uang_bayar)) {
                                                        echo 'Rp. ' . number_format($dataPenj->uang_bayar);
                                                    } else {
                                                        echo 'Rp. ' . $dataPenj->uang_bayar .'0';
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-between w-100" >
                                            <p>Uang Kembali</p>
                                            <p>Rp. {{ number_format($dataPenj->uang_kembali) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
      <!-- [ Main Content ] end -->

    <div class="modal fade" id="modalChangePayment" tabindex="-1" role="dialog" aria-labelledby="modalChangePaymentTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Change Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card product-card">
                        <div style="padding: 10px;">
                            <div class="row align-items-center">
                            <div class="col">
                                <h5 class="m-b-5" id="totalbayar">Rp. 0</h5>
                            </div>
                            <div class="col-auto">
                                <span class="pc-arrow">Bayar</span>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                            <label class="form-label" for="tgl_pembayaran">Tgl Pembayaran</label>
                            <input type="text" class="form-control" id="tgl_pembayaran" disabled>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label" for="statusbayar">Payment Method</label>
                                <select class="form-select" id="statusbayar" onchange="choosePay()">
                                    <option selected value="1">Lunas</option>
                                    <option value="2">PayLater</option>
                                    <option value="3">QRIS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4" hidden id="nmpiutang">
                            <div class="form-group">
                            <label class="form-label" for="namepaylater">Name</label>
                            <input type="text" class="form-control" placeholder="Enter Name" id="namepaylater">
                            </div>
                        </div>
                        <div class="col-lg-4" id="priceitem">
                            <div class="form-group">
                            <label class="form-label" for="cash">Cash</label>
                            <input type="number" class="form-control" placeholder="Enter Cash" id="cash">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <a href="{{ route('kasir') }}">
                    <button class="btn btn-sm btn-info">Kembali</button>
                  </a>
                  <button type="button" class="btn btn-sm btn-primary btn-bayar" disabled onclick="paymentAdd()">Bayar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPassword" tabindex="-1" role="dialog" aria-labelledby="modalPasswordTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Password PayLater</h5>
                </div>
                <div class="modal-body">
                    <input type="password" class="form-control" placeholder="Password" id="password">
                </div>
                <div class="modal-footer">
                  <button class="btn btn-success" onclick="btnClose()">Close</button>
                  <button type="button" class="btn btn-primary btn-submit" onclick="btnSubmitPassword()">Oke</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modaluangkembali" tabindex="-1" role="dialog" aria-labelledby="modaluangkembaliTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Uang Kembali</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p id="iduangkembali">Rp. 0</p>
                </div>
                <div class="modal-footer">
                  <a href="{{ route('kasir') }}">
                    <button class="btn btn-info">Kembali</button>
                  </a>
                  <button class="btn btn-success" id="printBillingBtn" onclick="printBilling()">Cetak</button>
                  <button type="button" class="btn btn-primary btn-submit" onclick="btnClose()">Oke</button>
                </div>
            </div>
        </div>
    </div>

    <!-- transaksi refund -->
    <div class="modal fade" id="modalTransaksiRefund" tabindex="-1" role="dialog" aria-labelledby="modalTransaksiRefundTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Refund Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 id="iduangkembali">Yakin akan refund transaksi ini ?</h5>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label" for="keteranganrefund">Keterangan Refund</label>
                                <input type="text" class="form-control" id="keteranganrefund">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <a href="{{ route('activity') }}">
                    <button class="btn btn-info">No</button>
                  </a>
                  <button type="button" class="btn btn-primary btn-submit" onclick="btnProsesRefund()">Yes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>

        getAllActivityDetail = () => {

            var id = window.location.pathname.split('/').pop();
            $.get("{{ route('activity.detail', ['id' => ':id']) }}".replace(':id', id), function (items) {
                $('#paymentmeth').text(items.dtitm.payment_method)
                $('#paylatername').text(items.dtitm.nm_pelanggan)
                $('#totalbayar').text('Rp. ' + items.dtitm.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))
                console.log(items)
            });
        }

        function changePayment() {
            const result = new Date();
            const formattedDate = `${result.getFullYear()}-${String(result.getMonth() + 1).padStart(2, '0')}-${String(result.getDate()).padStart(2, '0')} ${String(result.getHours()).padStart(2, '0')}:${String(result.getMinutes()).padStart(2, '0')}:${String(result.getSeconds()).padStart(2, '0')}`;
            $('#tgl_pembayaran').val(formattedDate);

            $('#modalChangePayment').modal('show');
        }

        $('#cash').on('keyup', function() {
            var cash = $('#cash').val();

            var totalbayar = $('#totalbayar').text();
            var total = $('#totalbayar').text();
            var cleanedTotal = totalbayar.replace('Rp. ', '').replace(',', '');
            var total = parseInt(cleanedTotal, 10);
            console.log(total)

            if (cash >= total) {
                $('.btn-bayar').removeAttr('disabled');
            } else {
                $('.btn-bayar').attr('disabled', 'disabled');
            }
        });

        function choosePay() {
            var selectElement = document.getElementById("statusbayar");
            var selectedValue = selectElement.value;

            // if (selectedValue == "2") {
            //     $('.btn-bayar').removeAttr('disabled');
            //     $('#cash').hide();
            //     console.log("Data PayLater dipilih");
            // } else {
            //     $('.btn-bayar').attr('disabled', 'disabled');
            //     $('#cash').show();
            // }
            if (selectedValue == "2") {
                $('#modalPassword').modal('show');
                $('#cash').attr('disabled', 'disabled');
            } else {
                $('#modalPassword').modal('hide');
                $('.btn-bayar').attr('disabled', 'disabled');
                $('#cash').removeAttr('disabled');
            }
        }

        function btnSubmitPassword() {
            var passwordMatch = 'y4kuz4'
            var password = $('#password').val()
            if (passwordMatch == password) {
                $('.btn-bayar').removeAttr('disabled');
                $('#modalPassword').modal('hide');
            } else {
                alert('Wrong Password')
            }
        }

        function paymentAdd() {

            var tgl_pembayaran = $('#tgl_pembayaran').val();
            var statusbayar = $('#statusbayar').val();
            var cash = $('#cash').val();
            var id_penjualan = window.location.pathname.split('/').pop();
            var token = $("meta[name='csrf-token']").attr("content");

            var totalbayar = $('#totalbayar').text();
            var total = $('#totalbayar').text();
            var cleanedTotal = totalbayar.replace('Rp. ', '').replace(',', '');
            var total = parseInt(cleanedTotal, 10);

            var kembali = cash - total

            var jsonSave = {
                id_penjualan: id_penjualan,
                tgl_pembayaran: tgl_pembayaran,
                statusbayar: statusbayar,
                cash: cash,
                uang_kembali: kembali
            }

            console.log(jsonSave)

            $.ajax({
                url: "{{ route('change.payment.method') }}",
                type: "POST",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify({
                    "dataObj": jsonSave,
                    "_token": token
                }),
                success: function(response) {
                    $('#printBillingBtn').data('id-penjualan', response.id_penjualan);
                    $('#modalChangePayment').modal('hide');
                    getAllActivityDetail();

                    $.Toast("Success", response.message, "success", {
                        has_icon:true,
                        has_close_btn:true,
                        stack: true,
                        fullscreen:false,
                        timeout: 2000,
                        sticky:false,
                        has_progress:true,
                        rtl:false,
                        position_class: "toast-top-right",
                        width: 250,
                    });

                    if (statusbayar != "2") {
                        $('#modaluangkembali').modal('show');
                        if (kembali == 0) {
                            $('#iduangkembali').text('Kembalian Pas')
                        } else {
                            $('#iduangkembali').text('Rp. ' + kembali.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))
                        }
                        sessionStorage.removeItem('dataOrderTemp');
                        countCart();
                    }
                },
                error: function(err) {
                    console.log(err)
                    // err.responseJSON.error.map((e) => {
                    //     $.Toast("Gagal", e, "warning", {
                    //         has_icon:true,
                    //         has_close_btn:true,
                    //         stack: true,
                    //         fullscreen:false,
                    //         timeout:8000,
                    //         sticky:false,
                    //         has_progress:true,
                    //         rtl:false,
                    //         position_class: "toast-top-right",
                    //         width: 250,
                    //     });
                    // })
                }
            })

        }

        function btnClose() {
            $('#modaluangkembali').modal('hide');
            window.location.reload();
        }

        function printDocument() {
            printBilling();
        }

        function printBilling() {
            var id_penjualan = window.location.pathname.split('/').pop();
            var url = "{{ route('billing.print', ['id' => ':id']) }}".replace(':id', id_penjualan);
            console.log(url)

            var popupWindow = window.open(url, "_blank", "width=110");
            // Tunggu sampai jendela baru dimuat
            popupWindow.onload = function() {
                // Memicu pencetakan setelah halaman eksternal dimuat
                popupWindow.print();
            };
            popupWindow.document.head.insertAdjacentHTML("beforeend", "<style>@page { size: 58mm; }</style>");
        }

        function btnRefund() {
            $('#modalTransaksiRefund').modal('show');
        }

        function btnProsesRefund() {
            var id_penjualan = window.location.pathname.split('/').pop();
            var keteranganrefund = $('#keteranganrefund').val();
            var token = $("meta[name='csrf-token']").attr("content");

            var jsonSave = {
                id_penjualan: id_penjualan,
                keteranganrefund: keteranganrefund
            }

            console.log(keteranganrefund)
            if (keteranganrefund != '') {
                $.ajax({
                    url: "{{ route('transaksi.penjualan.delete') }}",
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
                            window.location = "{{ route('activity') }}";
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
                $.Toast("Failed", 'Keterangan refund tidak boleh kosong', "error", {
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
            getAllActivityDetail();
        });
    </script>
@endpush