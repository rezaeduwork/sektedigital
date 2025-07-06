<?php
// Test script to simulate Tripay webhook callback
// c:\Users\LENOVO\Documents\laragon\www\sektedigital\tripay-webhook-test.php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

use App\Models\Payment;
use App\Models\TransactionWhatsapp;
use App\Models\WhatsappCustomerSession;
use Illuminate\Support\Facades\Log;

// Set up the test environment
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test payment ID - replace with a valid payment ID from your database
$paymentId = 1; // Replace with an actual pending payment ID

function testPaymentUpdate($paymentId, $newStatus) {
    echo "Testing payment update for payment ID: {$paymentId}\n";

    // Find payment
    $payment = Payment::find($paymentId);
    if (!$payment) {
        echo "Error: Payment with ID {$paymentId} not found\n";
        return;
    }

    echo "Current payment status: {$payment->status}\n";

    // Get transaction data
    $transactionData = json_decode($payment->data, true);
    $transactionId = $transactionData['transaction_id'] ?? null;

    if (!$transactionId) {
        echo "Error: Transaction ID not found in payment data\n";
        return;
    }

    $transaction = TransactionWhatsapp::find($transactionId);
    if (!$transaction) {
        echo "Error: Transaction with ID {$transactionId} not found\n";
        return;
    }

    echo "Found transaction: #{$transaction->id} (Order: {$transaction->order_number})\n";

    // Find any sessions that might be referencing this transaction
    $sessions = WhatsappCustomerSession::where('context_data->transaction_id', $transactionId)->get();

    echo "Found " . count($sessions) . " sessions referencing this transaction\n";

    // Update payment status
    $payment->status = $newStatus;
    if ($newStatus === 'settlement') {
        $payment->settlement_at = now();
    }
    $payment->save();

    echo "Updated payment status to: {$newStatus}\n";

    // Display session states after update
    foreach ($sessions as $session) {
        echo "Session #{$session->id} for customer #{$session->whatsapp_customer_id}:\n";
        echo "  - Current state: {$session->current_state}\n";
        echo "  - Has transaction ID in context: " . (isset($session->context_data['transaction_id']) ? "Yes" : "No") . "\n";
        echo "  - Has payment ID in context: " . (isset($session->context_data['payment_id']) ? "Yes" : "No") . "\n";
    }

    return $payment;
}

// 1. Test pending to settlement transition
echo "\n--- TEST: Payment transition from pending to settlement ---\n";
$payment = testPaymentUpdate($paymentId, 'settlement');

// If the test was successful, let's simulate checking the status via the parser
if ($payment) {
    // Find a session for this transaction
    $transactionData = json_decode($payment->data, true);
    $transactionId = $transactionData['transaction_id'] ?? null;

    if ($transactionId) {
        $session = WhatsappCustomerSession::where('context_data->transaction_id', $transactionId)->first();

        if ($session) {
            echo "\n--- TEST: Checking transaction status via Ppob parser ---\n";

            // Get store
            $store = $session->store;

            // Create parser instance
            $ppobParser = new App\Services\Whatsapp\MessageParser\Ppob($store, $session);

            // Check status
            $statusResult = $ppobParser->transactionStatus();
            echo "Status check result:\n{$statusResult}\n";

            // Check if session state was reset
            echo "\nSession state after status check: {$session->current_state}\n";
            echo "Transaction ID still in context: " . (isset($session->context_data['transaction_id']) ? "Yes" : "No") . "\n";
        } else {
            echo "No session found for transaction ID {$transactionId}\n";
        }
    }
}

echo "\nTest completed.\n";
