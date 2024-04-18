@extends('koffe.frontend.default')

@section('content')
    <!-- [ Main Content ] start -->
    <div class="pc-content">
      <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('kasir') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Koffea</a></li>
                            <li class="breadcrumb-item" aria-current="page">Add New Item</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Add New Item</h2>
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
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="itemname">Item Name</label>
                                            <input type="text" class="form-control" placeholder="Enter Item Name" id="itemname">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="category">Category</label>
                                            <select class="form-select" id="category">
                                                <option selected value="1">Uncategorized</option>
                                                @foreach($category as $ct)
                                                    <option value="{{ $ct->id_category }}">{{ $ct->category_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4" id="priceitem">
                                        <div class="form-group">
                                            <label class="form-label" for="price">Price</label>
                                            <input type="text" class="form-control" placeholder="Enter Price" id="price">
                                        </div>
                                    </div>
                                    <div class="col-lg-4" id="skuitem">
                                        <div class="form-group">
                                            <label class="form-label" for="sku">SKU</label>
                                            <input type="text" class="form-control" placeholder="Enter SKU" id="sku">
                                        </div>
                                    </div>
                                    <div class="col-lg-4" id="addvariantbtn">
                                        <label class="form-label">&nbsp;</label><br>
                                        <button class="btn btn-primary btn-addvariant" style="width:300px;" onclick="addVarianModal()">Add Variant</button>
                                    </div>
                                    <div class="text-end btn-page mb-0 mt-4">
                                        <button class="btn btn-outline-secondary" onclick="btncancelItem()">Cancel</button>
                                        <button class="btn btn-primary" onclick="saveItem()">Save</button>
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
    </div>
    <!-- [ Main Content ] end -->

    <!-- Modal -->
    <div class="modal fade" id="modalAddVariant" tabindex="-1" role="dialog" aria-labelledby="modalAddVariantTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Variant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label" for="variantname">Variant Name</label>
                                        <input type="text" class="form-control" placeholder="Enter Variant Name" id="variantname">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label" for="price">Price</label>
                                        <input type="text" class="form-control" placeholder="Enter Price" id="pricevariant">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label" for="sku">SKU</label>
                                        <input type="text" class="form-control" placeholder="Enter SKU" id="skuvariant">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary btn-submit" onclick="addVariant()"><i class="ti ti-plus me-1"></i> Tambah</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive dt-responsive">
                            <table id="data-variant" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Variant</th>
                                        <th>Price</th>
                                        <th>SKU</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-outline-secondary" onclick="batalVariant()">Batal</button>
                    <button type="button" class="btn btn-primary btn-submit" onclick="saveVariant()">Simpan</button>
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

        function initializeDataVariant() {
            var storedVariants = sessionStorage.getItem('storedVariants');

            if (storedVariants && JSON.parse(storedVariants).length > 0) {
                $('#priceitem, #skuitem').hide();
                var count = JSON.parse(storedVariants).length;
                $('.btn-addvariant').text(+ count + ' ' + 'Variants ');
            } else {
                $('#priceitem, #skuitem').show();
                $('.btn-addvariant').text('Add Variant');
            }
        }

        function initializeDataVariantTable() {
            var storedVariants = sessionStorage.getItem('storedVariants');
            if (storedVariants) {
                var variants = JSON.parse(storedVariants);
                for (var i = 0; i < variants.length; i++) {
                    var variant = variants[i];
                    var html = `<tr>
                                    <td>${variant.variantname}</td>
                                    <td>${variant.pricevariant}</td>
                                    <td>${variant.skuvariant}</td>
                                    <td>
                                        <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                            <a href="javascript:void(0)" class="avtar avtar-xs btn-link-danger btn-delete-variant" data-toggle="tooltip">
                                                <i class="ti ti-trash f-18"></i>
                                            </a>
                                        </li>
                                    </td>
                                </tr>`;
                    $('#tbody').append(html);
                }
            } else {
                $('#tbody').empty();
            }
        }

        $(document).ready(function() {
            initializeDataTable();
            initializeDataVariant();
            initializeDataVariantTable();
        });

        function addVarianModal() {
            $('#modalAddVariant').modal('show')
        }

        function batalVariant() {
            $('#modalAddVariant').modal('hide')
            sessionStorage.clear();
            initializeDataVariant();
            initializeDataVariantTable();
        }

        function resetInputData() {
            $('#variantname').val('');
            $('#pricevariant').val('');
            $('#skuvariant').val('');
        }

        function risetInputItem() {
            $('#itemname').val('');
            $('#category').val('');
            $('#price').val('');
            $('#sku').val('');
        }

        function addVariant(){
            var variantname     = $('#variantname').val();
            var pricevariant    = $('#pricevariant').val();
            var skuvariant      = $('#skuvariant').val();

            // let changeFormat = data.harga_jual_default.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            var html = `<tr>
                <td>${variantname}</td>
                <td>${pricevariant}</td>
                <td>${skuvariant}</td>
                <td><li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                        <a href="javascript:void(0)" class="avtar avtar-xs btn-link-danger btn-delete-variant" data-toggle="tooltip">
                            <i class="ti ti-trash f-18"></i>
                        </a>
                    </li>
                </td>
                </tr>'`;
            $('#tbody').append(html)
            resetInputData();
        }

        $(document).on('click', '.btn-delete-variant', function() {
            var rowToDelete = $(this).closest('tr');

            rowToDelete.remove();
        });

        function saveVariant() {
            var tableData = [];
            $('#tbody tr').each(function() {
                var variant = {};

                variant.variantname = $(this).find('td:eq(0)').text();
                variant.pricevariant = $(this).find('td:eq(1)').text();
                variant.skuvariant = $(this).find('td:eq(2)').text();

                tableData.push(variant);
            });

            sessionStorage.setItem('storedVariants', JSON.stringify(tableData));
            $('#modalAddVariant').modal('hide');
            initializeDataVariant();

            return tableData;
        }

        function saveItem() {
            var itemname = $('#itemname').val();
            var category = $('#category').val();
            var price = $('#price').val();
            var sku = $('#sku').val();
            var token = $("meta[name='csrf-token']").attr("content");

            var storedVariants = sessionStorage.getItem('storedVariants');

            var dataObj;
            if (storedVariants && JSON.parse(storedVariants).length > 0) {
                var variants = JSON.parse(storedVariants);

                dataObj = {
                    "itemname": itemname,
                    "category": category,
                    "variantname" : true,
                    "dataArr": []
                };

                variants.forEach(function(variant) {
                    var variantData = {
                        "price": variant.pricevariant,
                        "sku": variant.skuvariant,
                        "variantname": variant.variantname
                    };
                    dataObj.dataArr.push(variantData);
                });
            } else {
                dataObj = {
                    "itemname"  : itemname,
                    "category"  : category,
                    "variantname" : false,
                    "dataArr"   : [
                        { "price" : price, "sku" : sku, }
                    ]
                }
                console.log(dataObj)
            }

            $.ajax({
                type: "POST",
                url: "{{ route('create.item.add') }}",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify({
                    dataObj,
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
                            sessionStorage.removeItem('storedVariants');
                            risetInputItem();
                            initializeDataVariant();
                            window.location = "{{ route('category.item', ['id' => ':id']) }}".replace(':id', category);
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

        function btncancelItem() {
            sessionStorage.clear();
            initializeDataVariant();
            initializeDataVariantTable();
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