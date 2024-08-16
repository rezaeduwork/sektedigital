<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 lg:gap-6">
  @php
  $list = [
    [
      'image' => url('storage/gift-voucher.png'),
      'name' => 'Top Up',
      'url' => url('topup')
    ],
    [
      'image' => url('storage/payment-method.png'),
      'name' => 'Tagihan',
      'url' => url('tagihan')
    ],
    [
      'image' => url('storage/demand.png'),
      'name' => 'Rekber',
      'url' => url('rekber')
    ]
  ];
  @endphp
  @foreach ($list as $row)
  <!-- col -->
  <div>
    <a href="{{$row['url']}}" class="text-decoration-none text-inherit">
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
  @foreach (\App\Models\CategoryProduct::all() as $row)
  <!-- col -->
  <div>
    <a href="{{url('shop/'.$row->id)}}" class="text-decoration-none text-inherit" wire:navigate>
      <!-- card -->
      <div class="border hover:border-2">
        <div class="card-body text-center py-4">
          <div class="flex justify-center">
            <!-- img -->
            <img src="{{url('storage/'.$row->icon)}}" alt="{{$row->name}}" class="mb-3">
          </div>
          <!-- text -->
          <div class="truncate">{{$row->name}}</div>
        </div>
      </div>
    </a>
  </div>
  @endforeach
</div>
