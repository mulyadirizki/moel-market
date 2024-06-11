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
                            <li class="breadcrumb-item" aria-current="page">Data Karyawan</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Data Satuan</h2>
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
                        <small>Data Satuan</small>
                    </div>
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table id="data-satuan" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Nama Satuan</th>
                                        <th>Aliase Satuan</th>
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
    <div class="modal fade" id="modalAddSatuan" tabindex="-1" role="dialog" aria-labelledby="modalAddSatuanTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Satuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <input type="hidden" id="id_satuan" value="">
                            <div class="col-lg-6">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="desc_satuan">Nama Satuan <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" placeholder="Pieces" id="desc_satuan" nama="desc_satuan">
                                    <small class="form-text text-muted">Please enter Nama Satuan</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group input-group-sm">
                                    <label class="form-label" for="aliase_satuan">Aliase Satuan :</label>
                                    <input type="email" class="form-control" placeholder="Pcs" id="aliase_satuan" nama="aliase_satuan">
                                    <small class="form-text text-muted">Optional</small>
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
                ajax: "{{ route('data.satuan') }}",
                columns: [
                    { data: 'desc_satuan', name: 'desc_satuan' },
                    { data: 'aliase_satuan', name: 'aliase_satuan' },
                    { data: 'action', name: 'action', orderable: true, searchable: true }
                ]
            });

        }

        function addData() {
            $('#modalAddSatuan').modal('show')
        }

        function save() {
            var id_satuan = $('#id_satuan').val();
            var desc_satuan = $('#desc_satuan').val();
            var aliase_satuan = $('#aliase_satuan').val();
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                type: "POST",
                url: "{{ route('data.satuan.add') }}",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify({
                    "id_satuan": id_satuan,
                    "desc_satuan": desc_satuan,
                    "aliase_satuan": aliase_satuan,
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
                            $('#modalAddSatuan').modal('hide');
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
            $('#id_satuan').val(id)

            $.get("{{ route('data.satuan.edit', ['id' => ':id']) }}".replace(':id', id), function (data) {
                $('#modalAddSatuan').modal('show')

                $('#desc_satuan').val(data.item.desc_satuan)
                $('#aliase_satuan').val(data.item.aliase_satuan)
            })
        })

        $('body').on('click', '.btn-delete', function () {

            var id_satuan = $(this).attr("dataId");
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                type: "delete",
                url: "{{ route('data.satuan.delete', ['id' => ':id']) }}".replace(':id', id_satuan),
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