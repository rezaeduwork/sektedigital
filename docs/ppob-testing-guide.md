# PPOB Parser Testing Guide

This document provides instructions for testing the PPOB (Payment Point Online Bank) service in the WhatsApp bot system.

## Prerequisites

1. A WhatsApp bot connected to the system
2. A store configured in the system with PPOB products
3. Access to the Tripay payment gateway
4. Ability to send messages to the WhatsApp bot

## Testing PPOB Message Flow

### 1. Product Selection Flow

1. **Start the PPOB service**:
   - Send: `ppob`
   - Expected: Bot responds with available PPOB categories (pulsa, data, game)

2. **Select a category**:
   - Send: `pulsa` (or `data` or `game`)
   - Expected: Bot responds with available brands for the selected category

3. **Select a brand**:
   - Send: `1` (or any number corresponding to a brand)
   - Expected: Bot responds with available products for the selected brand

4. **Select a product**:
   - Send: `1` (or any number corresponding to a product)
   - Expected: Bot responds with product details and prompts for customer number

5. **Enter customer number**:
   - Send: `081234567890` (or any valid number)
   - Expected: Bot confirms the number and shows available payment methods

6. **Select payment method**:
   - Send: `checkout QRIS` (or any available payment method)
   - Expected: Bot creates a transaction and sends payment instructions

### 2. Payment Status Testing

#### Case 1: Pending Payment

1. **Check status while payment is pending**:
   - Send: `status`
   - Expected: Bot responds with "Menunggu Pembayaran" message and payment instructions
   - Check: Session state remains as `waiting_payment`

2. **Verify session persistence**:
   - Verify in database: The session's `current_state` is `waiting_payment`
   - Verify in database: Session's `context_data` contains `transaction_id` and `payment_id`
   - Send other commands: Bot should recognize you're in the payment flow

#### Case 2: Successful Payment

1. **Simulate successful payment**:
   - Use admin panel or directly update the database:
     ```sql
     UPDATE payments 
     SET status = 'settlement', settlement_at = NOW() 
     WHERE id = [payment_id];
     ```

2. **Check status after payment is successful**:
   - Send: `status`
   - Expected: Bot responds with "Pembayaran Berhasil" message
   - Check: Session state is reset to `null`
   - Verify in database: The session's `context_data` no longer contains transaction_id and payment_id

#### Case 3: Payment Webhook

1. **Simulate payment gateway webhook**:
   - Use a tool like Postman to send a POST request to your webhook endpoint
   - Headers:
     ```
     X-Callback-Event: payment_status
     X-Callback-Signature: [calculated signature]
     ```
   - Body:
     ```json
     {
       "reference": "T123456789",
       "merchant_ref": "[your order number]",
       "status": "PAID",
       "is_closed_payment": 1
     }
     ```

2. **Check status after webhook**:
   - Send: `status`
   - Expected: Bot responds with "Pembayaran Berhasil" message
   - Check: Session state is reset to `null`

## How to Debug Issues

### Session State Issues

If the session state is not updating correctly:
1. Check the `current_state` value in the `whatsapp_customer_sessions` table
2. Verify that `context_data` contains the necessary transaction and payment information

### Payment Status Issues

If payment status is not updating correctly:
1. Check the `status` field in the `payments` table
2. Verify webhook is being received by checking the server logs
3. Ensure the webhook signature is being validated correctly

### Transaction Status Issues

If transaction status messages are incorrect:
1. Check the transaction data in the `transaction_whatsapps` table
2. Verify the `meta_data` field contains the correct product information
3. Check if payment_id is correctly linked in the transaction's meta_data

## Testing Debug Commands

For developers, the following debug scripts are available:

1. **PPOB Debug Console**:
   ```bash
   php ppob-debug.php
   ```
   This interactive script allows you to test each step of the PPOB flow individually

2. **Tripay Webhook Simulator**:
   ```bash
   php tripay-webhook-test.php
   ```
   This script simulates Tripay payment webhooks for testing payment status updates

## Integration Points

The PPOB parser interacts with several system components:

1. **MessageProcessor.php**: Registers the Ppob parser with the IoC container
2. **CustomCommandHandler.php**: Contains special handling for the status command in waiting_payment state
3. **WebhookController.php**: Processes payment gateway webhooks
4. **TransactionWhatsapp model**: Stores transaction data
5. **Payment model**: Stores payment data and status

Make sure all these components are working correctly to ensure the PPOB flow works properly.
