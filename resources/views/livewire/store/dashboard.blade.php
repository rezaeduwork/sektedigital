<section class="container">
  <div class="table-responsive-xl mb-6 mb-lg-0 space-y-4">
    <h1 class="text-2xl font-bold">Pengumuman</h1>
    <div class="row flex-nowrap pb-3 pb-lg-0">
      <div class="col-12 mb-6">
        Belum ada pengumuman.
      </div>
    </div>
  </div>
  @php
  $importantActivities = [
    [
      'title' => 'Belum Bayar',
      'value' => 0,
    ],
    [
      'title' => 'Menunggu Konfirmasi',
      'value' => 0,
    ],
    [
      'title' => 'Diproses',
      'value' => 0,
    ],
    [
      'title' => 'Selesai',
      'value' => 0,
    ],
  ];
  @endphp
  <div class="table-responsive-xl mb-6 mb-lg-0 space-y-4">
    <h1 class="text-2xl font-bold">Aktifitas Yang Pelu Kamu Lakukan</h1>
    <div class="row flex-nowrap pb-3 pb-lg-0">
      @foreach ($importantActivities as $row)
      <div class="col-lg-3 col-12 mb-6">
        <div class="card h-100 card-lg shadow-none border">
          <div class="card-body p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div>
                <h4 class="mb-0 fs-5">{{$row['title']}}</h4>
              </div>
            </div>
            <div class="lh-1">
              <h1 class="fw-bold fs-2">{{$row['value']}}</h1>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <!-- table -->
  <div class="table-responsive-xl mb-6 mb-lg-0 space-y-4">
    <h1 class="text-2xl font-bold">Performa Kios</h1>
    <div class="row flex-nowrap pb-3 pb-lg-0">
      <div class="col-lg-4 col-12 mb-6">
        <!-- card -->
        <div class="card h-100 card-lg shadow-none border">
          <!-- card body -->
          <div class="card-body p-6">
            <!-- heading -->
            <div class="d-flex justify-content-between align-items-center mb-6">
              <div>
                <h4 class="mb-0 fs-5">Pendapatan</h4>
              </div>
              <div class="icon-shape icon-md bg-light-danger text-dark-danger rounded-circle">
                <i class="bi bi-currency-dollar fs-5"></i>
              </div>
            </div>
            <!-- project number -->
            <div class="lh-1">
              <h1 class="mb-2 fw-bold fs-2">Rp. 200,000</h1>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-12 mb-6">
        <!-- card -->
        <div class="card h-100 card-lg shadow-none border">
          <!-- card body -->
          <div class="card-body p-6">
            <!-- heading -->
            <div class="d-flex justify-content-between align-items-center mb-6">
              <div>
                <h4 class="mb-0 fs-5">Total Penjualan</h4>
              </div>
              <div class="icon-shape icon-md bg-light-warning text-dark-warning rounded-circle">
                <i class="bi bi-cart fs-5"></i>
              </div>
            </div>
            <!-- project number -->
            <div class="lh-1">
              <h1 class="mb-2 fw-bold fs-2">42,339</h1>
              {{-- <span>
                <span class="text-dark me-1">35+</span>
                New Sales
              </span> --}}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-12 mb-6">
        <!-- card -->
        <div class="card h-100 card-lg shadow-none border">
          <!-- card body -->
          <div class="card-body p-6">
            <!-- heading -->
            <div class="d-flex justify-content-between align-items-center mb-6">
              <div>
                <h4 class="mb-0 fs-5">Pengunjung</h4>
              </div>
              <div class="icon-shape icon-md bg-light-info text-dark-info rounded-circle">
                <i class="bi bi-people fs-5"></i>
              </div>
            </div>
            <!-- project number -->
            <div class="lh-1">
              <h1 class="mb-2 fw-bold fs-2">39,354</h1>
              {{-- <span>
                <span class="text-dark me-1">30+</span>
                new in 2 days
              </span> --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
