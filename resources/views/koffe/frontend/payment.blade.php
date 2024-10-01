@extends('koffe.frontend.default')

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
              <li class="breadcrumb-item"><a href="{{ route('kasir') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="javascript: void(0)">Koffea</a></li>
              <li class="breadcrumb-item" aria-current="page">Payment</li>
            </ul>
          </div>
          <div class="col-md-12">
            <div class="page-header-title">
              <h2 class="mb-0">Payment</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
      <!-- [ sample-page ] start -->
      <div class="ecom-content">
        <div class="row">
          <div class="col-xl-6 col-md-6">
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
                  <label class="form-label" for="nonota">No Nota</label>
                  <input type="text" class="form-control" id="nonota" disabled>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                    <label class="form-label" for="statusbayar">Payment Method</label>
                    <select class="form-select" id="statusbayar" onchange="changePembayaran()">
                        <option selected value="1">Lunas</option>
                        <option value="2">Pay Later</option>
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
              <div class="text-end btn-page mb-0 mt-4">
                  <!-- <button class="btn btn-outline-secondary" onclick="btncancelItem()">Cancel</button> -->
                  <button class="btn btn-primary btn-bayar" disabled onclick="paymentAdd()">Bayar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- [ sample-page ] end -->
    </div>
      <!-- [ Main Content ] end -->
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
@endsection
@push('script')
  <script>
    function orderPayment() {
      const result = Math.floor(Math.random() * 1000000000);
      $('#nonota').val(result)

      var dataOrder = sessionStorage.getItem('dataOrderTemp');
      var dataOrderArr = JSON.parse(dataOrder)

      if (dataOrderArr) {
          var total = 0;
          dataOrderArr.forEach(function(itm) {
            total += parseFloat(itm.price) * parseInt(itm.qty);

              $('#totalbayar').text('Rp. '+ total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))
          });
      } else {
          $('#totalbayar').text('Rp. 0');
      }
    }

    function changePembayaran() {
      let statusbayar = $('#statusbayar').val();
      if (statusbayar == 2) {
          $('#nmpiutang')[0].removeAttribute("hidden")
          $('#priceitem')[0].setAttribute("hidden", true)
          $('.btn-bayar').removeAttr('disabled');
          $('#cash').val('0');
      } else if(statusbayar == 3) {
          $('#nmpiutang')[0].removeAttribute("hidden")
          $('.btn-bayar').removeAttr('disabled');
      }else {
          $('#nmpiutang')[0].setAttribute("hidden", true)
          $('#priceitem')[0].removeAttribute("hidden")
          $('.btn-bayar').attr('disabled', 'disabled');
      }
    }

    $('#cash').on('keyup', function() {
      var cash = $('#cash').val();
      console.log(cash)

      var dataOrder = sessionStorage.getItem('dataOrderTemp');
      var dataOrderArr = JSON.parse(dataOrder);
      if (dataOrderArr) {
          var total = 0;
          dataOrderArr.forEach(function(pay) {
              total += parseFloat(pay.price) * parseInt(pay.qty);
          });
          // var totalString = total.toString();
          // var totalWithZeros = totalString + '000';
          // var totalWithThreeDecimals = parseFloat(totalWithZeros);

          if (cash >= total) {
              $('.btn-bayar').removeAttr('disabled');
          } else {
              $('.btn-bayar').attr('disabled', 'disabled');
          }
      } else {
          $('#countCart').text('0');
      }
    });

    function paymentAdd() {

      var nonota = $('#nonota').val();
      var statusbayar = $('#statusbayar').val();
      var namepaylater = $('#namepaylater').val();
      var cash = $('#cash').val();
      var token = $("meta[name='csrf-token']").attr("content");

      var dataOrder = sessionStorage.getItem('dataOrderTemp');
      var dataOrderArr = JSON.parse(dataOrder)


      let today = new Date();
      let date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
      let time = today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds()
      let dateTime = date + ' ' + time;
      if (dataOrderArr) {

        var total = 0;
        let barang = [];
        dataOrderArr.forEach(function(e) {
          total += parseFloat(e.price) * parseInt(e.qty);
          barang.push({
            id_variant: e.id_variant,
            id_item: e.item_id,
            qty: e.qty,
            subtotal: (e.price * e.qty),
            total: (e.price * e.qty),
            harga_peritem: e.price,
            tgl_penjualan: dateTime,
          })

        });

        // let arrSave = []

        var kembali = cash - total

        var jsonSave =
          {
            nonota: nonota,
            tgl_nota: dateTime,
            total: total,
            item: barang,
            cash: cash,
            status: statusbayar,
            nm_pelanggan: namepaylater,
            uang_kembali: kembali
          }

        console.log(jsonSave)

        $.ajax({
          url: "{{ route('payment.order.add') }}",
          type: "POST",
          dataType: "JSON",
          contentType: "application/json",
          data: JSON.stringify({
            "dataObj": jsonSave,
            "_token": token
          }),
          success: function(response) {
              $('#printBillingBtn').data('id-penjualan', response.id_penjualan);

              $.Toast("Success", "Transaksi berhasil", "success", {
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

              $('#modaluangkembali').modal('show');
              if (kembali == 0) {
                $('#iduangkembali').text('Kembalian Pas')
              } else {
                $('#iduangkembali').text('Rp. ' + kembali.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))
              }
              sessionStorage.removeItem('dataOrderTemp');
              countCart();
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
      } else {
          $('#countCart').text('0');
      }
    }

    function btnClose() {
      $('#modaluangkembali').modal('hide');
      window.location = "{{ route('kasir') }}";
    }

    function printBilling() {
        var id_penjualan = $('#printBillingBtn').data('id-penjualan');
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

    $(document).ready(function() {
      orderPayment();
    });
  </script>
@endpush