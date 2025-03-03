<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Transaction</h1>
        </div>
        <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Transaction</li>
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
                      placeholder="Search"
                    />
                  </div>
                  <div class="input-group input-group-sm" style="width: 180px">
                    <input
                      type="text"
                      wire:model.live.debounce.150ms="searchStore"
                      class="form-control float-right"
                      placeholder="Search Store"
                    />
                  </div>
                </div>
              </div>
            </div>

            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap text-xs">
                <thead>
                  <tr>
                    <td>ID</td>
                    <td>Product</td>
                    <td>User</td>
                    <td>Store</td>
                    <td>Amount</td>
                    <td>Status</td>
                  </tr>
                </thead>
                <tbody>
                @foreach ($list as $row)
                @php
                $firstProduct = $row->details()->first()->product;
                @endphp
                <tr wire:key="{{'table'.$row->id}}">
                  <td>#{{$row->id}}</td>
                  <td>
                    @if ($firstProduct)
                    <div class="flex flex-col items-start space-y-1">
                      <div>
                        <div class="font-semibold mb-1">{{$firstProduct->title}}</div>
                        <img src="{{productImage($firstProduct->mainImage())}}" alt="" class="size-[24px]" />
                      </div>
                      @if ($row->details()->count() > 1)
                      <button class="btn btn-xs btn-default" data-toggle="modal" data-target="#modal-show-image-{{$row->id}}">+{{$row->details()->count() - 1}} Products</button>
                      <div class="modal fade" id="modal-show-image-{{$row->id}}">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Products</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <div class="grid grid-cols-4 gap-2">
                                @foreach ($row->details as $detail)
                                <img src="{{productImage($detail->product->mainImage())}}" alt="" class="w-full h-auto">
                                @endforeach
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      @endif
                    </div>
                    @endif
                  </td>
                  <td>{{$row->user->name}}</td>
                  <td>
                    <div class="flex items-center space-x-1">
                      <img src="{{storeProfile($row->store)}}" alt="" srcset="" class="size-5">
                      <div>
                        {{$row->store->name}}
                      </div>
                    </div>
                  </td>
                  <td><div class="font-semibold">Rp{{number_format($row->amount,0,',','.')}}</div></td>
                  <td>
                    <div class="dropdown w-fit" x-data="{open: false}">
                      <button @click="open = !open" class="flex items-center space-x-1 btn btn-xs btn-default {{$row->getStatusColor()}}" type="button">
                        <div>{{$row->status}}</div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                        </svg>
                      </button>
                      <ul class="absolute top-[100%] right-0 bg-white border z-[10]" display="none" x-show="open" @click.away="open = false">
                        @foreach (\App\Models\Transaction::getStatusses() as $rowStatus)
                        <li class="dropdown-item cursor-pointer" @click="$wire.updateStatus({{$row->id}},'{{$rowStatus}}');open = false;">{{$rowStatus}}</li>
                        @endforeach
                      </ul>
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
