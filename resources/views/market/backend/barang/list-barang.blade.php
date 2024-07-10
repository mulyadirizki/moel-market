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
                            <li class="breadcrumb-item" aria-current="page">Data Barang</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Data Barang</h2>
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
                        <small>Data Barang</small>
                    </div>
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table id="data-barang" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barcode</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th>Merek</th>
                                        <th>Stok Min</th>
                                        <th>Stok Max</th>
                                        <th>Harga Pokok</th>
                                        <th>Harga Jual</th>
                                        <th>Margin (%)</th>
                                        <th>Harga Jual x Margin</th>
                                        <th>Action</th>
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
    <div class="modal fade" id="modalAddBarang" tabindex="-1" role="dialog" aria-labelledby="modalAddBarangTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <input type="hidden" id="id_barang" value="">
                            <div class="col-lg-2">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="kode_barcode">Kode Barcode</label>
                                    <input type="text" class="form-control" placeholder="Nama Barang" id="kode_barcode" nama="kode_barcode">
                                    <small class="form-text text-muted">Scan barcode di barang jika ada</small>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="nama_barang">Nama Barang <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" placeholder="Nama Barang" id="nama_barang" nama="nama_barang">
                                    <small class="form-text text-muted">Please enter Nama Barang</small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="satuan">Satuan <span style="color: red;">*</span></label>
                                    <select class="form-select" id="satuan" name="satuan" style="width: 100%;" value=""></select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="kategori">Kategori <span style="color: red;">*</span></label>
                                    <select class="form-select" id="kategori" name="kategori" style="width: 100%;" value=""></select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="merek">Merek</label>
                                    <select class="form-select" id="merek" name="merek" style="width: 100%;" value=""></select>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="stok_min">Stok Min</label>
                                    <input type="number" class="form-control" placeholder="Stok Min" id="stok_min" nama="stok_min" value="20">
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="stok_max">Stok Max</label>
                                    <input type="number" class="form-control" placeholder="Stok Max" id="stok_max" nama="stok_max">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="harga_pokok">Harga Pokok <span style="color: red;">*</span></label>
                                    <input type="number" class="form-control" placeholder="Harga Pokok" id="harga_pokok" nama="harga_pokok">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="harga_jual">Harga Jual <span style="color: red;">*</span></label>
                                    <input type="number" class="form-control" placeholder="Harga Jual" id="harga_jual" nama="harga_jual">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="margin">Margin</label>
                                    <input type="number" class="form-control" placeholder="Margin" id="margin" nama="margin" value="20">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="harga_jual_default">Harga Jual x Margin <span style="color: red;">*</span></label>
                                    <input type="number" class="form-control" placeholder="Harga Jual x Margin" id="harga_jual_default" nama="harga_jual_default">
                                </div>
                            </div>
                        </div>
                    </form>
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
                    { data: 'stok_min', name: 'stok_min' },
                    { data: 'stok_max', name: 'stok_max' },
                    { data: 'harga_pokok', name: 'harga_pokok', render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') },
                    { data: 'harga_jual', name: 'harga_jual', render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') },
                    { data: 'margin', name: 'margin' },
                    { data: 'harga_jual_default', name: 'harga_jual_default', render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') },
                    { data: 'action', name: 'action', orderable: true, searchable: true }
                ]
            });

        }

        function addData() {
            $('#modalAddBarang').modal('show')
        }

        function save() {
            var token = $("meta[name='csrf-token']").attr("content");

            var dataSave = {
                id_barang: $('#id_barang').val(),
                kode_barcode: $('#kode_barcode').val(),
                nama_barang: $('#nama_barang').val(),
                satuan: $('#satuan').val(),
                kategori: $('#kategori').val(),
                merek: $('#merek').val(),
                stok_min: $('#stok_min').val(),
                stok_max: $('#stok_max').val(),
                harga_pokok: $('#harga_pokok').val(),
                harga_jual: $('#harga_jual').val(),
                margin: $('#margin').val(),
                harga_jual_default: $('#harga_jual_default').val()
            }

            console.log(dataSave)

            $.ajax({
                type: "POST",
                url: "{{ route('data.barang.add') }}",
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
                            $('#modalAddBarang').modal('hide');
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

            $('#kategori').select2({
                placeholder: "Pilih kategori",
                dropdownParent: $('#modalAddBarang'),
                ajax: {
                    url: "{{ route('get.kategori') }}",
                    delay: 250,
                    dataType: 'JSON',
                    processResults: function (response) {
                        return {
                            results: $.map(response.data, function(item) {
                                return {
                                    text: item.nama_kategori,
                                    id: item.id_kategori
                                }
                            })
                        }
                    },
                    cache: true
                },
                minimumInputLength: 0 // Ini memungkinkan dropdown muncul tanpa input
            });

            $('#satuan').select2({
                placeholder: "Pilih Satuan Barang",
                dropdownParent: $('#modalAddBarang'),
                ajax: {
                    url: "{{ route('get.satuan') }}",
                    delay: 250,
                    dataType: 'JSON',
                    processResults: function (response) {
                        return {
                            results: $.map(response.data, function(item) {
                                return {
                                    text: item.desc_satuan + ' (' + item.aliase_satuan + ')',
                                    id: item.id_satuan
                                }
                            })
                        }
                    },
                    cache: true
                },
                minimumInputLength: 0 // Ini memungkinkan dropdown muncul tanpa input
            });

            $('#merek').select2({
                placeholder: "Pilih Merek",
                dropdownParent: $('#modalAddBarang'),
                ajax: {
                    url: "{{ route('get.merek') }}",
                    delay: 250,
                    dataType: 'JSON',
                    processResults: function (response) {
                        return {
                            results: $.map(response.data, function(item) {
                                return {
                                    text: item.desc_merek,
                                    id: item.id_merek
                                }
                            })
                        }
                    },
                    cache: true
                },
                minimumInputLength: 0 // Ini memungkinkan dropdown muncul tanpa input
            });

            $("#harga_jual").change(function(event){

                var hrg_jual = $("#harga_jual").val();
                var margin = $("#margin").val();
                var margin = ((hrg_jual * margin) / 100);
                var hrg_fix = parseInt(hrg_jual) + parseInt (margin)
                var harga_jual_default = hrg_fix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $("#harga_jual_default").val(hrg_fix);
            });

            $("#margin").change(function(event){
                var hrg_jual = $("#harga_jual").val();
                var margin = $("#margin").val();
                var margin = ((hrg_jual * margin) / 100);
                var hrg_fix = parseInt(hrg_jual) + parseInt (margin)
                var harga_jual_default = hrg_fix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $("#harga_jual_default").val(hrg_fix);
            });
        })
        $('body').on('click', '.btn-edit', function() {
            var id = $(this).attr("dataId");
            $('#id_barang').val(id)

            $.get("{{ route('data.barang.edit', ['id' => ':id']) }}".replace(':id', id), function (data) {
                $('#modalAddBarang').modal('show')

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