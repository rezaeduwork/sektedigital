<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard 2</title>

  <link rel="stylesheet" href="{{url('asset-admin')}}/plugins/fontawesome-free/css/all.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{url('asset-admin')}}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('/asset-admin')}}/css/adminlte.min.css">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
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
    });
  </script>
</body>

</html>
