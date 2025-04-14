<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Transaction Instant</h1>
        </div>
        <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Transaction Instant</li>
          </ol>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                  <div class="input-group input-group-sm" style="width: 180px">
                    <input
                      type="text"
                      wire:model.live.debounce.150ms="search"
                      class="form-control float-right"
                      placeholder="id / product"
                    />
                  </div>
                </div>
                <livewire:admin.transaction-instant.create-transaction :key="'create-transaction'" />
              </div>
            </div>

            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap text-xs">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>User</th>
                    <th>Product</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($list as $row)
                @php
                $firstProduct = $row->product()->withTrashed()->first();
                @endphp
                <tr wire:key="{{'table'.$row->id}}">
                  <td>#{{$row->id}}</td>
                  <td>{{\Carbon\Carbon::parse($row->created_at)->format('l, d F Y H:i')}}</td>
                  <td>{{$row->customer_name}}</td>
                  <td>
                    @if ($firstProduct)
                    <div class="flex flex-col items-start space-y-1">
                      <div class="flex items-center space-x-2">
                        <img src="{{productInstantImage($firstProduct)}}" alt="" class="size-[24px]" />
                        <div class="font-semibold mb-1">{{$firstProduct->title}}</div>
                      </div>
                    </div>
                    @endif
                  </td>
                  <td><div class="font-semibold">Rp{{number_format($row->payment->amount,0,',','.')}}</div></td>
                  <td>
                    <div class="flex flex-col items-start space-y-1">
                      <div class="btn btn-xs btn-default !cursor-default {{$row->getStatusColor()}}">Transaction: <span class="font-bold">{{$row->getStatusText()}}</span></div>
                      <div class="btn btn-xs btn-default !cursor-default {{$row->payment->getStatusColor()}}">Payment: <span class="font-bold">{{$row->payment->getStatusText()}}</span></div>
                    </div>
                  </td>
                  <td>
                    <div class="flex flex-col space-y-1">
                      @if ($row->payment->status == 'settlement' && in_array($row->status, ['confirmed','rejected']))
                      <livewire:admin.transaction-instant.process-manual-action :key="'manual-process-'.$row->id" :transaction="$row" />
                      @endif
                      @php
                      $phone = $row->customer_phone;
                      if ($phone) {
                        $phone = preg_replace('/[^0-9]/', '', $phone);
                      }
                      @endphp
                      @if ($phone)
                        @if (in_array($row->status, ['finished']))
                        <a href="https://api.whatsapp.com/send?phone={{$phone}}&text=Halo%20{{$row->customer_name}},%20terima%20kasih%20sudah%20melakukan%20transaksi%20di%20{{config('app.name')}}.%20Silahkan%20cek%20status%20transaksi%20anda.%0AID:%20{{$row->id}}{{isset($firstProduct->title) ? '.%0AProduct:%20':''}}.%0AStatus:%20{{$row->getStatusText()}}.%0A" target="_blank" class="btn btn-xs btn-success">
                          <i class="fab fa-whatsapp"></i> WA
                        </a>
                        @elseif(in_array($row->payment->status, ['pending']) && \Carbon\Carbon::parse($row->payment->created_at)->addHours(1)->gt(\Carbon\Carbon::now()))
                        <a href="https://api.whatsapp.com/send?phone={{$phone}}&text=Halo%20{{$row->customer_name}},%20terima%20kasih%20sudah%20menggunakan%20platform%20{{config('app.name')}}.%20Silahkan%20cek%20melakukan%20pembayaran%20sebelum{{\Carbon\Carbon::parse($row->payment->created_at)->addHours(1)->format('l, d F Y H:i')}}.%0AID:%20{{$row->id}}.%0AProduct:%20{{$firstProduct->title}}.%0AStatus:%20{{$row->getStatusText()}}.%0A" target="_blank" class="btn btn-xs btn-success">
                          <i class="fab fa-whatsapp"></i> WA
                        </a>
                        @endif
                      @endif
                    </div>
                  </td>
                </tr>
                @endforeach
                </tbody>
              </table>
              <div class="p-4">
                {{$list->links()}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>
