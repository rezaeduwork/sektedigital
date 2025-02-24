<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Product</h1>
        </div>
        <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Product</li>
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
                  <livewire:admin.product.modal-deleted />
                </div>
              </div>
            </div>

            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap text-xs">
                <thead>
                  <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Hightlight</th>
                    <th>Store</th>
                    <th>Terjual</th>
                    <th>Dibuat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($list as $row)
                <tr wire:key="{{'table'.$row->id}}">
                  <td>
                    <div class="flex items-center space-x-1">
                      <img src="{{productImage($row->mainImage())}}" alt="" class="size-[24px]" />
                      @if ($row->images()->count() > 1)
                      <button class="btn btn-xs btn-default" data-toggle="modal" data-target="#modal-show-image-{{$row->id}}">+{{$row->images()->count() - 1}}</button>
                      <div class="modal fade" id="modal-show-image-{{$row->id}}">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Deleted Product</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <div class="grid grid-cols-4 gap-2">
                                @foreach ($row->images as $image)
                                <img src="{{productImage($image)}}" alt="" class="w-full h-auto">
                                @endforeach
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      @endif
                    </div>
                  </td>
                  <td class="w-full font-bold text-sm" style="white-space: normal;">{{$row->title}}</td>
                  <td>
                    <div class="flex items-center space-x-1">
                      <img src="{{url('storage/'.$row->category->icon)}}" alt="" srcset="" class="size-5">
                      <div>
                        {{$row->category->name}}
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="max-w-[100px]" style="white-space: normal;">
                      {{$row->highlight}}
                    </div>
                  </td>
                  <td>
                    <div class="flex items-center space-x-1">
                      <img src="{{storeProfile($row->store)}}" alt="" srcset="" class="size-5">
                      <div>
                        {{$row->store->name}}
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="flex items-center justify-center">
                      @php
                      $soldCount = $row->transactionDetails()->whereHas('transaction', function($query) {
                        $query->whereIn('status', ['finished']);
                      })->count();
                      @endphp
                      {{$soldCount}}
                    </div>
                  </td>
                  <td>
                    <div>{{\Carbon\Carbon::parse($row->created_at)->diffForHumans()}}</div>
                  </td>
                  <td>
                    <span class="flex items-center space-x-1 btn btn-xs @if($row->status === 'active') btn-success @else btn-default @endif">
                      <div>{{$row->status}}</div>
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                      </svg>
                    </span>
                  </td>
                  <td>
                    <div class="flex items-center space-x-1 w-full">
                      <livewire:admin.product.delete :id="$row->id" :key="'delete-'.$row->id" />
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
