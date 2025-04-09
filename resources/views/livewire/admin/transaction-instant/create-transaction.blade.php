<div class="flex items-center">
  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#create-transaction">
    Buat Transaksi
  </button>
  <div class="modal fade" id="create-transaction" tabindex="-1" aria-labelledby="create-transactionLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-lg" id="create-transactionLabel">Buat Transaksi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info break-word whitespace-normal text-md text-base">
            Dengan membuat transaksi ini, anda akan membuat transaksi baru dengan produk yang dipilih dan langsung memotong saldo dari provider. Apakah anda yakin ingin membuat transaksi ini?
          </div>
          <div class="form-group">
            <label for="product">Pilih Kategori</label>
            @php
            $categories = \App\Models\ProductInstant::select('category')->groupBy('category')->get();
            @endphp
            <select wire:model.live="category" class="form-control">
              <option value="">-- Pilih Kategori --</option>
              @foreach ($categories as $row)
              <option value="{{$row->category}}">{{$row->category}}</option>
              @endforeach
            </select>
          </div>
          <div class="w-full" wire:loading wire:target="category">
            <div class="mx-auto flex w-full justify-center">
              @include('components.spinner')
            </div>
          </div>
          @if ($category)
          <div class="form-group" wire:loading.remove wire:target="category">
            <label for="product">Pilih Brand</label>
            @php
            $providers = \App\Models\ProductInstant::select('brand')->where('category', $category)->groupBy('brand')->get();
            @endphp
            <select wire:model.live="brand" class="form-control">
              <option value="">-- Pilih Brand --</option>
              @foreach ($providers as $row)
              <option value="{{$row->brand}}">{{$row->brand}}</option>
              @endforeach
            </select>
          </div>
          @endif
          @if ($category && $brand)
          <div class="form-group" wire:loading.remove wire:target="category">
            <label for="product">Pilih Produk</label>
            @php
            $products = \App\Models\ProductInstant::where('category', $category)->where('brand',$brand)->orderByRaw('type desc, price asc')->get();
            @endphp
            <select wire:model.live="productId" class="form-control">
              <option value="">-- Pilih Produk --</option>
              @foreach ($products as $row)
              <option value="{{$row->id}}">{{$row->title}}</option>
              @endforeach
            </select>
          </div>
          @endif
          @if ($category && $brand && $productId)
          @foreach (getTransactionInstantInformations($product->category,$product->brand) as $key => $row)
          <div class="form-group" wire:loading.remove wire:target="category">
            <label for="product">{{$row['label']}} @if(isset($row['required']) && $row['required'] === false) <i class="font-normal">optional</i> @endif</label>
            @if (in_array($row['type'], ['text', 'number']))
            <input type="{{$row['type']}}" @input.debounce.500ms="$wire.fillAccount('{{$key}}',$event.target.value)" class="form-control" />
            @elseif ($row['type'] == 'select')
            <select class="form-control" @change="$wire.fillAccount('{{$key}}',$event.target.value)">
              <option value="">-- Pilih {{$row['label']}} --</option>
              @foreach ($row['options'] as $option)
              <option value="{{$option}}">{{$option}}</option>
              @endforeach
            </select>
            @endif
          </div>
          @endforeach
          @endif
          @if ($showConfirmation)
          <div class="alert alert-warning break-word whitespace-normal text-md text-base">
            Apakah anda yakin ingin memproses transaksi ini?
            <div class="font-semibold text-lg">Produk: {{$product->title}}</div>
          </div>
          @endif
        </div>
        <div class="modal-footer flex items-center">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          @if ($showConfirmation)
          <div class="flex items-center">
            <div wire:loading.remove wire:target="process">
              <button type="button" class="btn btn-warning" wire:click="process">Lanjut Simpan</button>
            </div>
            <div wire:loading wire:target="process">
              <button type="button" class="btn btn-warning opacity-50">Lanjut Simpan</button>
            </div>
          </div>
          @else
            <button type="button" class="btn btn-success" @click="$wire.set('showConfirmation', true)">Simpan</button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
