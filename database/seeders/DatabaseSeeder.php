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
      'password' => \Hash::make('12345678')
    ]);
    $user->store()->create([
      'name' => 'Dummy Store',
      'photo' => 'dummy.png',
      'status' => 'verified'
    ]);
    \App\Models\CategoryProduct::query()->delete();
    foreach ([
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
        'icon' => 'app-development.png',
        'name' => 'Software',
      ],
      [
        'icon' => 'audio.png',
        'name' => 'Audio',
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
