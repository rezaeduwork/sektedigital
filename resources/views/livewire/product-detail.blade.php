<div class="container">
  <livewire:components.product-detail.main-detail :product="$product">
  <livewire:components.product-detail.store-owner :product="$product">
  <livewire:components.product-detail.description :product="$product">
  <livewire:components.product-detail.rating :product="$product">
  {{-- <livewire:components.product-detail.discussion :product="$product"> --}}
  <livewire:components.product-detail.store-products :store="$product->store">
  <livewire:components.product-detail.recommendation>
</div>
