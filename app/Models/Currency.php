<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
  use HasFactory;
  protected $fillable = [
    'symbol',
    'price'
  ];

  // HELPER
  public static function reloadUsdIdr()
  {
    self::where('symbol', 'usdidr')->delete();
    $apiKey = config('services.crypto.idr.api_key');
    $url = "https://anyapi.io/api/v1/exchange/convert?apiKey={$apiKey}&base=USD&to=IDR&amount=1";

    try {
      // Mengirim request ke API dengan timeout 5 detik
      $response = \Http::timeout(5)->get($url);

      // Cek jika request berhasil (status code 200)
      if ($response->successful()) {
        $data = $response->json();

        // Pastikan respons memiliki data yang diperlukan
        if (isset($data['converted'])) {
          self::create([
            'symbol' => 'usdidr',
            'price' => $data['converted']
          ]);
        }
      }
    } catch (\Exception $e) {
    }
  }
}
