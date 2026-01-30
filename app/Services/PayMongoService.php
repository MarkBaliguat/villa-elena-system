<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class PayMongoService
{
    protected $client;
    protected $secretKey;
    protected $baseUrl = 'https://api.paymongo.com/v1';

    public function __construct()
    {
        $this->secretKey = config('services.paymongo.secret_key');
        
        if (!$this->secretKey) {
            throw new \Exception('PayMongo secret key not configured');
        }
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':')
            ],
            'timeout' => 30,
            'connect_timeout' => 10
        ]);
    }

    /**
     * Create a payment intent
     */
    public function createPaymentIntent($amount, $description)
    {
        try {
            Log::info('Creating PayMongo Payment Intent', [
                'amount' => $amount,
                'description' => $description
            ]);
            
            $response = $this->client->post('/payment_intents', [
                'json' => [
                    'data' => [
                        'attributes' => [
                            'amount' => (int)($amount * 100), // Convert to centavos
                            'payment_method_allowed' => ['gcash'],
                            'payment_method_options' => [
                                'gcash' => [
                                    'redirect' => [
                                        'success' => route('customer.payment.success'),
                                        'failed' => route('customer.payment.failed')
                                    ]
                                ]
                            ],
                            'currency' => 'PHP',
                            'description' => $description,
                            'statement_descriptor' => 'Villa Elena Resort'
                        ]
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            Log::info('Payment Intent Created Successfully', ['intent_id' => $result['data']['id'] ?? 'unknown']);
            
            return $result;
            
        } catch (RequestException $e) {
            Log::error('PayMongo Payment Intent Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            
            // Get error response body if available
            if ($e->hasResponse()) {
                $errorBody = (string) $e->getResponse()->getBody();
                Log::error('PayMongo Error Response', ['body' => $errorBody]);
            }
            
            throw new \Exception('Failed to create payment intent: ' . $e->getMessage());
            
        } catch (GuzzleException $e) {
            Log::error('PayMongo Payment Intent Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            
            throw new \Exception('Failed to create payment intent: ' . $e->getMessage());
        }
    }

    /**
     * Create a payment method
     */
    public function createPaymentMethod()
    {
        try {
            Log::info('Creating PayMongo Payment Method');
            
            $response = $this->client->post('/payment_methods', [
                'json' => [
                    'data' => [
                        'attributes' => [
                            'type' => 'gcash'
                        ]
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            Log::info('Payment Method Created Successfully', ['method_id' => $result['data']['id'] ?? 'unknown']);
            
            return $result;
            
        } catch (RequestException $e) {
            Log::error('PayMongo Payment Method Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            
            // Get error response body if available
            if ($e->hasResponse()) {
                $errorBody = (string) $e->getResponse()->getBody();
                Log::error('PayMongo Error Response', ['body' => $errorBody]);
            }
            
            throw new \Exception('Failed to create payment method: ' . $e->getMessage());
            
        } catch (GuzzleException $e) {
            Log::error('PayMongo Payment Method Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            
            throw new \Exception('Failed to create payment method: ' . $e->getMessage());
        }
    }

    /**
     * Attach payment method to payment intent
     */
    public function attachPaymentMethod($paymentIntentId, $paymentMethodId, $returnUrl)
    {
        try {
            Log::info('Attaching Payment Method to Intent', [
                'intent_id' => $paymentIntentId,
                'method_id' => $paymentMethodId
            ]);
            
            $response = $this->client->post("/payment_intents/{$paymentIntentId}/attach", [
                'json' => [
                    'data' => [
                        'attributes' => [
                            'payment_method' => $paymentMethodId,
                            'return_url' => $returnUrl
                        ]
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            Log::info('Payment Method Attached Successfully');
            
            return $result;
            
        } catch (RequestException $e) {
            Log::error('PayMongo Attach Payment Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'intent_id' => $paymentIntentId
            ]);
            
            // Get error response body if available
            if ($e->hasResponse()) {
                $errorBody = (string) $e->getResponse()->getBody();
                Log::error('PayMongo Error Response', ['body' => $errorBody]);
            }
            
            throw new \Exception('Failed to attach payment method: ' . $e->getMessage());
            
        } catch (GuzzleException $e) {
            Log::error('PayMongo Attach Payment Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'intent_id' => $paymentIntentId
            ]);
            
            throw new \Exception('Failed to attach payment method: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve payment intent
     */
    public function retrievePaymentIntent($paymentIntentId)
    {
        try {
            Log::info('Retrieving Payment Intent', ['intent_id' => $paymentIntentId]);
            
            $response = $this->client->get("/payment_intents/{$paymentIntentId}");
            $result = json_decode($response->getBody()->getContents(), true);
            
            Log::info('Payment Intent Retrieved', [
                'status' => $result['data']['attributes']['status'] ?? 'unknown'
            ]);
            
            return $result;
            
        } catch (RequestException $e) {
            Log::error('PayMongo Retrieve Payment Intent Error', [
                'message' => $e->getMessage(),
                'intent_id' => $paymentIntentId
            ]);
            
            // Get error response body if available
            if ($e->hasResponse()) {
                $errorBody = (string) $e->getResponse()->getBody();
                Log::error('PayMongo Error Response', ['body' => $errorBody]);
            }
            
            throw new \Exception('Failed to retrieve payment intent: ' . $e->getMessage());
            
        } catch (GuzzleException $e) {
            Log::error('PayMongo Retrieve Payment Intent Error', [
                'message' => $e->getMessage(),
                'intent_id' => $paymentIntentId
            ]);
            
            throw new \Exception('Failed to retrieve payment intent: ' . $e->getMessage());
        }
    }
}