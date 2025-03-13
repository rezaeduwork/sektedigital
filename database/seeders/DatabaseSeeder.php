<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // ADMIN
    \App\Models\User::query()->delete();
    \App\Models\Store::query()->delete();
    $user = \App\Models\User::create([
      'name' => 'admin',
      'email' => 'admin@gmail.com',
      'role' => 'admin',
      'phone' => '000000000000',
      'password' => \Hash::make('12345678')
    ]);
    $user->store()->create([
      'name' => 'Dummy Store',
      'photo' => 'store.png',
      'status' => 'verified'
    ]);

    $createUser = $this->createUser([
      'name' => 'member',
      'email' => 'member@gmail.com',
      'role' => 'member',
      'phone' => '000000000000',
      'password' => \Hash::make('12345678')
    ]);
    $user1 = $createUser['user'];
    $store1 = $createUser['store'];

    $createUser2 = $this->createUser([
      'name' => 'member2',
      'email' => 'member2@gmail.com',
      'role' => 'member',
      'phone' => '0000000000002',
      'password' => \Hash::make('12345678')
    ]);

    $user2 = $createUser2['user'];
    $store2 = $createUser2['store'];

    \App\Models\Product::query()->delete();
    \App\Models\CategoryProduct::query()->delete();
    foreach (
      [
        [
          'icon' => 'verified-account.png',
          'name' => 'Akun'
        ],
        [
          'icon' => 'business.png',
          'name' => 'Bisnis'
        ],
        [
          'icon' => 'collection.png',
          'name' => 'Koleksi'
        ],
        [
          'icon' => 'marketing.png',
          'name' => 'Marketing'
        ],
        [
          'icon' => 'social-media.png',
          'name' => 'Sosial Media'
        ],
        [
          'icon' => 'vector.png',
          'name' => 'Design Grafis'
        ],
        [
          'icon' => 'gift-voucher.png',
          'name' => 'Top Up'
        ],
        [
          'icon' => 'video-games.png',
          'name' => 'Game',
        ],
        [
          'icon' => 'click.png',
          'name' => 'E-Book',
        ],
        [
          'icon' => 'software.png',
          'name' => 'Tools & Software',
        ],
        [
          'icon' => 'app-development.png',
          'name' => 'Software Development',
        ],
        [
          'icon' => 'audio.png',
          'name' => 'Audio',
        ],
        [
          'icon' => 'video-marketing.png',
          'name' => 'Video'
        ],
        [
          'icon' => 'other.png',
          'name' => 'Lainnya',
        ]
      ] as $row
    ) {
      $category = \App\Models\CategoryProduct::create($row);
      foreach (range(1, 10) as $rowProduct) {
        File::copy(public_path('category-icons.bak/' . $category->icon), storage_path('app/public/' . $category->icon));
        $this->createProduct($category, $store1);
        $this->createProduct($category, $store2);
      }
    }

    // USDIDR CONVERSION
    \App\Models\Currency::reloadRate('usdidr');
  }
  public function createUser($userParams)
  {
    $user = \App\Models\User::create($userParams);
    $store1 = $user->store()->create([
      'name' => $userParams['name'] . ' Store',
      'photo' => 'store.png',
      'status' => 'verified'
    ]);

    $productImages = \App\Models\ProductImage::all();
    // Delete each image from storage
    foreach ($productImages as $rowProductImage) {
      // Delete the file if it exists in storage
      if (Storage::exists('public/' . $rowProductImage->name)) {
        Storage::delete('public/' . $rowProductImage->name);
      }
    }

    return [
      'user' => $user,
      'store' => $store1
    ];
  }
  public function createProduct($category, $store)
  {
    $faker = Faker::create();

    $product = $category->products()->create([
      'title' => $faker->words(3, true),
      'description' => $faker->paragraph(10),
      'highlight' => $faker->paragraph(1),
      'price' => $faker->numberBetween(10000, 1000000),
      'slug' => Str::slug($faker->words(3, true)),
      'stock' => $faker->numberBetween(50, 1000),
      'store_id' => $store->id,
      'status' => 'active'
    ]);
    $mainImage = $this->fetchAndStoreRandomImage('main');
    if (!$mainImage) {
      $product->delete();
      return;
    }
    $product->images()->create([
      'name' => $mainImage,
      'type' => 'main'
    ]);

    // Handle additional product images
    $additionalImagesCount = mt_rand(2, 10);
    foreach (range(1, $additionalImagesCount) as $row) {
      $additionalImage = $this->fetchAndStoreRandomImage('additional');
      if (!$additionalImage) {
        $product->images()->create([
          'name' => $this->fetchAndStoreRandomImage('additional'),
          'type' => 'additional'
        ]);
      }
    }
  }
  // Helper function to fetch and store a random image
  private function fetchAndStoreRandomImage($type)
  {
    // Generate a random ID for Lorem Picsum
    $randomId = mt_rand(1, 1000);
    // Landscape aspect ratio (wider)
    $width = 1200;  // Increase width
    $height = 800;  // Keep a reasonable height

    // Get random image from Lorem Picsum (landscape)
    $imageUrl = "https://picsum.photos/{$width}/{$height}";

    $randomFilename = Str::random(20) . '.jpeg';
    $destinationPath = 'public/' . $randomFilename;

    try {
      // Download and store the image
      Storage::put($destinationPath, file_get_contents($imageUrl));
    } catch (\Throwable $th) {
      return null;
    }

    return $randomFilename;
  }
}
