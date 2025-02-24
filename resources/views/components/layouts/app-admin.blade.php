<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Administrator</title>

  <link rel="stylesheet" href="{{url('asset-admin')}}/plugins/fontawesome-free/css/all.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{url('asset-admin')}}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('/asset-admin')}}/css/adminlte.min.css">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    .form-control {
      display: block;
      width: 100%;
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      font-weight: 400;
      line-height: 1.5;
      color: #495057;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid #ced4da;
      border-radius: 0.25rem;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    /* Focus Effect */
    .form-control:focus {
      border-color: #80bdff;
      outline: 0;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Disabled Input */
    .form-control:disabled {
      background-color: #e9ecef;
      opacity: 1;
    }

    /* Readonly Input */
    .form-control[readonly] {
      background-color: #f8f9fa;
      opacity
    }

  </style>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @livewireStyles

</head>

<body class="sidebar-mini layout-fixed" style="height: auto;">
  <div class="wrapper">

    <livewire:admin.navbar>

    <livewire:admin.sidebar>

    <!-- Content Wrapper. Contains page content -->
    {{$slot}}
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <!-- jQuery -->
  <script src="{{url('asset-admin')}}/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="{{url('asset-admin')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="{{url('asset-admin')}}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="{{url('/asset-admin')}}/js/adminlte.js"></script>

  <!-- PAGE PLUGINS -->
  <!-- jQuery Mapael -->
  <script src="{{url('asset-admin')}}/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
  <script src="{{url('asset-admin')}}/plugins/raphael/raphael.min.js"></script>
  <script src="{{url('asset-admin')}}/plugins/jquery-mapael/jquery.mapael.min.js"></script>
  <script src="{{url('asset-admin')}}/plugins/jquery-mapael/maps/usa_states.min.js"></script>
  <!-- ChartJS -->
  <script src="{{url('asset-admin')}}/plugins/chart.js/Chart.min.js"></script>

  <!-- AdminLTE for demo purposes -->
  <script src="{{url('/asset-admin')}}/js/demo.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="{{url('/asset-admin')}}/js/pages/dashboard2.js"></script>

  @livewireScripts
  <script>
    document.addEventListener('livewire:init', () => {
      Livewire.on('alert-success', (event) => {
        $('.modal').modal('hide')
        Swal.fire({
          title: "Success!",
          text: event.message,
          icon: "success"
        });
      });
      Livewire.on('reload', (event) => {
        $('.modal').modal('hide')
      });
    });
  </script>
</body>

</html>
