<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // ADMIN
    \App\Models\User::whereIn('email', ['admin@gmail.com'])->delete();
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

    // USER
    \App\Models\User::whereIn('email', ['member@gmail.com'])->delete();
    $user = \App\Models\User::create([
      'name' => 'member',
      'email' => 'member@gmail.com',
      'role' => 'member',
      'phone' => '000000000000',
      'password' => \Hash::make('12345678')
    ]);
    $store1 = $user->store()->create([
      'name' => 'Dummy Store',
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
      foreach (range(1, 20) as $rowProduct) {
        $faker = Faker::create();

        $product = $category->products()->create([
          'title' => $faker->words(3, true),
          'description' => $faker->paragraph(10),
          'highlight' => $faker->paragraph(1),
          'price' => $faker->numberBetween(10000, 1000000),
          'slug' => Str::slug($faker->words(3, true)),
          'stock' => $faker->numberBetween(50, 1000),
          'store_id' => $store1->id,
          'status' => 'active'
        ]);

        // Handle main product image
        $sourcePath = public_path('assets/images/bg-violet.jpeg');
        $randomFilename = Str::random(20) . '.jpeg';
        $destinationPath = 'public/' . $randomFilename;

        Storage::put($destinationPath, file_get_contents($sourcePath));

        $product->images()->create([
          'name' => $randomFilename, // Remove 'public/' from stored path
          'type' => 'main'
        ]);

        // Handle additional product images
        $additionalImagesCount = mt_rand(2, 10);
        foreach (range(1, $additionalImagesCount) as $row) {
          $randomFilename = Str::random(20) . '.jpeg';
          $destinationPath = 'public/' . $randomFilename;

          Storage::put($destinationPath, file_get_contents($sourcePath));

          $product->images()->create([
            'name' => $randomFilename,
            'type' => 'additional'
          ]);
        }
      }
    }
  }
}
