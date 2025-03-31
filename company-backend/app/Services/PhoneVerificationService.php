<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PhoneVerificationService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey =  env('ABSTRACT_API_KEY');
        $this->apiUrl = 'https://phonevalidation.abstractapi.com/v1/';



    }

    /**
     * Verify Phone Number.
     * returns : json: {"valid" : boolean}
     */
    public function verifyPhoneNumber($phoneNumber)
    {
        $response = Http::get($this->apiUrl, [
            'api_key' => $this->apiKey,
            'phone' => $phoneNumber,
        ]);

        // Check if the response is successful
        if ($response->successful()) {
            // Extract the 'valid' key from the response
            $data = $response->json();
            return [
                'valid' => $data['valid'], // Return only the 'valid' key and its value
            ];
        }


        // Handle no phone number passed case or wrong api key
        return [
            'error' => true,
            'message' => 'Failed to verify phone number.',
            'details' => $response->json(),

        ];
    }
}
