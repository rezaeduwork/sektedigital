<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedWhatsappBotCommandCategories extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'whatsapp:seed-bot-command-categories';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Seed WhatsApp bot command categories';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $this->info('Seeding WhatsApp bot command categories...');

    // Run the seeder
    $seeder = new \Database\Seeders\WhatsappBotCommandCategorySeeder();
    $seeder->run();

    $this->info('WhatsApp bot command categories seeded successfully!');

    // If commands already exist, we can map them to categories
    $commandCount = \App\Models\WhatsappBotCommand::where('category_id', '!=', null)->count();
    $this->info($commandCount . ' commands mapped to categories.');

    return 0;
  }
}
