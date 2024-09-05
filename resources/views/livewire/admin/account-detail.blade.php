<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <button type="button" class="m-0 border rounded p-2">
            <i class="fas fa-store"></i> &nbsp; {{ $user->store ? $user->store->name : 'Store' }}
          </button>
        </div>
        <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Account</li>
            <li class="breadcrumb-item active">{{ $user->name }}</li>
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
        <div class="col-md-3">

          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                <img class="profile-user-img img-fluid img-circle" src="{{ url('asset-admin') }}/img/user4-128x128.jpg"
                  alt="User profile picture">
              </div>
              <h3 class="profile-username text-center">{{ $user->name }}</h3>
              <p class="text-muted text-center">{{ $user->email }}</p>
              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Level</b> <a class="float-right">Common</a>
                </li>
                <li class="list-group-item">
                  <b>Funds</b> <a class="float-right">1,322</a>
                </li>
                <li class="list-group-item">
                  <b>Transaction</b> <a class="float-right">543</a>
                </li>
              </ul>
              <a href="#" class="btn btn-primary btn-block"><b>Login</b></a>
            </div>

          </div>

        </div>

        <div class="col-md-9">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link {{ $tab == 'information' ? 'active' : '' }}" href="#"
                    @click="$wire.set('tab', 'information')">Information</a></li>
                <li class="nav-item"><a class="nav-link {{ $tab == 'wallet' ? 'active' : '' }}" href="#"
                    @click="$wire.set('tab', 'wallet')">Wallet</a></li>
                <li class="nav-item"><a class="nav-link {{ $tab == 'transaction' ? 'active' : '' }}" href="#"
                    @click="$wire.set('tab', 'transaction')">Transaction</a></li>
                <li class="nav-item"><a class="nav-link {{ $tab == 'setting' ? 'active' : '' }}" href="#"
                    @click="$wire.set('tab', 'setting')">Setting</a></li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content">
                <div class="tab-pane {{ $tab == 'information' ? 'active' : '' }}" id="information">

                </div>

                <div class="tab-pane {{ $tab == 'wallet' ? 'active' : '' }}" id="wallet">
                  <livewire:admin.account.wallet :user="$user">
                </div>

                <div class="tab-pane {{ $tab == 'transaction' ? 'active' : '' }}" id="transaction">
                </div>

                <div class="tab-pane {{ $tab == 'setting' ? 'active' : '' }}" id="setting">
                  <form class="form-horizontal">
                    <div class="form-group row">
                      <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="inputName" placeholder="Name">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Name</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName2" placeholder="Name">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
                      <div class="col-sm-10">
                        <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputSkills" class="col-sm-2 col-form-label">Skills</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="offset-sm-2 col-sm-10">
                        <div class="checkbox">
                          <label>
                            <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-danger">Submit</button>
                      </div>
                    </div>
                  </form>
                </div>

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
