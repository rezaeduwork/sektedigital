<?php
// Test script to simulate PPOB parser with WhatsApp messages
// c:\Users\LENOVO\Documents\laragon\www\sektedigital\ppob-test.php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

use App\Models\Store;
use App\Models\WhatsappCustomer;
use App\Models\WhatsappCustomerSession;
use App\Models\Payment;
use App\Models\TransactionWhatsapp;
use App\Services\Whatsapp\MessageParser\Ppob;
use Illuminate\Support\Facades\Log;

// Set up the test environment
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test constants
$STORE_ID = 1; // Replace with an actual store ID from your database
$CUSTOMER_PHONE = '628123456789'; // Replace with a test phone number

function runTest() {
    global $STORE_ID, $CUSTOMER_PHONE;

    echo "Starting PPOB parser test...\n\n";

    // Get store
    $store = Store::find($STORE_ID);
    if (!$store) {
        echo "Error: Store with ID {$STORE_ID} not found\n";
        return;
    }

    echo "Testing with store: {$store->name}\n";

    // Get or create customer
    $customer = WhatsappCustomer::firstOrCreate(
        ['phone_number' => $CUSTOMER_PHONE],
        [
            'store_id' => $store->id,
            'name' => 'Test Customer',
            'is_active' => true
        ]
    );

    echo "Using customer: {$customer->name} ({$customer->phone_number})\n";

    // Create or reset session
    $session = WhatsappCustomerSession::updateOrCreate(
        [
            'store_id' => $store->id,
            'whatsapp_customer_id' => $customer->id
        ],
        [
            'context_data' => [],
            'current_state' => null,
            'last_active_at' => now()
        ]
    );

    echo "Session created/reset\n";

    // Initialize PPOB parser
    $ppobParser = new Ppob($store, $session);

    // Test 1: Get pulsa brand list
    echo "\n--- TEST 1: Get pulsa brand list ---\n";
    $brandList = $ppobParser->pulsaBrandList();
    echo $brandList;

    // Simulate user selecting a brand (assuming index 1 exists)
    echo "\n--- TEST 2: Select brand (simulated user input: 1) ---\n";
    $session->context_data['selected_index'] = 1;
    $session->save();

    // Test 2: Get product list for selected brand
    $productList = $ppobParser->productList();
    echo $productList;

    // Simulate user selecting a product (assuming index 1 exists)
    echo "\n--- TEST 3: Select product (simulated user input: 1) ---\n";
    $session->context_data['selected_product_index'] = 1;
    $session->save();

    // Test 3: Get product detail
    $productDetail = $ppobParser->productDetail();
    echo $productDetail;

    // Test 4: Get payment channels
    echo "\n--- TEST 4: Get payment channels ---\n";
    $paymentChannels = $ppobParser->paymentChannels();
    echo $paymentChannels;

    // Simulate user entering customer number
    echo "\n--- TEST 5: Enter customer number (simulated user input: 081234567890) ---\n";
    $session->context_data['customer_number'] = '081234567890';
    $session->save();

    // Simulate user selecting payment method (assuming QRIS exists)
    echo "\n--- TEST 6: Select payment method (QRIS) ---\n";
    $paymentMethods = $session->context_data['payment_channels'] ?? [];
    if (isset($paymentMethods['QRIS'])) {
        $session->context_data['selected_payment'] = $paymentMethods['QRIS'];
        $session->save();
    } else {
        echo "Payment method QRIS not found. Using first available method.\n";
        if (!empty($paymentMethods)) {
            $firstKey = array_key_first($paymentMethods);
            $session->context_data['selected_payment'] = $paymentMethods[$firstKey];
            $session->save();
            echo "Selected payment method: {$firstKey}\n";
        } else {
            echo "No payment methods available\n";
            return;
        }
    }

    // Test 5: Process payment
    echo "\n--- TEST 7: Process payment ---\n";
    try {
        $paymentResult = $ppobParser->processPayment();
        echo $paymentResult;

        // Now we should have a transaction and payment created
        if (!isset($session->context_data['transaction_id']) || !isset($session->context_data['payment_id'])) {
            echo "Error: Transaction or payment ID not found in session\n";
            return;
        }

        $transactionId = $session->context_data['transaction_id'];
        $paymentId = $session->context_data['payment_id'];

        echo "\nTransaction ID: {$transactionId}\n";
        echo "Payment ID: {$paymentId}\n";

        // Test 6: Check transaction status (should be pending)
        echo "\n--- TEST 8: Check transaction status (should be pending) ---\n";
        $statusResult = $ppobParser->transactionStatus();
        echo $statusResult;

        // Test 7: Simulate payment completion (manually update payment status)
        echo "\n--- TEST 9: Simulate payment completion ---\n";
        $payment = Payment::find($paymentId);
        if ($payment) {
            $payment->status = 'settlement';
            $payment->settlement_at = now();
            $payment->save();
            echo "Payment status updated to 'settlement'\n";

            // Test 8: Check transaction status again (should be completed)
            echo "\n--- TEST 10: Check transaction status (should be completed) ---\n";
            $statusResult = $ppobParser->transactionStatus();
            echo $statusResult;
        } else {
            echo "Error: Payment with ID {$paymentId} not found\n";
        }
    } catch (\Exception $e) {
        echo "Error processing payment: " . $e->getMessage() . "\n";
        echo "Stack trace: " . $e->getTraceAsString() . "\n";
    }
}

// Run the test
runTest();
