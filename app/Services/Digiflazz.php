<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Digiflazz
{
  private $username;
  private $apiKey;
  private $platformKey;
  private $baseUrl = 'https://api.digiflazz.com/v1/';

  public function __construct()
  {
    $this->apiKey = config('services.digiflazz.api_key');
    $this->username = config('services.digiflazz.username');
    $this->platformKey = config('services.digiflazz.platform_key');
  }
  function createSignature($string)
  {
    return md5($this->username . $this->apiKey . $string);
  }
  function getSecretKey()
  {
    return $this->platformKey;
  }
  function getPriceList()
  {
    try {
      $response = Http::timeout(10)->post($this->baseUrl . 'price-list', [
        'cmd' => 'prepaid',
        'username' => $this->username,
        'sign' => $this->createSignature('pricelist'),
      ]);
      if ($response->failed()) {
        throw new \Exception('Request failed: ' . $response->body());
      }
      $data = $response->json();
      return [
        'success' => true,
        'data' => $data['data'],
      ];
    } catch (\Throwable $th) {
      //throw $th;
    } catch (RequestException $e) {
      return [
        'success' => false,
        'message' => 'Request error: ' . $e->getMessage(),
      ];
    } catch (\Exception $e) {
      return [
        'success' => false,
        'message' => $e->getMessage(),
      ];
    }
  }
  public function checkBalances()
  {
    try {
      $response = Http::timeout(10)->post($this->baseUrl . 'cek-saldo', [
        'cmd' => 'deposit',
        'username' => $this->username,
        'sign' => $this->createSignature('depo'),
      ]);
      if ($response->failed()) {
        throw new \Exception('Request failed: ' . $response->body());
      }
      $data = $response->json();

      return [
        'success' => true,
        'data' => $data['data'],
      ];
    } catch (\Throwable $th) {
      return [
        'success' => false,
        'message' => $th->getMessage(),
      ];
    } catch (RequestException $e) {
      return [
        'success' => false,
        'message' => 'Request error: ' . $e->getMessage(),
      ];
    } catch (\Exception $e) {
      return [
        'success' => false,
        'message' => $e->getMessage(),
      ];
    }
  }
  public function createTransaction($tx)
  {
    try {
      $product = $tx->product;
      $data = $tx->data;
      $customerNo = collect($data)->filter(function ($value, $key) use ($product) {
        if (strtolower($product->category) == 'games') {
          if (strtolower($product->brand) == 'mobile legends') {
            return in_array($value['name'], ['account_id', 'zone_id']);
          } else {
            return in_array($value['name'], ['account_id']);
          }
        } else if (in_array($product->category, ['Pulsa', 'Data'])) {
          return in_array($value['name'], ['phone']);
        } else if (in_array($product->category, ['Token'])) {
          return in_array($value['name'], ['phone']);
        } else if (in_array($product->category, ['E-Wallet'])) {
          return in_array($value['name'], ['phone']);
        }
      })->map(function ($value, $key) {
        return $value['value'];
      })->implode('');
      $buyerskuCode = $tx->product->code;
      if (config('app.env') == 'local') {
        $buyerskuCode = 'xld10';
        $customerNo = '087800001230';
      }

      $payload = [
        'username' => $this->username,
        "buyer_sku_code" => $buyerskuCode,
        "customer_no" => $customerNo,
        "ref_id" => (string)$tx->id,
        'sign' => $this->createSignature($tx->id),
      ];
      $response = Http::timeout(10)->post($this->baseUrl . 'transaction', $payload);
      if ($response->failed()) {
        throw new \Exception('Request failed: ' . $response->body());
      }
      $data = $response->json();
      if ($data['data']['status'] == 'Gagal') {
        return [
          'success' => false,
          'data' => $data['data'],
        ];
      } else if ($data['data']['status'] == 'Pending') {
        return [
          'success' => false,
          'data' => $data['data'],
        ];
      }
      return [
        'success' => true,
        'data' => $data['data'],
      ];
    } catch (\Throwable $th) {
      if (config('app.env') == 'local') {
        throw $th;
      }
    } catch (RequestException $e) {
      return [
        'success' => false,
        'message' => 'Request error: ' . $e->getMessage(),
      ];
    } catch (\Exception $e) {
      return [
        'success' => false,
        'message' => $e->getMessage(),
      ];
    }
  }
}
