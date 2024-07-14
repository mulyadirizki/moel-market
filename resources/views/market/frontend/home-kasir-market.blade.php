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
            <h4 class="card-title">Transaksi Penjualan </h4>
            <p class="text-muted mb-0">Kantin IGD. -
            {{ auth()->user()->nama }}
            </p>
          </div>
          <div class="card-body p-3">
            <div class="row">
                <div class="col-lg-4">
                    <div class="general-label">
                        <form>
                            <input type="hidden" id="kode_user" value="{{ auth()->user()->kode_user }}">
                            <div class="mb-3 row">
                                <label for="no_nota" class="col-sm-3 col-form-label">No Nota</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="no_nota" placeholder="No Nota">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="tgl_nota" class="col-sm-3 col-form-label">Tgl Nota</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="datetime" id="tgl_nota">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="no_nota" class="col-sm-3 col-form-label">Barang</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control id_barang" name="member[]" onchange="getDataBarang()" value="" id="input" />
                                    <!-- <input type="text" class="form-control" id="no_nota" placeholder="Kasir"> -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <blockquote class="card-bodyquote mb-0">
                                <h1 style="color: white; text-align: right; font-weight: bold;" class="big-grand">Rp. 0</h1>
                            </blockquote>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 table-centered table-sm" id="dataTable">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th style="width: 150px">Kode</th>
                                <th style="width: 200px">Satuan</th>
                                <th>Harga</th>
                                <th width="60px">Qty</th>
                                <th>Sub Total</th>
                                <th style="width: 10px">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                        </tbody>
                    </table>
                    <!--end /table-->
                </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
                <div class="col-lg-8">
                    <div class="button-items" style="float: right; margin-top: 150px">
                        <a href="{{ route('logout') }}">
                            <button type="button" class="btn btn-danger btn-sm"><i class="mdi mdi-power me-2"></i>Keluar</button>
                        </a>
                        <button type="button" class="btn btn-warning btn-sm" id="btn-batal" onclick="batalTransaksi()">
                            <i class="mdi mdi-alert-outline me-2"></i>Batal
                        </button>
                        <a href="{{ route('history.transaksi') }}">
                            <button type="button" class="btn btn-info btn-sm" id="btn-batal">
                                <i class="mdi mdi-alert-outline me-2 "></i>History Transaksi
                            </button>
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" onclick="simpan()">
                            <i class="mdi mdi-check-all me-2"></i>Bayar (F8)
                        </button>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="general-label">
                        <form>
                            <div class="mb-3 row">
                                <label for="no_nota" class="col-sm-3 col-form-label">Total Jual</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="total_jual" value="Rp. 0">
                                </div>
                            </div>

                            <bdiv class="mb-3 row">
                                <label for="tgl_nota" class="col-sm-3 col-form-label">Grand Total</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" value="Rp. 0" id="grand_total">
                                </div>
                            </bdiv>
                        </form>
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
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" onclick="btnClose()">Close</button>
        <button type="button" class="btn btn-warning btn-sm" id="printBillingBtn" data-dismiss="modal" onclick="btnPrint()">Cetak</button>
        <button type="button" class="btn btn-primary btn-submit btn-bayar btn-sm" disabled onclick="saveTransaksi()">Simpan (F9)</button>
      </div>
    </div>
  </div>
</div>
@endsection
@push('script')
<script>
    $(document).ready(function() {
        document.addEventListener("keypress", function(e) {
            if (e.target.tagName !== "INPUT") {
                var input = document.querySelector(".id_barang");
                input.focus();
                input.value = e.key;
                e.preventDefault();
            }
        });

        function generateSequence() {
            return Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        }

        function getCurrentDate() {
            const date = new Date();
            const year = date.getFullYear();
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            return `${year}${month}${day}`;
        }

        function generateNotaNumber() {
            const prefix = "INV";
            const currentDate = getCurrentDate();
            const sequence = generateSequence();
            return `${prefix}${currentDate}${sequence}`;
        }

        function getFormattedDate() {
            const date = new Date();
            const year = date.getFullYear();
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function getFormattedTime() {
            const date = new Date();
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            const seconds = date.getSeconds().toString().padStart(2, '0');
            return `${hours}:${minutes}:${seconds}`;
        }

        $('#no_nota').val(generateNotaNumber());
        $('#tgl_nota').val(`${getFormattedDate()} ${getFormattedTime()}`);

        $("#input").focus();

        $("#input").autocomplete({
            minLength: 0,
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('get.data.barang') }}",
                    dataType: 'json',
                    data: {
                        q: request.term
                    },
                    success: function(data) {
                        const dataBarang = data.data.map(item => {
                            return {
                                label: item.nama_barang + ' (Stok ' + item.total_stok + ')',
                                value: item.id_barang
                            };
                        });
                        response(dataBarang);
                    }
                });
            },
            select: function(event, ui) {
                $(".id_barang").val(ui.item.value);
                getDataBarang(ui.item.value);
            }
        });
    });

    function getDataBarang(id) {
        $.get("{{ route('get.data.barangId', ['id' => ':id']) }}".replace(':id', id), function(response) {
            if (response.hasOwnProperty('data')) {
                const data = response.data;
                const id_barang = data.id_barang;
                const existingRow = $(`.id_input_${id_barang}`).closest('tr');

                if (existingRow.length) {
                    const input = existingRow.find('input[name="qty[]"]');
                    input.val(parseInt(input.val()) + 1);
                    countPrice(id_barang, data.harga_jual_default);
                } else {
                    makeNewRow(data);
                }

                $(".id_barang").val("");
            } else {
                console.log('key not exists');
            }
        });
    }

    function makeNewRow(data) {
        const changeFormat = data.harga_jual_default.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        const html = `<tr data-id="${data.id_barang}">
            <td>${data.nama_barang}</td>
            <td>${data.kode_barcode}</td>
            <td>${data.desc_satuan}</td>
            <td>Rp. ${changeFormat}</td>
            <td><input type="number" class="id_input_${data.id_barang} small-input" id="qty" onkeyup="countPrice('${data.id_barang}', ${data.harga_jual_default})" onclick="countPrice('${data.id_barang}', ${data.harga_jual_default})"  name="qty[]" value="1" style="border: none; width: 60px;" /></td>
            <td class="id_total_${data.id_barang}"></td>
            <td><button class="btn" onClick="hapusData('${data.id_barang}')">x</button></td>
        </tr>`;
        $('#tbody').append(html);
        countPrice(data.id_barang, data.harga_jual_default);
    }

    function countPrice(id_barang, harga) {
        let vol = parseInt($(`.id_input_${id_barang}`).val());

        // Cek apakah vol kosong atau kurang dari 1
        // if (isNaN(vol) || vol < 1) {
        //     vol = 1; // Set ke default 1
        //     $(`.id_input_${id_barang}`).val(1); // Perbarui nilai input
        // }

        const count = vol * harga;
        const changeFormat = count.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $(`.id_total_${id_barang}`).text("Rp. " + changeFormat);
        grandTotal();
    }

    function grandTotal() {
        let total = 0;
        $('#tbody tr').each(function() {
            const count = parseInt($(this).find('td:eq(5)').text().replace(/\D/g, ''));
            total += isNaN(count) ? 0 : count;
        });
        const changeFormat = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $("#grand_total").val('Rp. ' + changeFormat);
        $(".big-grand").text('Rp. ' + changeFormat);
        $("#total_jual").val('Rp. ' + changeFormat);
    }

    function hapusData(id_barang) {
        $(`.id_input_${id_barang}`).closest('tr').remove();
        grandTotal();
    }

    function simpan() {
        const total = $("#grand_total").val();
        $('#total_belanja').val(total);
        const totalConvert = total.replace(/\D/g, '');

        if (totalConvert === '0') {
            $.Toast("Warning", 'Tidak ada produk yang ditambahkan, tambahkan beberapa produk terlebih dahulu', "warning", {
                has_icon: true,
                has_close_btn: true,
                stack: true,
                fullscreen: false,
                timeout: 8000,
                sticky: false,
                has_progress: true,
                rtl: false,
                position_class: "toast-top-right",
                width: 150,
            });
        } else {
            $('#exampleModalCenter').modal('show');

            $('#exampleModalCenter').on('shown.bs.modal', function () {
                $("#bayar").focus();
            });
            const pembayaran = $('#pembayaran').val();
            if (pembayaran === '2') {
                $('.kembali').text('0');
            } else {
                $('#bayar').on('keyup', function() {
                const bayar = parseInt($('#bayar').val());
                const totalConvParse = parseInt(totalConvert)
                
                if (isNaN(bayar) || bayar < totalConvParse) {
                    $('.kembali').text('Belum cukup');
                    $('.btn-bayar').attr('disabled', 'disabled');
                } else if (bayar == totalConvParse) {
                    $('.kembali').text('Uang pas');
                    $('.btn-bayar').removeAttr('disabled');
                } else {
                    const kembali = bayar - totalConvParse;
                    $('.kembali').text('Rp. ' + kembali.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $('.btn-bayar').removeAttr('disabled');
                }
            });
            }
        }
    }

    function changePembayaran() {
        let pembayaran = $('#pembayaran').val();
        if (pembayaran === '2') {
            $('#nmpiutang')[0].removeAttribute("hidden")
        } else {
            $('#nmpiutang')[0].setAttribute("hidden", true)
        }
    }

    document.addEventListener('keydown', function(event) {
        if(event.keyCode == 119) {
            simpan()
        }
        else if(event.keyCode == 120) {
            saveTransaksi()
        }
    });

    function saveTransaksi() {
        const token = $("meta[name='csrf-token']").attr("content");

        const dataSave = {
            no_nota: $('#no_nota').val(),
            tgl_nota: $('#tgl_nota').val(),
            total: $('#grand_total').val().replace(/\D/g, ''),
            uang_bayar: $('#bayar').val(),
            uang_kembali: $('.kembali').text().replace(/\D/g, ''),
            cara_bayar: $('#pembayaran').val(),
            nm_pelanggan: $('#nm_pelanggan').val(),
            barang: $('#tbody tr').map(function() {
                const row = $(this);
                const cleanedText = row.find('td:eq(3)').text().replace(/\D/g, '')
                return {
                    id_barang: row.data('id'),
                    qty: row.find('input[name="qty[]"]').val(),
                    harga_peritem: cleanedText.replace(/\.?0{2}$/, ''),
                    sub_total: row.find('td:eq(5)').text().replace(/\D/g, '')
                };
            }).get()
        };

        $.ajax({
            url: "{{ route('save.transaksi') }}",
            method: "POST",
            dataType: "JSON",
            contentType: "application/json",
            data: JSON.stringify({
                dataSave,
                "_token": token
            }),
            success: function(response) {
                $('#printBillingBtn').data('id-penjualan-market', response.id_penjualan_market);
                if (response.success) {
                    $.Toast("Berhasil", 'Transaksi berhasil', "success", {
                        has_icon: true,
                        has_close_btn: true,
                        stack: true,
                        fullscreen: false,
                        timeout: 8000,
                        sticky: false,
                        has_progress: true,
                        rtl: false,
                        position_class: "toast-top-right",
                        width: 150,
                    });
                    // $('#exampleModalCenter').modal('hide');
                    // location.reload();
                } else {
                    alert('Gagal menyimpan transaksi.');
                }
            },
            error: function() {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    }

    function btnPrint() {
        var id_penjualan_market = $('#printBillingBtn').data('id-penjualan-market');
        var url = "{{ route('market.print.transaksi', ['id' => ':id']) }}".replace(':id', id_penjualan_market);
        console.log(url)

        var popupWindow = window.open(url, "_blank", "width=110");
        // Tunggu sampai jendela baru dimuat
        popupWindow.onload = function() {
            // Memicu pencetakan setelah halaman eksternal dimuat
            popupWindow.print();
        };
        popupWindow.document.head.insertAdjacentHTML("beforeend", "<style>@page { size: 58mm; }</style>");
    }

    function batalTransaksi() {
        location.reload();
    }

    function btnClose() {
        $('#exampleModalCenter').modal('hide');
    }

</script>
@endpush