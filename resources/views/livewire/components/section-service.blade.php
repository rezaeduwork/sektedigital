<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 lg:gap-6">
  @php
  $list = [
    [
      'image' => url('storage/gift-voucher.png'),
      'name' => 'Top Up',
    ],
    [
      'image' => url('storage/payment-method.png'),
      'name' => 'Tagihan',
    ],
    [
      'image' => url('storage/demand.png'),
      'name' => 'Rekber',
    ],
    [
      'image' => url('storage/click.png'),
      'name' => 'E-Book',
    ],
    [
      'image' => url('storage/app-development.png'),
      'name' => 'Software',
    ],
    [
      'image' => url('storage/audio.png'),
      'name' => 'Audio',
    ],
    [
      'image' => url('storage/other.png'),
      'name' => 'Lainnya',
    ]
  ];
  @endphp
  @foreach ($list as $row)
  <!-- col -->
  <div>
    <a href="shop-grid.html" class="text-decoration-none text-inherit">
      <!-- card -->
      <div class="border hover:border-2">
        <div class="card-body text-center py-4">
          <div class="flex justify-center">
            <!-- img -->
            <img src="{{$row['image']}}" alt="Grocery Ecommerce Template" class="mb-3">
          </div>
          <!-- text -->
          <div class="truncate">{{$row['name']}}</div>
        </div>
      </div>
    </a>
  </div>
  @endforeach
</div>
