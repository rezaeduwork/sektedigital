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
  public static function reloadRate($symbol)
  {
    switch ($symbol) {
      case 'usdidr':
        $apiKey = config('services.crypto.idr.api_key');
        $url = "https://anyapi.io/api/v1/exchange/convert?apiKey={$apiKey}&base=USD&to=IDR&amount=1";

        try {
          // Mengirim request ke API dengan timeout 5 detik
          $response = \Http::timeout(3)->get($url);

          // Cek jika request berhasil (status code 200)
          if ($response->successful()) {
            $data = $response->json();

            // Pastikan respons memiliki data yang diperlukan
            if (isset($data['converted'])) {
              $existingPrice = \App\Models\Currency::where('symbol', 'usdidr')->first();
              if (!$existingPrice) {
                \App\Models\Currency::create([
                  'symbol' => 'usdidr',
                  'price' => $data['converted']
                ]);
              } else {
                $existingPrice->price = $data['converted'];
                $existingPrice->save();
              }
            }
          }
        } catch (\Exception $e) {
        }
        break;

      case 'bnbusdt':
        $apiKey = config('services.crypto.bsc.api_key');
        $url = "https://api.bscscan.com/api?module=stats&action=bnbprice&apikey={$apiKey}";

        try {
          $response = \Http::connectTimeout(5)->timeout(30)->get($url);
          if ($response->successful()) {
            $data = $response->json();
            if (isset($data['result']['ethusd'])) {
              $existingPrice = \App\Models\Currency::where('symbol', 'bnbusdt')->first();
              if (!$existingPrice) {
                \App\Models\Currency::create([
                  'symbol' => 'bnbusdt',
                  'price' => $data['result']['ethusd']
                ]);
              } else {
                $existingPrice->price = $data['result']['ethusd'];
                $existingPrice->save();
              }
            }
          }
        } catch (\Throwable $th) {
          dd($th);
        }

      case 'bnbidr':
        $bnbUsdt = \App\Models\Currency::where('symbol', 'bnbusdt')->first();
        if ($bnbUsdt && \Carbon\Carbon::parse($bnbUsdt->updated_at)->diff(now())->s > 30) {
          \App\Models\Currency::reloadRate('bnbusdt');
        }
        $usdIdr = \App\Models\Currency::where('symbol', 'usdidr')->first();
        if ($bnbUsdt && $usdIdr) {
          $existingPrice = \App\Models\Currency::where('symbol', 'bnbidr')->first();
          $price = ($bnbUsdt->price * $usdIdr->price);
          if (!$existingPrice) {
            \App\Models\Currency::create([
              'symbol' => 'bnbidr',
              'price' => $price
            ]);
          } else {
            $existingPrice->price = $price;
            $existingPrice->save();
          }
        }
      default:
        # code...
        break;
    }
  }
}
