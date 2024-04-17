<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gentelella Alela! | </title>

    <link rel="icon" href="{{ url('assets/images/favicon.svg') }}" type="image/x-icon">
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ url('assets/fonts/tabler-icons.min.css') }}" >
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ url('assets/fonts/feather.css') }}" >
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ url('assets/fonts/fontawesome.css') }}" >
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ url('assets/fonts/material.css') }}" >

    <!-- Bootstrap -->
    <link href="{{ url('assets/build/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <!-- <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet"> -->
    <!-- NProgress -->
    <link href="{{ url('assets/build/css/nprogress.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ url('assets/build/css/custom.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ url('assets/css/style.css') }}" id="main-style-link" >
    <link rel="stylesheet" href="{{ url('assets/css/style-preset.css') }}" >
    <meta name="csrf-token" content="{{ csrf_token() }}">
  </head>

  <body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <!-- page content -->
                <div role="main">
                    <div class="">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 ">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Registrasi <small>Sessions</small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <!-- Smart Wizard -->
                                        <div class="my-element">
                                            <p>Mulai Sekarang dan Tingkatkan Penjualan Anda</p>
                                            <div id="wizard" class="form_wizard wizard_horizontal">
                                                <ul class="wizard_steps">
                                                    <li>
                                                        <a href="#step-1">
                                                            <span class="step_no">1</span>
                                                            <span class="step_descr">
                                                                Step 1<br />
                                                                <small>Step 1 description</small>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#step-2">
                                                            <span class="step_no">2</span>
                                                            <span class="step_descr">
                                                                Step 2<br />
                                                                <small>Step 2 description</small>
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div id="step-1">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="nama">Nama Lengkap<span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <input type="text" id="nama" required="required" class="form-control  ">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="nohp">No HP <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <input type="text" id="nohp" name="nohp" required="required" class="form-control ">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="email" class="col-form-label col-md-3 col-sm-3 label-align">Email</label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <input id="email" class="form-control col" type="email" name="email">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="username" class="col-form-label col-md-3 col-sm-3 label-align">Username</label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <input id="username" class="form-control col" type="text" name="username">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="password" class="col-form-label col-md-3 col-sm-3 label-align">Password</label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <input id="password" class="form-control col" type="password" name="password">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="step-2">
                                                    <form>
                                                        <div class="form-group row">
                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="namatoko">Nama Toko <span class="required">*</span>
                                                            </label>
                                                            <div class="col-md-6 col-sm-6 ">
                                                                <input type="text" id="namatoko" required="required" class="form-control  ">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="tipebisnis">Tipe Bisnis <span class="required">*</span>
                                                            </label>
                                                            <div class="col-md-6 col-sm-6 ">
                                                                <select class="form-select" id="tipebisnis" name="tipebisnis">
                                                                    <option selected disabled>Pilih Tipe Bisnis</option>
                                                                    @foreach($tipe as $tb)
                                                                        <option value="{{ $tb->id }}">{{ $tb->nama_bisnis }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="alamat" class="col-form-label col-md-3 col-sm-3 label-align">Alamat</label>
                                                            <div class="col-md-6 col-sm-6 ">
                                                                <input id="alamat" class="form-control col" type="text" name="alamat">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        <!-- End SmartWizard Content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /page content -->
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ url('assets/build/js/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
   <script src="{{ url('assets/build/js/bootstrap.bundle.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ url('assets/build/js/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ url('assets/build/js/nprogress.js') }}"></script>
    <!-- jQuery Smart Wizard -->
    <script src="{{ url('assets/build/js/jquery.smartWizard.js') }}"></script>
    <!-- Custom Theme Scripts -->
    <script src="{{ url('assets/build/js/custom.min.js') }}"></script>

    <style>
        /* Gaya CSS default untuk elemen */
        .my-element {
            width: 600px; /* Lebar default */
        }

        /* Media query untuk layar dengan lebar maksimum 768px (tablet) */
        @media only screen and (max-width: 768px) {
            .my-element {
                width: 100%; /* Menyesuaikan lebar menjadi 100% */
            }
        }
    </style>

</body>
</html>