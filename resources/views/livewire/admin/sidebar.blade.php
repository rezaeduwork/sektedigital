<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="index3.html" class="brand-link">
    {{-- <img src="{{ url('/asset-admin') }}/img/AdminLTELogo.png" alt="AdminLTE Logo"
      class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
    <span class="brand-text font-weight-light ml-4">Administrator</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{url('admin')}}" class="nav-link" wire:navigate>
            <i class="fas fa-tachometer-alt nav-icon"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item menu-open">  <!-- Add 'menu-open' here -->
          <a href="#" class="nav-link">  <!-- Add 'active' here -->
            <i class="fas fa-warehouse nav-icon"></i>
            <p>
              Warehouse
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview" style="display: block;">  <!-- Change 'display: none;' to 'display: block;' -->
            <li class="nav-item">
              <a href="{{url('admin/category')}}" class="nav-link" wire:navigate>
                <i class="far fa-circle nav-icon invisible"></i>
                <p>Category</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{url('admin/product')}}" class="nav-link" wire:navigate>
                <i class="far fa-circle nav-icon invisible"></i>
                <p>Product</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{url('admin/account')}}" class="nav-link" wire:navigate>
                <i class="nav-icon far fa-circle invisible"></i>
                <p>
                  Account
                </p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item menu-open">
          <a href="#" class="nav-link">
            <i class="fas fa-money-bill-wave nav-icon"></i>
            <p>
              Finance
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="pages/tables/simple.html" class="nav-link">
                <i class="far fa-circle nav-icon invisible"></i>
                <p>Transaction</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="pages/tables/simple.html" class="nav-link">
                <i class="far fa-circle nav-icon invisible"></i>
                <p>Withdrawal Request</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="pages/tables/simple.html" class="nav-link">
                <i class="far fa-circle nav-icon invisible"></i>
                <p>Member Balance</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="pages/tables/simple.html" class="nav-link">
                <i class="far fa-circle nav-icon invisible"></i>
                <p>Store Balance</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="{{url('logout')}}" class="nav-link">
            <i class="nav-icon fas fa-door-open"></i>
            <p>
              Logout
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
