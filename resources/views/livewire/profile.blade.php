<div class="flex mb-5">
  <div class="flex shrink-0 w-[250px] py-2 px-4">
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
