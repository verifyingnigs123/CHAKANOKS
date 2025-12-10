<?php

namespace App\Libraries;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

/**
 * PayPal Payment Service
 * 
 * This service handles PayPal payment processing using PayPal Checkout SDK.
 * Supports creating orders, capturing payments, and verifying transactions.
 */
class PayPalService
{
    protected $clientId;
    protected $clientSecret;
    protected $mode; // 'sandbox' or 'live'
    protected $client;

    public function __construct()
    {
        // Load PayPal configuration from settings or environment
        $this->clientId = getenv('PAYPAL_CLIENT_ID') ?: '';
        $this->clientSecret = getenv('PAYPAL_CLIENT_SECRET') ?: '';
        $this->mode = getenv('PAYPAL_MODE') ?: 'sandbox';
        
        // Initialize PayPal HTTP Client
        if ($this->isConfigured()) {
            $environment = $this->mode === 'live' 
                ? new ProductionEnvironment($this->clientId, $this->clientSecret)
                : new SandboxEnvironment($this->clientId, $this->clientSecret);
            
            $this->client = new PayPalHttpClient($environment);
        }
    }

    /**
     * Create a PayPal order
     * 
     * @param float $amount Payment amount
     * @param string $currency Currency code (default: USD)
     * @param string $description Payment description
     * @param string $returnUrl URL to redirect after successful payment
     * @param string $cancelUrl URL to redirect if payment is cancelled
     * @param array $items Optional array of items for detailed breakdown
     * @return array Order response with approval URL and order ID
     */
    public function createPayment($amount, $currency = 'USD', $description = '', $returnUrl = '', $cancelUrl = '', $items = [])
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'PayPal is not configured. Please set PAYPAL_CLIENT_ID and PAYPAL_CLIENT_SECRET.',
            ];
        }

        try {
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            
            // Build order body
            $orderBody = [
                'intent' => 'CAPTURE',
                'application_context' => [
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                    'brand_name' => 'ChakaNoks SCMS',
                    'landing_page' => 'BILLING',
                    'user_action' => 'PAY_NOW'
                ],
                'purchase_units' => [
                    [
                        'reference_id' => 'PUHF',
                        'description' => $description,
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($amount, 2, '.', '')
                        ]
                    ]
                ]
            ];

            // Add items if provided - with proper breakdown
            if (!empty($items)) {
                $paypalItems = [];
                $itemTotal = 0;
                foreach ($items as $item) {
                    $itemPrice = number_format($item['price'], 2, '.', '');
                    $itemQty = (int)$item['quantity'];
                    $itemTotal += ($itemPrice * $itemQty);
                    
                    $paypalItems[] = [
                        'name' => substr($item['name'], 0, 127), // PayPal max 127 chars
                        'unit_amount' => [
                            'currency_code' => $currency,
                            'value' => $itemPrice
                        ],
                        'quantity' => (string)$itemQty
                    ];
                }
                
                // Update amount with breakdown (required when items are specified)
                $orderBody['purchase_units'][0]['amount'] = [
                    'currency_code' => $currency,
                    'value' => number_format($amount, 2, '.', ''),
                    'breakdown' => [
                        'item_total' => [
                            'currency_code' => $currency,
                            'value' => number_format($itemTotal, 2, '.', '')
                        ]
                    ]
                ];
                $orderBody['purchase_units'][0]['items'] = $paypalItems;
            }

            $request->body = $orderBody;

            // Log the request for debugging
            log_message('info', 'PayPal Order Request: ' . json_encode($orderBody));

            // Execute request
            $response = $this->client->execute($request);

            // Log the response for debugging
            log_message('info', 'PayPal Order Response: ' . json_encode($response->result));

            // Get approval URL
            $approvalUrl = null;
            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    $approvalUrl = $link->href;
                    break;
                }
            }

            log_message('info', 'PayPal Approval URL: ' . $approvalUrl);

            return [
                'success' => true,
                'order_id' => $response->result->id,
                'approval_url' => $approvalUrl,
                'status' => $response->result->status,
                'order' => $response->result,
            ];

        } catch (\Exception $e) {
            log_message('error', 'PayPal Order Creation Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Order creation failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Capture a PayPal order after user approval
     * 
     * @param string $orderId PayPal order ID
     * @return array Capture result
     */
    public function executePayment($orderId, $payerId = null)
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'PayPal is not configured.',
            ];
        }

        try {
            $request = new OrdersCaptureRequest($orderId);
            $request->prefer('return=representation');

            // Execute capture
            $response = $this->client->execute($request);

            if ($response->result->status === 'COMPLETED') {
                $capture = $response->result->purchase_units[0]->payments->captures[0];
                
                return [
                    'success' => true,
                    'order_id' => $response->result->id,
                    'transaction_id' => $capture->id,
                    'payer_id' => $response->result->payer->payer_id ?? '',
                    'amount' => $capture->amount->value,
                    'currency' => $capture->amount->currency_code,
                    'status' => $response->result->status,
                    'order' => $response->result,
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Order capture failed. Status: ' . $response->result->status,
                ];
            }

        } catch (\Exception $e) {
            log_message('error', 'PayPal Order Capture Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Order capture failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get order details by order ID
     * 
     * @param string $orderId PayPal order ID
     * @return array Order details
     */
    public function getPaymentDetails($orderId)
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'PayPal is not configured.',
            ];
        }

        try {
            $request = new OrdersGetRequest($orderId);
            $response = $this->client->execute($request);
            
            return [
                'success' => true,
                'order_id' => $response->result->id,
                'status' => $response->result->status,
                'intent' => $response->result->intent,
                'payer' => $response->result->payer,
                'purchase_units' => $response->result->purchase_units,
                'order' => $response->result,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to get order details: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify a PayPal transaction (simplified - checks order status)
     * 
     * @param string $orderId PayPal order ID
     * @return array Verification result
     */
    public function verifyTransaction($orderId)
    {
        $details = $this->getPaymentDetails($orderId);
        
        if ($details['success']) {
            return [
                'success' => true,
                'verified' => $details['status'] === 'COMPLETED',
                'order_id' => $details['order_id'],
                'status' => $details['status'],
                'order' => $details['order'],
            ];
        }
        
        return $details;
    }

    /**
     * Get PayPal configuration status
     * 
     * @return bool True if PayPal is configured
     */
    public function isConfigured()
    {
        return !empty($this->clientId) && !empty($this->clientSecret);
    }

    /**
     * Get PayPal mode (sandbox or live)
     * 
     * @return string PayPal mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Convert PHP amount to USD (simplified conversion)
     * Note: In production, use a real currency conversion API
     * 
     * @param float $phpAmount Amount in PHP
     * @return float Amount in USD
     */
    public function convertPHPToUSD($phpAmount)
    {
        // Simplified conversion rate (1 USD = 56 PHP approximately)
        // In production, use a real-time currency conversion API
        $conversionRate = 0.018; // 1 PHP = 0.018 USD (approximate)
        $usdAmount = round($phpAmount * $conversionRate, 2);
        
        // PayPal requires minimum $1.00 for transactions
        return max($usdAmount, 1.00);
    }
}