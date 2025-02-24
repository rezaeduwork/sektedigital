<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Category</h1>
        </div>
        <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Category</li>
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
                <livewire:admin.category.create />
                <div class="flex items-center space-x-2">
                  <div class="input-group input-group-sm" style="width: 250px">
                    <input
                      type="text"
                      wire:model.live.debounce.150ms="search"
                      class="form-control float-right"
                      placeholder="Search"
                    />
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i> Cari
                      </button>
                      <button class="btn btn-danger" @click="$wire.set('search', '')">Reset</button>
                    </div>
                  </div>
                  <livewire:admin.category.modal-deleted />
                </div>
              </div>
            </div>

            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Total Products</th>
                    <th>Icon</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($list as $row)
                <tr wire:key="{{'table'.$row->id}}">
                  <td class="w-full">{{$row->name}}</td>
                  <td>
                    <a href="http://" class="text-link">{{$row->products()->count()}} Products</a>
                  </td>
                  <td>
                    <img src="{{url('storage/'.$row->icon)}}" alt="" srcset="" class="size-[24px]" />
                  </td>
                  <td class="flex items-center space-x-1 w-full">
                    <livewire:admin.category.update :id="$row->id" :key="'update-'.$row->id" />
                    <livewire:admin.category.delete :id="$row->id" :key="'delete-'.$row->id" />
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
