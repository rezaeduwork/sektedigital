<div>
  @php
  $uniqKey = uniqid();
  @endphp
  <ul class="navbar-nav flex-column" id="sideNavbar{{$uniqKey}}">
    <li class="nav-item">
      <a class="nav-link" href="#" data-bs-toggle="collapse"
        data-bs-target="#navStore" aria-expanded="true" aria-controls="navStore">
        <div class="d-flex align-items-center">
          <span class="nav-link-icon"><i class="bi bi-house"></i></span>
          <span class="nav-link-text">Kios Saya</span>
        </div>
      </a>
      <div id="navStore" class="show !visible" data-bs-parent="#sideNavbar{{$uniqKey}}">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="{{url('/store')}}" wire:navigate>Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url('/store/profile')}}" wire:navigate>Profil</a>
          </li>
          <!-- Nav item -->
          <li class="nav-item">
            <a class="nav-link " href="{{url('/store/setting')}}" wire:navigate>Pengaturan</a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-bs-toggle="collapse"
        data-bs-target="#navCategoriesOrders" aria-expanded="true" aria-controls="navCategoriesOrders">
        <div class="d-flex align-items-center">
          <span class="nav-link-icon"><i class="bi bi-cart-check"></i></span>
          <span class="nav-link-text">Pesanan</span>
        </div>
      </a>
      <div id="navCategoriesOrders" class="show !visible" data-bs-parent="#sideNavbar{{$uniqKey}}">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="{{url('/store/transaction/history')}}" wire:navigate>Riwayat Pesanan</a>
          </li>
          <!-- Nav item -->
          <li class="nav-item">
            <a class="nav-link " href="{{url('/store/transaction/rating')}}" wire:navigate>Ulasan Pembeli</a>
          </li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="#" data-bs-toggle="collapse"
        data-bs-target="#navProduct" aria-expanded="true" aria-controls="navProduct">
        <div class="d-flex align-items-center">
          <span class="nav-link-icon"><i class="bi bi-box-seam"></i></span>
          <span class="nav-link-text">Dagangan</span>
        </div>
      </a>
      <div id="navProduct" class="show !visible" data-bs-parent="#sideNavbar{{$uniqKey}}">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="{{url('/store/product')}}" wire:navigate>Dagangan Saya</a>
          </li>
          <!-- Nav item -->
          <li class="nav-item">
            <a class="nav-link " href="{{url('/store/product/create')}}" wire:navigate>Buat Dagangan Baru</a>
          </li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="#" data-bs-toggle="collapse"
        data-bs-target="#navMoney" aria-expanded="true" aria-controls="navMoney">
        <div class="d-flex align-items-center">
          <span class="nav-link-icon"><i class="bi bi-credit-card"></i></span>
          <span class="nav-link-text">Keuangan</span>
        </div>
      </a>
      <div id="navMoney" class="show !visible" data-bs-parent="#sideNavbar{{$uniqKey}}">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="{{url('/finance')}}" wire:navigate>Saldo</a>
          </li>
          <!-- Nav item -->
          <li class="nav-item">
            <a class="nav-link " href="{{url('/finance/ref')}}" wire:navigate>Rekening</a>
          </li>
        </ul>
      </div>
    </li>
  </ul>

</div>
