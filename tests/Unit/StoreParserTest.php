<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Store;
use App\Services\Whatsapp\MessageProcessor;
use App\Services\WhatsappBotService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreParserTest extends TestCase
{
    use RefreshDatabase;

    protected $store;
    protected $processor;

    public function setUp(): void
    {
        parent::setUp();

        // Initialize the WhatsappBotService to register all services in the container
        new WhatsappBotService();

        // Create test store
        $this->store = Store::factory()->create([
            'name' => 'Test Store',
            'email' => 'test@example.com',
            'phone' => '123456789',
            'address' => '123 Test Street',
            'description' => 'This is a test store description',
            'hours' => 'Mon-Fri: 9AM-5PM'
        ]);

        // Create mock session
        $session = new \App\Models\WhatsappCustomerSession([
            'store_id' => $this->store->id,
            'phone_number' => '9876543210',
            'session_id' => 'TEST_SESSION_123',
            'current_state' => 'browsing',
            'context_data' => [],
            'last_interaction_at' => now(),
        ]);
        $session->store = $this->store;

        // Create processor instance
        $this->processor = new MessageProcessor();

        // Set expected date/time for tests
        Carbon::setTestNow(Carbon::create(2025, 5, 24, 14, 30));
    }

    public function testStoreName()
    {
        $template = "Welcome to {Store.name}!";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertEquals("Welcome to Test Store!", $result);
    }

    public function testStoreTime()
    {
        $template = "Current time: {Store.time}";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertEquals("Current time: 14:30", $result);
    }

    public function testStoreDate()
    {
        $template = "Today's date: {Store.date}";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertEquals("Today's date: 24 May 2025", $result);
    }

    public function testStoreInfo()
    {
        $template = "{Store.info}";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertStringContainsString("*Test Store*", $result);
        $this->assertStringContainsString("14:30", $result);
        $this->assertStringContainsString("24 May 2025", $result);
        $this->assertStringContainsString("This is a test store description", $result);
    }

    public function tearDown(): void
    {
        Carbon::setTestNow(); // Clear mock time
        parent::tearDown();
    }
}
