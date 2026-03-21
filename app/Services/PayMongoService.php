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
    protected $publicKey;
    protected $baseUrl = 'https://api.paymongo.com/v1';

    public function __construct()
    {
        $this->secretKey = config('services.paymongo.secret_key');
        $this->publicKey = config('services.paymongo.public_key');

        if (!$this->secretKey) {
            throw new \Exception('PayMongo secret key not configured');
        }

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':')
            ],
            'timeout'         => 30,
            'connect_timeout' => 10
        ]);
    }

    // ═══════════════════════════════════════════
    //  GCASH — Create Payment Intent
    // ═══════════════════════════════════════════
    public function createPaymentIntent($amount, $description)
    {
        try {
            Log::info('Creating PayMongo Payment Intent (GCash)', [
                'amount'      => $amount,
                'description' => $description
            ]);

            $response = $this->client->post('/payment_intents', [
                'json' => [
                    'data' => [
                        'attributes' => [
                            'amount'                  => (int)($amount * 100),
                            'payment_method_allowed'  => ['gcash'],
                            'payment_method_options'  => [
                                'gcash' => [
                                    'redirect' => [
                                        'success' => route('customer.payment.success'),
                                        'failed'  => route('customer.payment.failed')
                                    ]
                                ]
                            ],
                            'currency'             => 'PHP',
                            'description'          => $description,
                            'statement_descriptor' => 'Villa Elena Resort'
                        ]
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            Log::info('GCash Payment Intent Created', ['intent_id' => $result['data']['id'] ?? 'unknown']);
            return $result;

        } catch (RequestException $e) {
            $this->logRequestError('GCash Payment Intent', $e);
            throw new \Exception('Failed to create payment intent: ' . $e->getMessage());
        } catch (GuzzleException $e) {
            Log::error('GCash Payment Intent Error', ['message' => $e->getMessage()]);
            throw new \Exception('Failed to create payment intent: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════
    //  GCASH — Create Payment Method
    // ═══════════════════════════════════════════
    public function createPaymentMethod()
    {
        try {
            Log::info('Creating PayMongo Payment Method (GCash)');

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
            Log::info('GCash Payment Method Created', ['method_id' => $result['data']['id'] ?? 'unknown']);
            return $result;

        } catch (RequestException $e) {
            $this->logRequestError('GCash Payment Method', $e);
            throw new \Exception('Failed to create payment method: ' . $e->getMessage());
        } catch (GuzzleException $e) {
            Log::error('GCash Payment Method Error', ['message' => $e->getMessage()]);
            throw new \Exception('Failed to create payment method: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════
    //  CARD — Create Payment Intent
    // ═══════════════════════════════════════════
    public function createCardPaymentIntent($amount, $description)
    {
        try {
            Log::info('Creating PayMongo Payment Intent (Card)', [
                'amount'      => $amount,
                'description' => $description
            ]);

            $response = $this->client->post('/payment_intents', [
                'json' => [
                    'data' => [
                        'attributes' => [
                            'amount'                 => (int)($amount * 100),
                            'payment_method_allowed' => ['card'],
                            'currency'               => 'PHP',
                            'description'            => $description,
                            'statement_descriptor'   => 'Villa Elena Resort',
                            'capture_type'           => 'automatic'
                        ]
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            Log::info('Card Payment Intent Created', ['intent_id' => $result['data']['id'] ?? 'unknown']);
            return $result;

        } catch (RequestException $e) {
            $this->logRequestError('Card Payment Intent', $e);
            throw new \Exception('Failed to create card payment intent: ' . $e->getMessage());
        } catch (GuzzleException $e) {
            Log::error('Card Payment Intent Error', ['message' => $e->getMessage()]);
            throw new \Exception('Failed to create card payment intent: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════
    //  CARD — Create Payment Method (with card details)
    //  NOTE: Card details are tokenized on the FRONTEND
    //        using PayMongo.js / fetch with the PUBLIC KEY.
    //        The backend receives only the payment_method_id.
    //        This method is a helper if needed server-side.
    // ═══════════════════════════════════════════
    public function createCardPaymentMethod(array $cardDetails, array $billingDetails)
    {
        try {
            Log::info('Creating PayMongo Card Payment Method');

            $response = $this->client->post('/payment_methods', [
                'json' => [
                    'data' => [
                        'attributes' => [
                            'type'    => 'card',
                            'details' => [
                                'card_number' => $cardDetails['card_number'],
                                'exp_month'   => (int)$cardDetails['exp_month'],
                                'exp_year'    => (int)$cardDetails['exp_year'],
                                'cvc'         => $cardDetails['cvc'],
                            ],
                            'billing' => [
                                'name'  => $billingDetails['name'],
                                'email' => $billingDetails['email'],
                                'phone' => $billingDetails['phone'] ?? null,
                            ]
                        ]
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            Log::info('Card Payment Method Created', ['method_id' => $result['data']['id'] ?? 'unknown']);
            return $result;

        } catch (RequestException $e) {
            $this->logRequestError('Card Payment Method', $e);

            // Surface PayMongo's actual error message to the user
            if ($e->hasResponse()) {
                $body = json_decode((string)$e->getResponse()->getBody(), true);
                $detail = $body['errors'][0]['detail'] ?? $e->getMessage();
                throw new \Exception($detail);
            }

            throw new \Exception('Failed to create card payment method: ' . $e->getMessage());
        } catch (GuzzleException $e) {
            Log::error('Card Payment Method Error', ['message' => $e->getMessage()]);
            throw new \Exception('Failed to create card payment method: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════
    //  SHARED — Attach Payment Method to Intent
    // ═══════════════════════════════════════════
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
                            'return_url'     => $returnUrl
                        ]
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            Log::info('Payment Method Attached Successfully');
            return $result;

        } catch (RequestException $e) {
            $this->logRequestError('Attach Payment', $e);
            throw new \Exception('Failed to attach payment method: ' . $e->getMessage());
        } catch (GuzzleException $e) {
            Log::error('Attach Payment Error', ['message' => $e->getMessage()]);
            throw new \Exception('Failed to attach payment method: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════
    //  SHARED — Retrieve Payment Intent
    // ═══════════════════════════════════════════
    public function retrievePaymentIntent($paymentIntentId)
    {
        try {
            Log::info('Retrieving Payment Intent', ['intent_id' => $paymentIntentId]);

            $response = $this->client->get("/payment_intents/{$paymentIntentId}");
            $result   = json_decode($response->getBody()->getContents(), true);

            Log::info('Payment Intent Retrieved', [
                'status' => $result['data']['attributes']['status'] ?? 'unknown'
            ]);

            return $result;

        } catch (RequestException $e) {
            $this->logRequestError('Retrieve Payment Intent', $e);
            throw new \Exception('Failed to retrieve payment intent: ' . $e->getMessage());
        } catch (GuzzleException $e) {
            Log::error('Retrieve Payment Intent Error', ['message' => $e->getMessage()]);
            throw new \Exception('Failed to retrieve payment intent: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════
    //  HELPER — Log request errors with body
    // ═══════════════════════════════════════════
    private function logRequestError(string $context, RequestException $e): void
    {
        Log::error("PayMongo {$context} Error", [
            'message' => $e->getMessage(),
            'code'    => $e->getCode()
        ]);

        if ($e->hasResponse()) {
            $errorBody = (string)$e->getResponse()->getBody();
            Log::error("PayMongo {$context} Response Body", ['body' => $errorBody]);
        }
    }
}