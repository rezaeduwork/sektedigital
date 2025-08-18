<section class="container">
  @php
  $store = auth()->user()->store;
  @endphp
  <div class="table-responsive-xl mb-2 sm:mb-6 mb-lg-0 space-y-4">
    <h1 class="text-lg sm:text-2xl font-bold">Pengumuman</h1>
    <div class="row flex-nowrap pb-3 pb-lg-0">
      <div class="col-12 mb-2 sm:mb-6">
        Belum ada pengumuman.
      </div>
    </div>
  </div>
  @php
  $importantActivities = [
    [
      'title' => 'Perlu Proses',
      'value' => storeTransactionQuery('confirmed')->count(),
    ],
    [
      'title' => 'Diproses',
      'value' => storeTransactionQuery('processed')->count(),
    ],
    [
      'title' => 'Menunggu Konfirmasi',
      'value' => storeTransactionQuery('store_finished')->count(),
    ],
    [
      'title' => 'Selesai',
      'value' => storeTransactionQuery('finished')->count(),
    ]
  ];
  @endphp
  <div class="table-responsive-xl mb-2 sm:mb-6 mb-lg-0 space-y-4">
    <h1 class="text-lg sm:text-2xl font-bold">Aktifitas</h1>
    <div class="grid grid-cols-2 sm:grid-cols-4 pb-3 pb-lg-0 gap-2 sm:gap-6">
      @foreach ($importantActivities as $row)
      <div class="mb-2 sm:mb-2 sm:mb-6">
        <div class="card h-100 card-lg shadow-none border">
          <div class="card-body p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div>
                <h4 class="mb-0 fs-5 max-sm:!text-sm">{{$row['title']}}</h4>
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
  <div class="table-responsive-xl mb-2 sm:mb-6 mb-lg-0 space-y-4">
    <h1 class="text-lg sm:text-2xl font-bold">Performa Toko</h1>
    <div class="row sm:flex-nowrap pb-3 pb-lg-0">
      <div class="col-lg-4 col-12 mb-2 sm:mb-6">
        <!-- card -->
        <div class="card h-100 card-lg shadow-none border">
          <!-- card body -->
          <div class="card-body p-6">
            <!-- heading -->
            <div class="d-flex justify-content-between align-items-center mb-2 sm:mb-6">
              <div>
                <h4 class="mb-0 fs-5">Pendapatan Bulan Ini</h4>
              </div>
              <div class="icon-shape icon-md bg-light-danger text-dark-danger rounded-circle">
                <i class="bi bi-currency-dollar fs-5"></i>
              </div>
            </div>
            <!-- project number -->
            <div class="lh-1">
              <h1 class="mb-2 fw-bold fs-2">Rp{{ number_format($totalMonthRevenue, 0, ',', '.') }}</h1>
              <div class="d-flex flex-column mt-3">
                <div class="d-flex justify-content-between">
                  <span class="text-sm text-gray-600">Produk:</span>
                  <span class="text-sm font-medium">Rp{{ number_format($currentMonthRevenue, 0, ',', '.') }}</span>
                </div>
                {{-- <div class="d-flex justify-content-between">
                  <span class="text-sm text-gray-600">PPOB:</span>
                  <span class="text-sm font-medium">Rp{{ number_format($currentMonthPPOBRevenue, 0, ',', '.') }}</span>
                </div> --}}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-12 mb-2 sm:mb-6">
        <!-- card -->
        <div class="card h-100 card-lg shadow-none border">
          <!-- card body -->
          <div class="card-body p-6">
            <!-- heading -->
            <div class="d-flex justify-content-between align-items-center mb-2 sm:mb-6">
              <div>
                <h4 class="mb-0 fs-5">Total Penjualan</h4>
              </div>
              <div class="icon-shape icon-md bg-light-warning text-dark-warning rounded-circle">
                <i class="bi bi-cart fs-5"></i>
              </div>
            </div>
            <!-- project number -->
            <div class="lh-1">
              <h1 class="mb-2 fw-bold fs-2">{{ number_format($totalSalesCount) }}</h1>
              <div class="d-flex flex-column mt-3">
                <div class="d-flex justify-content-between">
                  <span class="text-sm text-gray-600">Produk:</span>
                  <span class="text-sm font-medium">{{ number_format($regularSalesCount) }}</span>
                </div>
                {{-- <div class="d-flex justify-content-between">
                  <span class="text-sm text-gray-600">PPOB:</span>
                  <span class="text-sm font-medium">{{ number_format($ppobSalesCount) }}</span>
                </div> --}}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-12 mb-2 sm:mb-6">
        <!-- card -->
        <div class="card h-100 card-lg shadow-none border">
          <!-- card body -->
          <div class="card-body p-6">
            <!-- heading -->
            <div class="d-flex justify-content-between align-items-center mb-2 sm:mb-6">
              <div>
                <h4 class="mb-0 fs-5">Produk Dilihat</h4>
              </div>
              <div class="icon-shape icon-md bg-light-info text-dark-info rounded-circle">
                <i class="bi bi-people fs-5"></i>
              </div>
            </div>
            @php
            $totalViews = \App\Models\ProductLog::whereHas('product', function ($query) use ($store) {
              $query->where('store_id', $store->id);
            })->where('activity', 'view')->count();

            // Calculate views from the last 24 hours
            $viewsLastDay = \App\Models\ProductLog::whereHas('product', function ($query) use ($store) {
              $query->where('store_id', $store->id);
            })->where('activity', 'view')->where('created_at', '>=', now()->subDay())->count();
            @endphp
            <!-- project number -->
            <div class="lh-1">
              <h1 class="mb-2 fw-bold fs-2">
                {{$totalViews}}
              </h1>
              <span>
                <span class="text-dark me-1">
                  {{$viewsLastDay}}
                </span>
                baru
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
