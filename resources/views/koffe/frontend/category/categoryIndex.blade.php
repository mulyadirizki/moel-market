@extends('koffe.frontend.default')

@push('meta')
    <meta name="author" content="HPV">
    <meta name="keywords" content="">
    <meta name="description" content=""/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <!-- [ Main Content ] start -->
    <div class="pc-content">
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- [ sample-page ] start -->
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5>Manage Categories</h5>
                        <button type="button" class="btn btn-shadow btn-link" onclick="addData()"><i class="ti ti-plus me-1"></i>Create</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dt-responsive">
                            <table id="data-category" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Categories</th>
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
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
  <!-- [ Main Content ] end -->

    <!-- Modal -->
    <div class="modal fade" id="modalAddCategory" tabindex="-1" role="dialog" aria-labelledby="modalAddCategoryTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label" for="categoryname">Category Name</label>
                                        <input type="text" class="form-control" placeholder="Enter Category Name" id="categoryname">
                                    </div>
                                </div>
                            </div>
                        </div>
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

        function initializeDataTable() {
            var table = $('#data-category').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                ajax: "{{ route('manage.category') }}",
                columns: [
                    { data: 'category_name', name: 'category_name' },
                    { data: 'action', name: 'action', orderable: true, searchable: true }
                ]
            });
        }

        $(document).ready(function() {
            initializeDataTable();
        });

        function addData() {
            $('#modalAddCategory').modal('show')
        }

        function resetInputData() {
            $('#category_name').val('');
        }

        function save() {
            var categoryname = $('#categoryname').val();
            var token = $("meta[name='csrf-token']").attr("content");

            console.log(categoryname);

            $.ajax({
                type: "POST",
                url: "{{ route('manage.category.add') }}",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify({
                    "categoryname": categoryname,
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
                            resetInputData();
                            $('#modalAddCategory').modal('hide');
                            var table = $('#data-category').DataTable();
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
                    }
                }
            })
        }

        $('body').on('click', '.btn-delete', function () {

            var id = $(this).attr("dataId");

            $.ajax({
                type: "delete",
                url: `manage-category/delete/${id}`,
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                    $.Toast("Success", "Data deleted successfully", "success", {
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
                    var table = $('#data-category').DataTable();
                    table.ajax.reload();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });
    </script>
@endpush