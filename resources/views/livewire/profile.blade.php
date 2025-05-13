<div class="flex max-sm:flex-col mb-5">
  <div class="flex shrink-0 w-full sm:w-[250px] py-2 px-4 sm:border sm:bg-white sm:rounded-lg">
    <livewire:components.profile-sidebar :page="$page" :key="$page">
  </div>
  <div class="w-full bg-white shadow rounded p-4">
    @if ($page == 'Profil')
    <livewire:components.account-profile>
    @elseif ($page == 'Kios Saya')
    <livewire:components.account-store>
    @endif
  </div>
</div>
