
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    @stack('css')
  </head>

    <body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light" data-pc-direction="ltr">
      <div class="loader-bg">
        <div class="loader-track">
          <div class="loader-fill"></div>
        </div>
      </div>

      <div class="pc-navbar"></div>
      <div class="navbar-content"></div>

      <div class="pc-container-fluid">
        @yield('content')
      </div>

      @include('market.frontend.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/simplebar.min.js') }}"></script>
    <!-- <script src="{{ url('assets/js/plugins/bootstrap.min.js') }}"></script> -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="{{ url('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ url('assets/js/pcoded.js') }}"></script>
    <script src="{{ url('assets/js/plugins/feather.min.js') }}"></script>
    <script>layout_change('light');</script>
    <script>change_box_container('false');</script>
    <script>layout_rtl_change('false');</script>
    <script>preset_change("preset-1");</script>
    <script>font_change("Public-Sans");</script>
    <!-- datatable Js -->
    <script src="{{ url('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ url('assets/js/toast.script.js') }}"></script>

    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/colreorder/1.7.0/js/dataTables.colReorder.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>

    <script src="{{ url('assets/js/autocomplete.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>

    @stack('script')
  </body>
  <!-- [Body] end -->
</html>
