<?php

namespace App\Livewire\Store\Whatsapp;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreWhatsappSetting;

class BotSettings extends Component
{
  public $isActive = false;
  public $welcomeMessageEnabled = false;
  public $welcomeMessageText = '';
  public $settings;
  public function mount()
  {
    $store = Auth::user()->store;
    $this->settings = StoreWhatsappSetting::firstOrCreate(
      ['store_id' => $store->id],
      [
        'is_bot_active' => false,
        'send_welcome_message' => false,
        'welcome_message' => "👋 *Selamat datang di {store_name}!* 👋\n\n" .
          "Saya adalah bot asisten belanja Anda. Berikut adalah yang dapat saya bantu:\n\n" .
          "🔍 *Cari Produk*: Cukup kirim kata kunci pencarian untuk melihat produk yang cocok\n" .
          "📋 *products*: Lihat semua produk yang tersedia\n" .
          "ℹ️ *help*: Lihat semua perintah yang tersedia\n\n" .
          "Beri tahu saya apa yang Anda cari hari ini!"
      ]
    );

    $this->isActive = $this->settings->is_bot_active;
    $this->welcomeMessageEnabled = $this->settings->send_welcome_message;
    $this->welcomeMessageText = $this->settings->welcome_message;
  }
  public function toggleBotActive()
  {
    $this->isActive = !$this->isActive;
    $this->settings->is_bot_active = $this->isActive;
    $this->settings->save();

    $this->dispatch('alert-success', message: $this->isActive ? 'WhatsApp bot activated successfully!' : 'WhatsApp bot deactivated.');
  }

  public function toggleWelcomeMessage()
  {
    $this->welcomeMessageEnabled = !$this->welcomeMessageEnabled;
    $this->settings->send_welcome_message = $this->welcomeMessageEnabled;
    $this->settings->save();

    $this->dispatch('alert-success', message: $this->welcomeMessageEnabled ? 'Welcome message enabled!' : 'Welcome message disabled.');
  }

  public function saveWelcomeMessage()
  {
    $this->validate([
      'welcomeMessageText' => 'required|min:10'
    ], [
      'welcomeMessageText.required' => 'Welcome message cannot be empty.',
      'welcomeMessageText.min' => 'Welcome message must be at least 10 characters long.'
    ]);

    $this->settings->welcome_message = $this->welcomeMessageText;
    $this->settings->save();

    $this->dispatch('alert-success', message: 'Welcome message updated successfully!');
  }

  public function resetWelcomeMessage()
  {
    $store = Auth::user()->store;
    $this->welcomeMessageText = "👋 *Selamat datang di {$store->name}!* 👋\n\n" .
      "Saya adalah bot asisten belanja Anda. Berikut adalah yang dapat saya bantu:\n\n" .
      "🔍 *Cari Produk*: Cukup kirim kata kunci pencarian untuk melihat produk yang cocok\n" .
      "📋 *products*: Lihat semua produk yang tersedia\n" .
      "ℹ️ *help*: Lihat semua perintah yang tersedia\n\n" .
      "Beri tahu saya apa yang Anda cari hari ini!";

    $this->settings->welcome_message = $this->welcomeMessageText;
    $this->settings->save();

    $this->dispatch('alert-success', message: 'Welcome message reset to default!');
  }

  public function render()
  {
    return view('livewire.store.whatsapp.bot-settings')
      ->layout('components.layouts.app-dashboard');
  }
}
