<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Withdrawal</h1>
        </div>
        <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Withdrawal</li>
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
                    <th>Status</th>
                    <th>Amount</th>
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
                    <div class="badge">{{$row->status}}</div>
                  </td>
                  <td class="flex items-center space-x-1">
                    <div>
                      <button class="btn btn-xs btn-success flex items-center space-x-1" data-toggle="modal" data-target="#approve-modal-{{$row->id}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-all" viewBox="0 0 16 16">
                          <path d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486z"/>
                        </svg>
                        <div>Approve</div>
                      </button>
                      <div class="modal fade" id="approve-modal-{{$row->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Delete Category</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              Are you sure want to approve this withdrawal?
                            </div>
                            <div class="modal-footer flex items-center">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <div wire:loading.remove wire:target="approve({{$row->id}})">
                                <button type="button" class="btn btn-warning" wire:click="approve({{$row->id}})">Approve</button>
                              </div>
                              <div wire:loading wire:target="approve({{$row->id}})">
                                <button type="button" class="btn btn-warning opacity-50">
                                  <div>Approve</div>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <button class="btn btn-xs btn-danger flex items-center space-x-1" data-toggle="modal" data-target="#reject-modal-{{$row->id}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                          <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                        <div>Reject</div>
                      </button>
                      <div class="modal fade" id="reject-modal-{{$row->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Reject Withdrawal</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              Are you sure want to reject this withdrawal?
                            </div>
                            <div class="modal-footer flex items-center">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <div wire:loading.remove wire:target="reject({{$row->id}})">
                                <button type="button" class="btn btn-warning" wire:click="reject({{$row->id}})">Reject</button>
                              </div>
                              <div wire:loading wire:target="reject({{$row->id}})">
                                <button type="button" class="btn btn-warning opacity-50">
                                  <div>Approve</div>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
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
