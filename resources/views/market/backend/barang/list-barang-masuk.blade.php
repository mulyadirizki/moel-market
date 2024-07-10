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
                            <li class="breadcrumb-item" aria-current="page">Data Barang Masuk</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Data Barang Masuk</h2>
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
                        <button type="button" class="btn btn-primary btn-sm" onclick="addData()">Tambah Data</button>
                        <small>Data Barang Masuk</small>
                    </div>
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table id="data-barang" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Penerimaan</th>
                                        <th>Tgl Terima</th>
                                        <th>Tgl Faktur</th>
                                        <th>No Faktur</th>
                                        <th>Nama Supplier</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- DOM/Jquery table end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalAddBarangMasuk" tabindex="-1" role="dialog" aria-labelledby="modalAddBarangMasukTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Barang Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <input type="hidden" id="id_barang" value="">
                            <div class="col-lg-2">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="tgl_terima">Tgl Terima <span style="color: red;">*</span></label>
                                    <input type="date" class="form-control"  id="tgl_terima">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="tgl_faktur">Tgl Faktur <span style="color: red;">*</span></label>
                                    <input type="date" class="form-control"  id="tgl_faktur">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="no_faktur">No Faktur <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="no_faktur" nama="no_faktur">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="supplier">Nama Supplier <span style="color: red;">*</span></label>
                                    <select class="form-select" id="supplier" name="supplier" style="width: 100%;" value=""></select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="barang">Nama Barang <span style="color: red;">*</span></label>
                                    <select class="form-select" id="barang" name="barang" style="width: 100%;" value=""></select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="tgl_expired">Tgl Expired</label>
                                    <input type="date" class="form-control"  id="tgl_expired" nama="tgl_expired">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="qty">Qty <span style="color: red;">*</span></label>
                                    <input type="number" class="form-control" id="qty" nama="qty">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group input-group-sm">
                                    <label class="form-label">&nbsp;</label><br>
                                    <button type="button" class="btn btn-primary btn-submit" onclick="tambahDataToTable()">Tambah</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="dt-responsive">
                        <table id="temp-barang-masuk" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Tgl Expired</th>
                                    <th>Qty</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-submit" onclick="save()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>

        var table
        function tableSatuan () {
            table = $('#data-barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('data.barang') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'no', orderable: false, searchable: false },
                    { data: 'kode_barcode', name: 'kode_barcode' },
                    { data: 'nama_barang', name: 'nama_barang' },
                    { data: 'nama_kategori', name: 'nama_kategori' },
                    { data: 'desc_satuan', name: 'desc_satuan' },
                    { data: 'desc_merek', name: 'desc_merek' },
                    { data: 'action', name: 'action', orderable: true, searchable: true }
                ]
            });

        }

        function addData() {
            $('#modalAddBarangMasuk').modal('show')

            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0');
            var year = today.getFullYear();

            var formattedDate = year + '-' + month + '-' + day;
            $('#tgl_terima').val(formattedDate);
            $('#tgl_faktur').val(formattedDate);
            $('#tgl_expired').val(formattedDate);
        }

        var dataAllBarang = [];
        var editIndex = -1;

        function tambahDataToTable() {
            var selectedBarang = $('#barang').select2('data')[0];
            var nama_barang = selectedBarang.text;
            var id_barang = selectedBarang.id;
            var tgl_expired = $('#tgl_expired').val();
            var qty = $('#qty').val();

            if (editIndex === -1) {
                dataAllBarang.push({
                    id_barang: id_barang,
                    nama_barang: nama_barang,
                    tgl_expired: tgl_expired,
                    qty: qty
                });
            } else {
                dataAllBarang[editIndex] = {
                    id_barang: id_barang,
                    nama_barang: nama_barang,
                    tgl_expired: tgl_expired,
                    qty: qty
                };
                editIndex = -1;
            }

            console.log(dataAllBarang);

            $('#barang').val(null).trigger('change');

            updateTable();
        }

        function updateTable() {
            var tbody = $('#temp-barang-masuk tbody');
            tbody.empty();
            dataAllBarang.forEach(function(item, index) {
                var row = $('<tr></tr>');
                row.append('<td>' + (index + 1) + '</td>');
                row.append('<td>' + item.nama_barang + '</td>');
                row.append('<td>' + item.tgl_expired + '</td>');
                row.append('<td>' + item.qty + '</td>');
                row.append('<td><button class="btn btn-primary btn-sm" onclick="editData(' + index + ')">Edit</button> <button class="btn btn-danger btn-sm" onclick="hapusData(' + index + ')">Hapus</button></td>');

                tbody.append(row);
            });
        }

        function editData(index) {
            var item = dataAllBarang[index];
            var newOption = new Option(item.nama_barang, item.id_barang, true, true);
            $('#barang').append(newOption).trigger('change');
            $('#tgl_expired').val(item.tgl_expired);
            $('#qty').val(item.qty);
            editIndex = index;
        }

        function hapusData(index) {
            dataAllBarang.splice(index, 1);
            updateTable();
        }

        function save() {
            var token = $("meta[name='csrf-token']").attr("content");

            var dataSave = {
                tgl_terima: $('#tgl_terima').val(),
                tgl_faktur: $('#tgl_faktur').val(),
                no_faktur: $('#no_faktur').val(),
                supplier: $('#supplier').val(),
                barang: dataAllBarang
            }

            console.log(dataSave)

            $.ajax({
                type: "POST",
                url: "{{ route('data.barang.masuk.add') }}",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify({
                    dataSave,
                    "_token": token
                }),
                success: function(response) {
                    if (response.success == true) {
                        $.Toast("Berhasil", response.message, "success", {
                            has_icon:true,
                            has_close_btn:true,
                            stack: true,
                            fullscreen:false,
                            timeout:8000,
                            sticky:false,
                            has_progress:true,
                            rtl:false,
                            position_class: "toast-top-right",
                            width: 150,
                        });
                        setTimeout(function() {
                            $('#modalAddBarangMasuk').modal('hide');
                            table.ajax.reload();
                            $('#kode_barcode').val('')
                            $('#nama_barang').val('')
                            $('#harga_pokok').val('')
                            $('#harga_jual').val('')
                            $('#harga_jual_default').val('')
                        }, 1000);
                    }
                },
                error: function(err) {
                    console.log(err)
                    if(err.status == 422) {
                        $.Toast("Berhasil", err.responseJSON.message, "error", {
                            has_icon:true,
                            has_close_btn:true,
                            stack: true,
                            fullscreen:false,
                            timeout:8000,
                            sticky:false,
                            has_progress:true,
                            rtl:false,
                            position_class: "toast-top-right",
                            width: 150,
                        });
                    } else if (err.status == 400) {
                        err.responseJSON.error.map((e) => {
                            $.Toast("Berhasil", e, "error", {
                                has_icon:true,
                                has_close_btn:true,
                                stack: true,
                                fullscreen:false,
                                timeout:8000,
                                sticky:false,
                                has_progress:true,
                                rtl:false,
                                position_class: "toast-top-right",
                                width: 150,
                            });
                        })
                    } else if (err.status == 500) {
                        err.responseJSON.error.map((e) => {
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
                                width: 150,
                            });
                        })
                    }
                }
            })
        }

        $('body').on('click', '.btn-delete', function () {

            var id_barang = $(this).attr("dataId");
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                type: "post",
                url: "{{ route('data.barang.delete', ['id' => ':id']) }}".replace(':id', id_barang),
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                    $.Toast("Success", "Hapus data berhasil", "success", {
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
                    table.ajax.reload();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });

        $(document).ready(function() {
            tableSatuan();

            $('#supplier').select2({
                placeholder: "Pilih Supplier",
                dropdownParent: $('#modalAddBarangMasuk'),
                ajax: {
                    url: "{{ route('get.supplier') }}",
                    delay: 250,
                    dataType: 'JSON',
                    processResults: function (response) {
                        return {
                            results: $.map(response.data, function(item) {
                                return {
                                    text: item.nama_supplier,
                                    id: item.id_supplier
                                }
                            })
                        }
                    },
                    cache: true
                },
                minimumInputLength: 0 // Ini memungkinkan dropdown muncul tanpa input
            });

            $('#barang').select2({
                placeholder: "Pilih Barang",
                dropdownParent: $('#modalAddBarangMasuk'),
                ajax: {
                    url: "{{ route('get.barang') }}",
                    delay: 250,
                    dataType: 'JSON',
                    processResults: function (response) {
                        return {
                            results: $.map(response.data, function(item) {
                                return {
                                    text: item.nama_barang,
                                    id: item.id_barang
                                }
                            })
                        }
                    },
                    cache: true
                },
                minimumInputLength: 0 // Ini memungkinkan dropdown muncul tanpa input
            });
        })
        $('body').on('click', '.btn-edit', function() {
            var id = $(this).attr("dataId");
            $('#id_barang').val(id)

            $.get("{{ route('data.barang.edit', ['id' => ':id']) }}".replace(':id', id), function (data) {
                $('#modalAddBarangMasuk').modal('show')

                $('#kode_barcode').val(data.item.kode_barcode)
                $('#nama_barang').val(data.item.nama_barang)
                var newOption = new Option(data.item.desc_satuan, data.item.id_satuan, true, true);
                $('#satuan').append(newOption).trigger('change');
                var newOption2 = new Option(data.item.nama_kategori, data.item.id_kategori, true, true);
                $('#kategori').append(newOption2).trigger('change');
                var newOption3 = new Option(data.item.desc_merek, data.item.id_merek, true, true);
                $('#merek').append(newOption3).trigger('change');
                $('#stok_min').val(data.item.stok_min)
                $('#stok_max').val(data.item.stok_max)
                $('#harga_pokok').val(data.item.harga_pokok)
                $('#harga_jual').val(data.item.harga_jual)
                $('#margin').val(data.item.margin)
                $('#harga_jual_default').val(data.item.harga_jual_default)
            })
        })
    </script>
@endpush