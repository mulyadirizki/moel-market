
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

        <!-- [ Sidebar Menu ] start -->
            @include('koffe.frontend.partials.sidebar')
        <!-- [ Sidebar Menu ] end -->

        <!-- [ Header Topbar ] start -->
            @include('koffe.frontend.partials.header')
        <!-- [ Header ] end -->



      <!-- [ Main Content ] start -->
      <div class="pc-container">
          @yield('content')
      </div>
      <!-- [ Main Content ] end -->

    <!-- footer -->
      @include('koffe.frontend.partials.footer')
    <!-- endfooter -->
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
