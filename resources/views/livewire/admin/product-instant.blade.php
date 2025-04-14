<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Product Instant</h1>
        </div>
        <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Product Instant</li>
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
              <div class="grid grid-cols-12">
                <div class="col-span-12">
                  <div class="mb-2">
                    <div class="mb-1">Search</div>
                    <div class="flex justify-between items-center">
                      <div class="flex items-center space-x-2">
                        <div class="input-group input-group-sm" style="width: 180px">
                          <input
                            type="text"
                            wire:model.live.debounce.150ms="search"
                            class="form-control float-right"
                            placeholder="Search title/brand/highlight/description"
                          />
                        </div>
                        <div class="input-group input-group-sm" style="width: 180px">
                          <input
                            type="text"
                            wire:model.live.debounce.150ms="searchCode"
                            class="form-control float-right"
                            placeholder="Search Code"
                          />
                        </div>
                        <div class="input-group input-group-sm" style="width: 180px">
                          <input
                            type="text"
                            wire:model.live.debounce.150ms="searchCategory"
                            class="form-control float-right"
                            placeholder="Search Category"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mb-2">
                    <div class="mb-1">Status</div>
                    <div>
                      <button class="btn btn-xs @if(!$filterStatus) btn-success @else btn-default @endif" @click="$wire.set('filterStatus', '')">Semua</button>
                      @foreach (\App\Models\ProductInstant::getStatusses() as $row)
                      <button class="btn btn-xs @if($row === $filterStatus) btn-success @else btn-default @endif" @click="$wire.set('filterStatus', '{{$row}}')">{{$row}}</button>
                      @endforeach
                    </div>
                  </div>
                  <div class="mb-2">
                    <div class="mb-1">Stok</div>
                    <div>
                      <button class="btn btn-xs @if(!$emptyStock) btn-success @else btn-default @endif" @click="$wire.set('emptyStock', '')">Semua</button>
                      @foreach (['Habis','Masih'] as $row)
                      <button class="btn btn-xs @if($row === $emptyStock) btn-success @else btn-default @endif" @click="$wire.set('emptyStock', '{{$row}}')">{{$row}}</button>
                      @endforeach
                    </div>
                  </div>
                </div>
                <div class="col-span-12"><hr class="my-2" /></div>
                <div class="col-span-12">
                  <div x-data="{openToolbox: false}">
                    <div class="mb-1 w-full flex items-center justify-between bg-gray-100 p-2 cursor-pointer" @click="openToolbox = !openToolbox">
                      <div>Toolbox</div>
                      <button type="button" :class="openToolbox ? 'rotate-180':''" class="transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
                        </svg>
                      </button>
                    </div>
                    <div class="flex items-center space-x-1 overflow-hidden transition-height duration-300 mt-2" style="max-height: 0px;" :style="openToolbox ? 'max-height: 50px;':'max-height: 0px;'">
                      <livewire:admin.product-instant.reload-crypto :key="'reload-crypto-btn'">
                      <livewire:admin.product-instant.reload-digiflazz :key="'reload-digiflazz-btn'">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap text-xs">
                <thead>
                  <tr>
                    <th>Image</th>
                    <th>Code</th>
                    <th>Title</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stok</th>
                    <th>Hightlight</th>
                    {{-- <th>Store</th> --}}
                    {{-- <th>Terjual</th> --}}
                    <th>Timestamp</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($list as $row)
                <tr wire:key="{{'table'.$row->id}}">
                  <td>
                    @if ($row->image)
                    <div class="flex items-center space-x-1">
                      <img src="{{url($row->image)}}" alt="" class="size-[24px]" />
                    </div>
                    @endif
                  </td>
                  <td>{{$row->code}}</td>
                  <td class="w-full font-bold text-sm" style="white-space: normal;">{{$row->title}}</td>
                  <td>{{$row->brand}}</td>
                  <td>
                    <div class="flex items-center space-x-1">
                      <div>
                        {{$row->category}}
                      </div>
                    </div>
                  </td>
                  <td class="font-semibold text-blue-700 text-md">Rp{{number_format($row->price,0,',','.')}}</td>
                  <td class="font-semibold text-blue-700 text-md">{{$row->stock == -1 ? 'Unlimited': $row->stock}}</td>
                  <td>
                    <div class="truncate max-w-[150px]">
                      {{$row->highlight}}
                    </div>
                  </td>
                  {{-- <td>
                    <div class="flex items-center space-x-1">
                      <img src="{{storeProfile($row->store)}}" alt="" srcset="" class="size-5">
                      <div>
                        {{$row->store->name}}
                      </div>
                    </div>
                  </td> --}}
                  {{-- <td>
                    <div class="flex items-center justify-center">
                      @php
                      $soldCount = $row->transactionDetails()->whereHas('transaction', function($query) {
                        $query->whereIn('status', ['finished']);
                      })->count();
                      @endphp
                      {{$soldCount}}
                    </div>
                  </td> --}}
                  <td>
                    <div class="flex flex-col items-start space-y-1">
                      <div class="badge badge-info">Dibuat {{\Carbon\Carbon::parse($row->created_at)->diffForHumans()}}</div>
                      <div class="badge badge-info">Diupdate {{\Carbon\Carbon::parse($row->updated_at)->diffForHumans()}}</div>
                    </div>
                  </td>
                  <td>
                    <div class="space-y-1 flex flex-col items-start">
                      <div class="dropdown" x-data="{open: false}">
                        <button class="flex items-center space-x-1 btn btn-xs z-[10] @if($row->status === 'active') btn-success @else btn-default @endif" type="button">
                          <div>{{$row->status}}</div>
                          {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                          </svg> --}}
                        </button>
                        <ul class="absolute top-[100%] right-0 bg-white border z-[9]" display="none" x-show="open" @click.away="open = false">
                          @foreach (\App\Models\ProductInstant::getStatusses() as $rowStatus)
                          <li class="dropdown-item cursor-pointer" @click="$wire.updateStatus({{$row->id}},'{{$rowStatus}}');open = false;">{{$rowStatus}}</li>
                          @endforeach
                        </ul>
                      </div>
                      @if ($row->provider_buyer_status !== 'unset')
                      <div class="badge badge-info">Provider Status {{$row->provider_buyer_status}}</div>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div class="flex items-center space-x-1 w-full">
                      <livewire:admin.product-instant.delete :key="'product-instant-delete-'.$row->id" :id="$row->id">
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
