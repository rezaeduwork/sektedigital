<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductInstant extends Model
{
  use HasFactory, SoftDeletes;
  protected $fillable = [
    'code',
    'provider',
    'brand',
    'category',
    'title',
    'highlight',
    'description',
    'price',
    'slug',
    'stock',
    'provider_stock',
    // comment('active | inactive')
    'status',
    // comment('unset | active | inactive')
    'provider_status',
    'image',
    'type'
  ];

  // HELPER
  // HELPER
  public static function getCategoryHightlight($category, $brand = null)
  {
    switch ($category) {
      case 'Pulsa':
        return 'Pulsa adalah saldo digital yang digunakan untuk melakukan panggilan, mengirim SMS, atau mengakses internet pada kartu SIM prabayar.';
        break;

      case 'Data':
        return 'Paket data memungkinkan pengguna untuk mengakses internet dengan kuota tertentu tanpa memotong pulsa reguler.';
        break;

      case 'Paket SMS & Telpon':
        return 'Paket SMS & Telpon menawarkan kuota komunikasi untuk mengirim pesan dan melakukan panggilan dengan tarif lebih hemat.';
        break;

      case 'Voucher':
        return 'Voucher digital dapat digunakan untuk mengisi ulang saldo atau mendapatkan akses ke layanan tertentu, seperti game atau streaming.';
        break;

      case 'Aktivasi Perdana':
        return 'Aktivasi Perdana adalah kartu SIM baru yang langsung aktif dengan paket internet, telepon, atau SMS yang telah tersedia.';
        break;

      case 'PLN':
        return 'Layanan pembayaran listrik PLN mencakup token prabayar dan pembayaran tagihan listrik pascabayar.';
        break;

      case 'E-Money':
        return 'E-Money adalah saldo digital yang dapat digunakan untuk transaksi online, pembayaran tol, transportasi, dan merchant digital.';
        break;

      case 'Games':
        if ($brand === 'MOBILE LEGENDS') {
          return 'Voucher Mobile Legends digunakan untuk top-up diamond yang dapat digunakan membeli skin, hero, dan item dalam game.';
        } else if ($brand === 'FREE FIRE') {
          return 'Voucher Free Fire digunakan untuk top-up diamond guna mendapatkan karakter, senjata, dan item eksklusif di dalam game.';
        }
        break;

      default:
        return null;
        break;
    }
  }
  public static function getBrandLogo($brand)
  {
    switch ($brand) {
      case 'MOBILE LEGENDS':
        return 'assets/images/logo/ml.webp';
        break;
      case 'FREE FIRE':
        return 'assets/images/logo/ff.webp';
        break;
      case 'PUBG MOBILE':
        return 'assets/images/logo/pubgm.webp';
        break;
      default:
        return null;
        break;
    }
  }
  public static function getStatusses()
  {
    return ['active', 'inactive'];
  }
  public static function reloadCrypto()
  {
    foreach (config('product') as $row) {
      $existing = \App\Models\ProductInstant::whereCode($row['code'])->first();
      \App\Models\Currency::reloadRate('bnbidr');
      $price = \App\Models\Currency::where('symbol', $row['code'] . 'idr')->first();
      if (!$existing) {
        \App\Models\ProductInstant::create([
          'code' => $row['code'],
          'category' => $row['category'],
          'title' => $row['title'],
          'highlight' => $row['highlight'],
          'description' => $row['description'],
          'price' => $price ? $price->price : 0,
          'slug' => $row['slug'],
          'stock' => 100,
          'status' => $price && $price->price > 0 ? $row['status'] : 'inactive',
          'provider' => $row['provider'],
          'image' => $row['image'],
          'brand' => $row['brand'],
          'provider' => $row['provider'],
        ]);
      } else {
        \App\Models\ProductInstant::whereId($existing->id)->update([
          'code' => $row['code'],
          'category' => $row['category'],
          'title' => $row['title'],
          'highlight' => $row['highlight'],
          'description' => $row['description'],
          'price' => $price ? $price->price : 0,
          'slug' => $row['slug'],
          'status' => $price && $price->price > 0 ? $row['status'] : 'inactive',
          'provider' => $row['provider'],
          'image' => $row['image'],
          'brand' => $row['brand'],
          'provider' => $row['provider'],
        ]);
      }
    }
    return [
      'success' => true
    ];
  }
  public static function reloadDigiflazz()
  {
    $digiflazz = new \App\Services\Digiflazz();
    $priceList = $digiflazz->getPriceList();
    if ($priceList['success']) {
      $insertedIds = [];
      foreach ($priceList['data'] as $row) {
        // if ($row['product_name'] == 'Free Fire 15 Diamond') {
        //   # code...
        // }
        if ($row['unlimited_stock'] === false && $row['stock'] === 0) {
          continue;
        }
        $exising = \App\Models\ProductInstant::whereCode($row['buyer_sku_code'])->first();
        if (!$exising) {
          $exising = \App\Models\ProductInstant::create([
            'code' => $row['buyer_sku_code'],
            'provider' => 'digiflazz',
            'category' => $row['category'],
            'title' => $row['product_name'],
            'highlight' => self::getCategoryHightlight($row['category'], $row['brand']),
            'description' => $row['desc'],
            'price' => $row['price'],
            'slug' => \Str::slug($row['buyer_sku_code'] . $row['product_name']),
            'stock' => $row['unlimited_stock'] ? -1 : $row['stock'],
            'status' => 'active',
            'image' => null,
            'brand' => $row['brand'],
            'provider_stock' => $row['unlimited_stock'] ? -1 : $row['stock'],
            'provider_buyer_status' => $row['buyer_product_status'] ? 'active' : 'inactive',
            'type' => $row['type'],
          ]);
        } else {
          \App\Models\ProductInstant::whereId($exising->id)->update([
            'provider' => 'digiflazz',
            'category' => $row['category'],
            'title' => $row['product_name'],
            'highlight' => self::getCategoryHightlight($row['category'], $row['brand']),
            'description' => $row['desc'],
            'price' => $row['price'],
            'slug' => \Str::slug($row['buyer_sku_code'] . $row['product_name']),
            'stock' => $row['unlimited_stock'] ? -1 : $row['stock'],
            'image' => $exising->image,
            'brand' => $row['brand'],
            'provider_stock' => $row['unlimited_stock'] ? -1 : $row['stock'],
            'status' => !$row['buyer_product_status'] ? 'inactive' : $exising->status,
            'provider_buyer_status' => $row['buyer_product_status'] ? 'active' : 'inactive',
            'type' => $row['type'],
          ]);
        }
        $insertedIds[] = $exising->id;
      }
      if (sizeof($insertedIds) > 0) {
        \App\Models\ProductInstant::where('provider', 'digiflazz')->whereNotIn('id', $insertedIds)->delete();
      } else {
        \App\Models\ProductInstant::where('provider', 'digiflazz')->delete();
      }
    }

    return [
      'success' => true,
      'data' => $priceList
    ];
  }
  public static function getPhoneBrand($phone)
  {
    // Pastikan hanya angka dan hilangkan karakter selain angka
    $phone = preg_replace('/\D/', '', $phone);

    // Pastikan nomor diawali dengan 08 atau 62
    if (preg_match('/^(08|62)/', $phone)) {
      // Konversi nomor yang diawali 62 ke format 08
      if (substr($phone, 0, 2) === '62') {
        $phone = '0' . substr($phone, 2);
      }
      // Ambil 2 digit pertama setelah "08"
      $prefix = substr($phone, 0, 4);

      // Mapping prefix ke provider
      $providers = [
        '0895' => 'TRI',
        '0896' => 'TRI',
        '0897' => 'TRI',
        '0898' => 'TRI',
        '0899' => 'TRI',
        '0811' => 'TELKOMSEL',
        '0812' => 'TELKOMSEL',
        '0813' => 'TELKOMSEL',
        '0821' => 'TELKOMSEL',
        '0822' => 'TELKOMSEL',
        '0823' => 'TELKOMSEL',
        '0851' => 'by.U',
        '0851' => 'TELKOMSEL',
        '0852' => 'TELKOMSEL',
        '0853' => 'TELKOMSEL',
        '0882 ' => 'SMARTFREN',
        '0858 ' => 'INDOSAT',
        '0881 ' => 'AXIS',
        '0877 ' => 'XL',
      ];
      return $providers[$prefix] ?? null;
    }

    return null;
  }
}
