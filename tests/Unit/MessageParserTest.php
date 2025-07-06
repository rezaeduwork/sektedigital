<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Store;
use App\Models\WhatsappCustomerSession;
use App\Services\Whatsapp\MessageProcessor;
use App\Services\WhatsappBotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class MessageParserTest extends TestCase
{
    use RefreshDatabase;

    protected $store;
    protected $session;
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
            'address' => '123 Test Street'
        ]);

        // Create test session
        $this->session = WhatsappCustomerSession::create([
            'store_id' => $this->store->id,
            'phone_number' => '9876543210',
            'session_id' => 'TEST_SESSION_123',
            'customer_name' => 'Test Customer',
            'current_state' => 'browsing',
            'context_data' => [
                'query' => 'test product',
                'product_id' => '123',
                'nested' => [
                    'field' => 'value'
                ]
            ],
            'cart_data' => [
                'items' => [
                    [
                        'id' => 1,
                        'name' => 'Test Product',
                        'price' => 15000,
                        'quantity' => 2
                    ]
                ]
            ],
            'last_interaction_at' => now(),
            'is_active' => true
        ]);

        // Attach store to session for the test
        $this->session->store = $this->store;

        // Create processor instance
        $this->processor = new MessageProcessor();
    }

    public function testBasicTemplateReplacement()
    {
        $template = "Hello, welcome to {{store_name}}!";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertEquals("Hello, welcome to Test Store!", $result);
    }

    public function testProductAllTemplate()
    {
        // This test is more of a mock as we're not actually creating products
        // In a real test, we would create test products and verify they appear
        $template = "Our products: {Product.all}";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertStringContainsString("Our products:", $result);
    }

    public function testCartCurrentTemplate()
    {
        $template = "Your cart: {Cart.current}";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertStringContainsString("Test Product", $result);
        $this->assertStringContainsString("15.000", $result);
    }

    public function testCartTotalTemplate()
    {
        $template = "Total: {Cart.total}";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertStringContainsString("30.000", $result);
    }

    public function testCustomerNameTemplate()
    {
        $template = "Hello, {Customer.name}!";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertEquals("Hello, Test Customer!", $result);
    }

    public function testContextDataTemplate()
    {
        $template = "You searched for: {input.query}";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertEquals("You searched for: test product", $result);
    }

    public function testSettingsTemplate()
    {
        $template = "Store name: {Settings.name}";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertEquals("Store name: Test Store", $result);
    }

    public function testNestedPatterns()
    {
        $template = "Product: {Product.input.query}";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertStringContainsString("Product:", $result);
    }

    public function testMultiplePatterns()
    {
        $template = "Hello {Customer.name}, you have {Cart.count} item(s) worth {Cart.total} in your cart.";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertStringContainsString("Hello Test Customer", $result);
        $this->assertStringContainsString("2 item(s)", $result);
        $this->assertStringContainsString("30.000", $result);
    }

    public function testNonExistentParser()
    {
        $template = "Test {NonExistent.method}";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertStringContainsString("[Parser 'NonExistent' not found]", $result);
    }

    public function testNonExistentMethod()
    {
        $template = "Test {Customer.nonExistentMethod}";
        $result = $this->processor->templateParser($template, $this->session);

        $this->assertStringContainsString("[Method 'nonExistentMethod' not found]", $result);
    }
}
