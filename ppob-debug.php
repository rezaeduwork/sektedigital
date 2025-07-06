<?php
// Simple test script to debug the Ppob parser and transaction status
// c:\Users\LENOVO\Documents\laragon\www\sektedigital\ppob-debug.php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

use App\Models\Store;
use App\Models\WhatsappCustomer;
use App\Models\WhatsappCustomerSession;
use App\Models\Payment;
use App\Models\TransactionWhatsapp;
use App\Services\Whatsapp\MessageParser\Ppob;

// Set up the test environment
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test parameters
$storeId = 1; // Replace with actual store ID
$transactionId = null; // Fill in if you want to test an existing transaction
$paymentId = null; // Fill in if you want to test an existing payment
$phone = "628123456789"; // Test phone number

// Get store
$store = Store::find($storeId);
if (!$store) {
    die("Store not found with ID: {$storeId}\n");
}

echo "Using store: {$store->name} (ID: {$store->id})\n\n";

// Get customer or create a test customer
$customer = WhatsappCustomer::firstOrCreate(
    ['phone_number' => $phone, 'store_id' => $store->id],
    [
        'name' => 'Test Customer',
        'is_active' => true
    ]
);

echo "Using customer: {$customer->name} ({$customer->phone_number})\n\n";

// Get or create session
$session = WhatsappCustomerSession::firstOrCreate(
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

echo "Using session ID: {$session->id}\n";
echo "Current state: " . ($session->current_state ?? 'null') . "\n";
echo "Context data: " . json_encode($session->context_data) . "\n\n";

// Create Ppob parser instance
$ppobParser = new Ppob($store, $session);

// Command menu
echo "Available commands:\n";
echo "1 - List pulsa brands\n";
echo "2 - List game brands\n";
echo "3 - List data brands\n";
echo "4 - Select brand (provide index)\n";
echo "5 - List products\n";
echo "6 - Select product (provide index)\n";
echo "7 - Show product detail\n";
echo "8 - List payment channels\n";
echo "9 - Enter customer number\n";
echo "10 - Select payment method (provide code)\n";
echo "11 - Process payment\n";
echo "12 - Check transaction status\n";
echo "13 - Simulate payment completion\n";
echo "14 - Exit\n\n";

// Main loop
while (true) {
    echo "Enter command (1-14): ";
    $cmd = trim(fgets(STDIN));

    if ($cmd == '14') {
        echo "Exiting...\n";
        break;
    }

    try {
        switch ($cmd) {
            case '1':
                echo "\n--- Listing pulsa brands ---\n";
                $result = $ppobParser->pulsaBrandList();
                echo $result . "\n";
                break;

            case '2':
                echo "\n--- Listing game brands ---\n";
                $result = $ppobParser->gameBrandList();
                echo $result . "\n";
                break;

            case '3':
                echo "\n--- Listing data brands ---\n";
                $result = $ppobParser->dataBrandList();
                echo $result . "\n";
                break;

            case '4':
                echo "Enter brand index: ";
                $index = trim(fgets(STDIN));
                $session->context_data['selected_index'] = $index;
                $session->save();
                echo "Selected brand index: {$index}\n";
                break;

            case '5':
                echo "\n--- Listing products ---\n";
                $result = $ppobParser->productList();
                echo $result . "\n";
                break;

            case '6':
                echo "Enter product index: ";
                $index = trim(fgets(STDIN));
                $session->context_data['selected_product_index'] = $index;
                $session->save();
                echo "Selected product index: {$index}\n";
                break;

            case '7':
                echo "\n--- Showing product detail ---\n";
                $result = $ppobParser->productDetail();
                echo $result . "\n";
                break;

            case '8':
                echo "\n--- Listing payment channels ---\n";
                $result = $ppobParser->paymentChannels();
                echo $result . "\n";
                break;

            case '9':
                echo "Enter customer number: ";
                $number = trim(fgets(STDIN));
                $session->context_data['customer_number'] = $number;
                $session->save();
                echo "Customer number set to: {$number}\n";
                break;

            case '10':
                echo "Enter payment method code: ";
                $code = trim(fgets(STDIN));
                if (isset($session->context_data['payment_channels'][$code])) {
                    $session->context_data['selected_payment'] = $session->context_data['payment_channels'][$code];
                    $session->save();
                    echo "Selected payment method: {$code}\n";
                } else {
                    echo "Payment method not found. Available methods: " . implode(", ", array_keys($session->context_data['payment_channels'] ?? [])) . "\n";
                }
                break;

            case '11':
                echo "\n--- Processing payment ---\n";
                $result = $ppobParser->processPayment();
                echo $result . "\n";

                // Save transaction and payment IDs for further testing
                $transactionId = $session->context_data['transaction_id'] ?? null;
                $paymentId = $session->context_data['payment_id'] ?? null;

                echo "Transaction ID: " . ($transactionId ?? 'Not set') . "\n";
                echo "Payment ID: " . ($paymentId ?? 'Not set') . "\n";
                break;

            case '12':
                echo "\n--- Checking transaction status ---\n";
                $result = $ppobParser->transactionStatus();
                echo $result . "\n";
                break;

            case '13':
                if ($paymentId) {
                    echo "Simulating payment completion for payment ID: {$paymentId}\n";
                    $payment = Payment::find($paymentId);
                    if ($payment) {
                        $payment->status = 'settlement';
                        $payment->settlement_at = now();
                        $payment->save();
                        echo "Payment status updated to 'settlement'\n";
                    } else {
                        echo "Payment not found with ID: {$paymentId}\n";
                    }
                } else {
                    echo "No payment ID available. Process a payment first or specify an existing payment ID.\n";
                }
                break;

            default:
                echo "Invalid command\n";
                break;
        }
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }

    echo "\nSession state: " . ($session->current_state ?? 'null') . "\n";
    echo "Context data: " . json_encode($session->context_data) . "\n\n";
}
