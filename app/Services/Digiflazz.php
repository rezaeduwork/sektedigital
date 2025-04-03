<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Digiflazz
{
  private $username;
  private $apiKey;
  private $baseUrl = 'https://api.digiflazz.com/v1/';

  public function __construct()
  {
    $this->apiKey = config('services.digiflazz.api_key');
    $this->username = config('services.digiflazz.username');
  }
  function createSignature($string)
  {
    return md5($this->username . $this->apiKey . $string);
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
}
