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
                            <li class="breadcrumb-item" aria-current="page">Data Supplier</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Data Supplier</h2>
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
                        <small>Data Supplier</small>
                    </div>
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table id="data-satuan" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Nama Supplier</th>
                                        <th>Alamat</th>
                                        <th>No HP</th>
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
    <div class="modal fade" id="modalAddSupplier" tabindex="-1" role="dialog" aria-labelledby="modalAddSupplierTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Satuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <input type="hidden" id="id_supplier" value="">
                            <div class="col-lg-6">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="nama_supplier">Nama Supplier <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" placeholder="Nama Supplier" id="nama_supplier" nama="nama_supplier">
                                    <small class="form-text text-muted">Please enter Nama Supplier</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="no_hp">No HP :</label>
                                    <input type="email" class="form-control" placeholder="No HP" id="no_hp" nama="no_hp">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="alamat">Alamat :</label>
                                    <textarea class="form-control" name="alamat"  id="alamat" rows="4"></textarea>
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
            table = $('#data-satuan').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('data.supplier') }}",
                columns: [
                    { data: 'nama_supplier', name: 'nama_supplier' },
                    { data: 'alamat', name: 'alamat' },
                    { data: 'no_hp', name: 'no_hp' },
                    { data: 'action', name: 'action', orderable: true, searchable: true }
                ]
            });

        }

        function addData() {
            $('#modalAddSupplier').modal('show')
        }

        function save() {
            var id_supplier = $('#id_supplier').val();
            var nama_supplier = $('#nama_supplier').val();
            var no_hp = $('#no_hp').val();
            var alamat = $('#alamat').val();
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                type: "POST",
                url: "{{ route('data.supplier.add') }}",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify({
                    "id_supplier": id_supplier,
                    "nama_supplier": nama_supplier,
                    "no_hp": no_hp,
                    "alamat": alamat,
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
                            $('#modalAddSupplier').modal('hide');
                            table.ajax.reload();
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

        $('body').on('click', '.btn-edit', function() {
            var id = $(this).attr("dataId");
            $('#id_supplier').val(id)

            $.get("{{ route('data.supplier.edit', ['id' => ':id']) }}".replace(':id', id), function (data) {
                $('#modalAddSupplier').modal('show')

                $('#nama_supplier').val(data.item.nama_supplier)
                $('#no_hp').val(data.item.no_hp)
                $('#alamat').val(data.item.alamat)
            })
        })

        $('body').on('click', '.btn-delete', function () {

            var id_supplier = $(this).attr("dataId");
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                type: "delete",
                url: "{{ route('data.supplier.delete', ['id' => ':id']) }}".replace(':id', id_supplier),
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
        })
    </script>
@endpush