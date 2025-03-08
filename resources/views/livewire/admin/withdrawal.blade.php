<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Balances</h1>
        </div>
        <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Balances</li>
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
                  {{-- <livewire:admin.withdrawal.modal-approved />
                  <livewire:admin.withdrawal.modal-rejected /> --}}
                  <div class="input-group input-group-sm" style="width: 180px">
                    <select wire:model.live.debounce.150ms="type" class="form-control">
                      <option value="">Semua Tipe</option>
                      @foreach (\App\Models\UserBalance::getTypes() as $rowType)
                      <option value="{{$rowType}}">{{$rowType}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="input-group input-group-sm" style="width: 180px">
                    <select wire:model.live.debounce.150ms="status" class="form-control">
                      <option value="">Semua Status</option>
                      <option value="pending">Pending</option>
                      <option value="success">Success</option>
                      <option value="rejected">Rejected</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap text-xs">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Balance</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($list as $row)
                <tr wire:key="{{'table'.$row->id}}">
                  <td>{{$row->id}}</td>
                  <td class="w-full">{{$row->user->name}}</td>
                  <td>{{$row->user->phone}}</td>
                  <td class="">Rp{{number_format($row->user->balance,0,',','.')}}</td>
                  <td class="text-red-600 font-semibold">Rp{{number_format($row->amount,0,',','.')}}</td>
                  <td>
                    <div class="badge
                    @if($row->status == 'pending')
                    badge-warning
                    @elseif($row->status == 'rejected')
                    badge-danger
                    @else
                    badge-success
                    @endif
                    ">{{$row->status}}</div>
                  </td>
                  <td class="">
                    @if ($row->status == 'pending' && $row->type == 'withdraw')
                    <livewire:admin.withdrawal.action-pending-withdrawal :key="'withdrawal-pending-action'.$row->id" :history="$row" />
                    @endif
                    @if ($row->status == 'rejected' && $row->type == 'withdraw')
                    <livewire:admin.withdrawal.action-reject-withdrawal :key="'withdrawal-reject-action'.$row->id" :history="$row" />
                    @endif
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
