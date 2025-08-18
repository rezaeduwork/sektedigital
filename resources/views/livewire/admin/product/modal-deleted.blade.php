<div>
  <button data-toggle="modal" data-target="#show-deleted-modal" class="btn btn-secondary btn-sm">Deleted Product ({{\App\Models\Product::onlyTrashed()->count()}})</button>
  <div class="modal fade" id="show-deleted-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Deleted Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{-- Search --}}
          <div class="input-group input-group-sm mb-2" style="width: 250px">
            <input
              type="text"
              wire:model.live.debounce.150ms="search"
              class="form-control float-right"
              placeholder="Search"
            />
          </div>
          <table class="table table-hover text-nowrap text-xs">
            <thead>
              <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Store</th>
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
                          <h5 class="modal-title" id="exampleModalLabel">Product Images</h5>
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
                @if ($row->category)
                <div class="flex items-center space-x-1">
                  <img src="{{url('storage/'.$row->category->icon)}}" alt="" srcset="" class="size-5">
                  <div>
                    {{$row->category->name}}
                  </div>
                </div>
                @endif
              </td>
              <td>
                @if ($row->store)
                <div class="flex items-center space-x-1">
                  <img src="{{storeProfile($row->store)}}" alt="" srcset="" class="size-5">
                  <div>
                    {{$row->store->name}}
                  </div>
                </div>
                @endif
              </td>
              <td>
                <livewire:admin.product.restore :id="$row->id" :key="'restore-'.$row->id" />
              </td>
            </tr>
            @endforeach
            </tbody>
          </table>
          {{-- LOAD MORE --}}
          @if ($perPage < $total)
          <div class="flex items-center justify-center">
            <button wire:click="loadMore" class="btn btn-secondary flex items-center space-x-1 text-xs">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 animate-spin" wire:loading wire:target="loadMore">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
              </svg>
              <div>
                Load More
              </div>
            </button>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
