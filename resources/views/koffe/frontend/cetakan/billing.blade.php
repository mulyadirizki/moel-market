
<!DOCTYPE html>
<html lang="en">
  <!-- [Head] start -->
  <head>
    <title>MA-Pos</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
    <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
    <meta name="author" content="CodedThemes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('meta')

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ url('assets/images/favicon.svg') }}" type="image/x-icon"> <!-- [Google Font] Family -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ url('assets/fonts/tabler-icons.min.css') }}" >
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ url('assets/fonts/feather.css') }}" >
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ url('assets/fonts/fontawesome.css') }}" >
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ url('assets/fonts/material.css') }}" >
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ url('assets/css/style.css') }}" id="main-style-link" >
    <link rel="stylesheet" href="{{ url('assets/css/style-preset.css') }}" >

    <!-- data tables css -->
    <link rel="stylesheet" href="{{ url('assets/css/plugins/dataTables.bootstrap5.min.css') }}">
    <link href="{{ url('assets/css/toast.style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('assets/css/toast.style.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        .dashed-line {
            border-top: 1px dashed #000;
            /* margin: 10px 0; */
        }
    </style>
  </head>
  <!-- [Head] end -->

    <!-- [Body] Start -->
    <body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light" data-pc-direction="ltr">
        <!-- [ Pre-loader ] start -->
        <div class="loader-bg">
            <div class="loader-track">
                <div class="loader-fill"></div>
            </div>
        </div>
        <!-- [ Pre-loader ] End -->

        <!-- [ Main Content ] start -->
        <div class="pc-container" style="margin-top: -60px;">
            <div class="pc-content">

                    <!-- [ Main Content ] start -->
                <div class="row">
                    <!-- [ sample-page ] start -->
                    <div class="col-sm-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4">
                                    <div class="media align-items-start">
                                        <div class="text-center">
                                            <div class="chat-avtar d-inline-flex mx-auto">
                                                <img class="bg-light rounded img-fluid wid-60" src="{{ url('assets/images/store/koffea.jpeg') }}" alt="User image">
                                            </div>
                                            <h5 class="mt-3">KOFFEA</h5>
                                            <p class="text-muted text-xsm">RS Universitas Andalas, Kota Padang, Sumatera Barat, 25161 </p>
                                            <p class="text-muted text-xsm" style="margin-top: -10px;"><b>081122334455</b></p>
                                        </div>
                                    </div>
                                    <div class="dashed-line" style="margin-top: -10px;"></div>
                                    <div style="margin-top: 20px;">
                                        <div class="row" class="row" style="margin-top: -15px;">
                                            <div class="col">
                                                <div class="float-end">
                                                    <p class="text-muted text-xsm">{{ $datPenjualan->no_nota }}</p>
                                                </div>
                                                <p class="text-muted text-xsm">No Nota</p>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col">
                                                <div class="float-end">
                                                    <p class="text-muted text-xsm">{{ date("d M Y H:i", strtotime($datPenjualan->tgl_nota)); }}</p>
                                                </div>
                                                <p class="text-muted text-xsm">Waktu</p>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col">
                                                <div class="float-end">
                                                    <p class="text-muted text-xsm">{{ $datPenjualan->nama }}</p>
                                                </div>
                                                <p class="text-muted text-xsm">Kasir</p>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col">
                                                <div class="float-end">
                                                    <?php
                                                        $random_string = '';
                                                        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

                                                        for ($i = 0; $i < 10; $i++) {
                                                            $random_string .= $characters[mt_rand(0, strlen($characters) - 1)];
                                                        }
                                                    ?>
                                                    <p class="text-muted text-xsm">{{ $random_string }}</p>
                                                </div>
                                                <p class="text-muted text-xsm">No Kuitansi</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dashed-line" style="margin-top: -10px;"></div>
                                    <div style="margin-top: 20px;">
                                        @foreach($result as $category => $items)
                                            <div class="row" style="margin-top: -15px; padding-bottom: 12px;">
                                                <div class="col">
                                                    <span class="text-muted text-sm"><b>{{ $category }}</b></span>
                                                </div>
                                            </div>
                                            @foreach($items as $itm)
                                                <div class="row" style="margin-top: -18px; margin-left: 1px;">
                                                    <div class="col">
                                                        <span class="text-muted text-xsm">{{ $itm->item_name }}</span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col" style="margin-left: 13px;">
                                                        <div class="float-end">
                                                            <p class="text-muted text-xsm">Rp. {{ number_format($itm->sub_total) }}</p>
                                                        </div>
                                                        <p class="text-muted text-xsm">{{ $itm->qty }} x <span style="margin-left: 10px;">Rp. {{ number_format($itm->harga_peritem) }}</span></p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                    <div class="dashed-line" style="margin-top: -10px;"></div>
                                    <div style="margin-top: 20px;">
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col">
                                                <div class="float-end">
                                                    <p class="text-muted text-xsm">Rp. {{ number_format($datPenjualan->total) }}</p>
                                                </div>
                                                <p class="text-muted text-xsm">Subtotal</p>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col">
                                                <div class="float-end">
                                                    <p class="text-muted text-xsm">Rp. {{ number_format($datPenjualan->total) }}</p>
                                                </div>
                                                <p class="text-muted text-xsm">Total Tagihan</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dashed-line" style="margin-top: -10px;"></div>
                                    <div style="margin-top: 20px;">
                                        @if($datPenjualan->payment_method != 'Pay Later')
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col">
                                                <div class="float-end">
                                                    <p class="text-muted text-xsm">Rp. {{ number_format($datPenjualan->uang_bayar) }}</p>
                                                </div>
                                                <p class="text-muted text-xsm">{{ $datPenjualan->payment_method }}</p>
                                            </div>
                                        </div>
                                        @else
                                            <div class="row" style="margin-top: -15px;">
                                                <div class="col">
                                                    <div class="float-end">
                                                        <p class="text-muted text-xsm">Rp. 0</p>
                                                    </div>
                                                    <p class="text-muted text-xsm">{{ $datPenjualan->payment_method }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col">
                                                <div class="float-end">
                                                    <p class="text-muted text-xsm">Rp. {{ number_format($datPenjualan->uang_bayar) }}</p>
                                                </div>
                                                <p class="text-muted text-xsm">Total Bayar</p>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col">
                                                <div class="float-end">
                                                    <p class="text-muted text-xsm">Rp. {{ number_format($datPenjualan->uang_kembali) }}</p>
                                                </div>
                                                <p class="text-muted text-xsm">Kembali</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="width: 100%">
                                        <div class="text-center">
                                            <p class="text-muted text-xsm">Powered By MA POS</p>
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
        </div>

    <script src="{{ url('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ url('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ url('assets/js/pcoded.js') }}"></script>
    <script src="{{ url('assets/js/plugins/feather.min.js') }}"></script>
    <script>layout_change('light');</script>
    <script>change_box_container('false');</script>
    <script>layout_rtl_change('false');</script>
    <script>preset_change("preset-1");</script>
    <script>font_change("Public-Sans");</script>


    @include('koffe.frontend.partials.custom')
    <script src="{{ url('assets/js/layout-horizontal.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ url('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ url('assets/js/toast.script.js') }}"></script>
    @stack('script')
  </body>
  <!-- [Body] end -->
</html>
