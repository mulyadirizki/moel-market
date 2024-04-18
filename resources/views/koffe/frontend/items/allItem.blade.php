@extends('koffe.frontend.default')

@section('content')
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Koffea</a></li>
                            <li class="breadcrumb-item" aria-current="page" id="category1"></li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0" id="category2"></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row">
            <div id="items-container">
                <!-- Items will be appended here -->
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalAddVariant" tabindex="-1" role="dialog" aria-labelledby="modalAddVariantTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="variantNameItem"></h5>
                    <span class="modal-subtitle" id="countNameItem" style="margin-left: 15px;"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label" for="variantname">Variant | <span>Choose One</span></label><br>
                                        <div class="card-body pc-component" id="menuitemvariant"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label" for="qty">Quantity</label>
                                    <div class="input-group mb-3">
                                        <button class="btn btn-outline-secondary btn-minuse" type="button" id="button-addon1">-</button>
                                        <input type="text" class="form-control no-padding add-color text-center" maxlength="3" value="1" id="qty">
                                        <button class="btn btn-outline-secondary btn-pluss" type="button" id="button-addon1">+</button>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label" for="notes">Notes</label>
                                    <textarea class="form-control" id="notes"></textarea>
                                </div>
                                <input type="hidden" id="id_item">
                                <input type="hidden" id="item_name">
                                <input type="hidden" id="price">
                                <input type="hidden" id="sku">
                                <input type="hidden" id="variant_name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-outline-secondary" onclick="batalVariant()">Batal</button>
                    <button type="button" class="btn btn-primary btn-submit" onclick="saveOrder()">Add</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>

        getAllItem = () => {
            var id = window.location.pathname.split('/').pop();
            $.get("{{ route('all.item') }}", function (items) {
                var uniqueNames = {};
                var uniqueItems = items.data.filter(function(item) {
                    if (!uniqueNames[item.item_name]) {
                        uniqueNames[item.item_name] = true;
                        return true;
                    }
                    return false;
                });
                uniqueItems.forEach(function(itm) {
                    var count = items.data.filter(function(item) {
                        return item.item_name === itm.item_name;
                    }).length;

                    var itemElement = '<div class="col-sm-6 col-xl-3">';
                    itemElement += '<div class="card product-card">';
                    itemElement += '<div style="padding: 10px;">';
                    itemElement += '<div class="row align-items-center">';
                    itemElement += '<div class="col-auto p-r-0">';
                    itemElement += '<div class="u-img">';
                    itemElement += '<img src="https://png.pngtree.com/template/20191104/ourmid/pngtree-kf-letter-logo-image_326979.jpg" alt="user image" class="" style="width: 50px">';
                    itemElement += '</div>';
                    itemElement += '</div>';
                    itemElement += '<div class="col">';
                    itemElement += '<a href="javascript:void(0);" onclick="choseVariant(\'' + itm.id_item + '\')">';
                    itemElement += '<h5 class="m-b-5">' + itm.item_name + '</h5>';
                    itemElement += '</a>';
                    itemElement += '</div>';
                    itemElement += '<div class="col-auto">';
                    if (count > 1) {
                        itemElement += '<span class="pc-arrow">' + count + ' Prices</span>';
                    } else {
                        itemElement += '<span class="pc-arrow">Rp. ' + itm.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' </span>';
                    }
                    itemElement += '</div>';
                    itemElement += '</div>';
                    itemElement += '</div>';
                    itemElement += '</div>';
                    itemElement += '</div>';

                    $('#items-container').append(itemElement);
                    $('#category1').text(itm.category_name);
                    $('#category2').text(itm.category_name);
                });
            });
        }

        function choseVariant(id_item) {
            $('#menuitemvariant').empty();
            $('#modalAddVariant').modal('show');
            $.get("{{ route('varian.item', ['id' => ':id']) }}".replace(':id', id_item), function (items) {
                items.data.forEach(function(itm) {
                    var count = items.data.filter(function(item) {
                        return item.item_name === itm.item_name;
                    }).length;

                    var itemName = items.data.filter(function(item) {
                        return item.item_name === itm.item_name;
                    });

                    if (itemName.length > 0) {
                        var firstItemName = itemName[0].item_name;
                        $('#variantNameItem').text(firstItemName);
                        $('#countNameItem').text(count + ' Variant');

                        var itemElement = '<div class="btn-group" role="group" style="width: 320px; margin-bottom: 10px;">';
                        itemElement += '<input type="radio" class="btn-check btn-variant" id="'+ itm.id_variant +'" name="btnvarian">';
                        if (itm.variant_name != null) {
                            itemElement += '<label class="btn btn-light-primary" for="'+ itm.id_variant +'"><span style="text-align: left;">'+ itm.variant_name + '</span>' + ' Rp. ' + itm.price + '</label>';
                        } else {
                            itemElement += '<label class="btn btn-light-primary" for="'+ itm.id_variant +'"><span style="text-align: left;">'+ itm.item_name + '</span>' + ' Rp. ' + itm.price + '</label>';
                        }
                        itemElement += '</div><br>';

                        $('#menuitemvariant').append(itemElement);
                    } else {
                        $('#variantNameItem').text("Item tidak ditemukan");
                    }
                });

                $('.btn-variant').change(function() {
                    if ($(this).is(':checked')) {
                        var id_variant_dipilih = $(this).attr('id');

                        var data_dipilih = items.data.find(function(item) {
                            return item.id_variant === id_variant_dipilih;
                        });

                        if (data_dipilih.id_variant) {
                            $('#id_item').val(data_dipilih.item_id);
                            $('#item_name').val(data_dipilih.item_name);
                            $('#price').val(data_dipilih.price);
                            $('#sku').val(data_dipilih.sku);
                            $('#variant_name').val(data_dipilih.variant_name);

                        } else {
                            console.log("Data tidak ditemukan untuk ID variant:", id_variant_dipilih);
                        }
                    }
                });
            });
        }

        $(".btn-minuse").on("click", function() {
            var $qty = $(this).next('.form-control');
            var currentVal = parseInt($qty.val());
            if (!isNaN(currentVal) && currentVal > 1) {
                $qty.val(currentVal - 1);
            }
        });

        $(".btn-pluss").on("click", function() {
            var $qty = $(this).prev('.form-control');
            var currentVal = parseInt($qty.val());
            if (!isNaN(currentVal)) {
                $qty.val(currentVal + 1);
            }
        });

        function saveOrder() {
            var id_variant = $('input[name="btnvarian"]:checked').attr('id');
            var qty = $('#qty').val();
            var notes = $('#notes').val();
            var id_item = $('#id_item').val();
            var item_name = $('#item_name').val();
            var price = $('#price').val();
            var sku = $('#sku').val();
            var variant_name = $('#variant_name').val();

            if (id_variant != undefined) {
                var OrderTemp = {
                    "item_name": item_name,
                    "item_id": id_item,
                    "id_variant": id_variant,
                    "variant_name": variant_name,
                    "price": price,
                    "sku": sku,
                    "qty": qty,
                    "notes": notes
                };

                var dataOrderTemp = JSON.parse(sessionStorage.getItem('dataOrderTemp'));

                if (dataOrderTemp) {
                    dataOrderTemp.push(OrderTemp);
                } else {
                    dataOrderTemp = [OrderTemp];
                }

                sessionStorage.setItem('dataOrderTemp', JSON.stringify(dataOrderTemp));

                $.Toast("Berhasil", 'Data berhasil ditambahkan', "success", {
                    has_icon:true,
                    has_close_btn:true,
                    stack: true,
                    fullscreen:false,
                    timeout: 1000,
                    sticky:false,
                    has_progress:true,
                    rtl:false,
                    position_class: "toast-top-right",
                    width: 150,
                });
                $('#modalAddVariant').modal('hide');
                countCart();
            } else {
                $.Toast("Failed", 'No data selected', "error", {
                    has_icon:true,
                    has_close_btn:true,
                    stack: true,
                    fullscreen:false,
                    timeout: 1000,
                    sticky:false,
                    has_progress:true,
                    rtl:false,
                    position_class: "toast-top-right",
                    width: 150,
                });
            }
        }

        $(document).ready(function() {
            getAllItem();
        });
    </script>
@endpush