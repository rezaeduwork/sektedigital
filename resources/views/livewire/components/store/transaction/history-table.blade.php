<div class="p-2 sm:p-4">
  @foreach ($list as $tx)
  <livewire:components.store.transaction.history-item :key="'item-'.uniqid()" :tx="$tx" :status="$status">
  @endforeach

  <div class="mt-4">
    {{$list->links()}}
  </div>

</div>
