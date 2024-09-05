<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    \App\Models\User::whereIn('email',['admin@gmail.com'])->delete();
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
    \App\Models\CategoryProduct::query()->delete();
    foreach ([
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
    ] as $row) {
      \App\Models\CategoryProduct::create($row);
    }
  }
}
