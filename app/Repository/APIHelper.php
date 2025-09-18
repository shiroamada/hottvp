<?php

namespace App\Repository;

use GuzzleHttp\Client;

class APIHelper
{
    public function post($body, $apiStr)
    {
        // Use environment variable or fallback to sea.test
        $baseUri = env('API_BASE_URL', 'http://sea.test/public/index.php/v2/1/');
        $fullUrl = $baseUri . $apiStr;
        
        \Log::info("=== APIHelper POST Request Debug ===");
        \Log::info("Base URI: " . $baseUri);
        \Log::info("API String: " . $apiStr);
        \Log::info("Full URL: " . $fullUrl);
        \Log::info("Request Body: " . json_encode($body, JSON_PRETTY_PRINT));
        \Log::info("Request Headers: " . json_encode([
            'Content-type' => 'application/json',
            'Accept' => 'application/json'
        ], JSON_PRETTY_PRINT));
        
        $client = new Client(['base_uri' => $baseUri]);
        
        try {
            $res = $client->request('POST', $apiStr,
                ['json' => $body,
                    'headers' => [
                        'Content-type' => 'application/json',
//                    'Cookie'=> 'XDEBUG_SESSION=PHPSTORM',
                        "Accept" => "application/json"]
                ]);
            
            \Log::info("=== APIHelper POST Response Debug ===");
            \Log::info("Response Status Code: " . $res->getStatusCode());
            \Log::info("Response Headers: " . json_encode($res->getHeaders(), JSON_PRETTY_PRINT));
            
            $data = $res->getBody()->getContents();
            \Log::info("Response Body: " . $data);
            \Log::info("=== End APIHelper POST Debug ===");
            
            return $data;
        } catch (\Exception $e) {
            \Log::error("=== APIHelper POST Error ===");
            \Log::error("Error Message: " . $e->getMessage());
            \Log::error("Error Code: " . $e->getCode());
            \Log::error("Request URL: " . $fullUrl);
            \Log::error("Request Body: " . json_encode($body));
            \Log::error("=== End APIHelper POST Error ===");
            throw $e;
        }
    }

    public function get($apiStr, $header = [])
    {
        $baseUri = env('API_BASE_URL', 'http://sea.test/public/index.php/v2/1/');
        $fullUrl = $baseUri . $apiStr;
        
        \Log::info("=== APIHelper GET Request Debug ===");
        \Log::info("Base URI: " . $baseUri);
        \Log::info("API String: " . $apiStr);
        \Log::info("Full URL: " . $fullUrl);
        \Log::info("Request Headers: " . json_encode($header, JSON_PRETTY_PRINT));
        
        $client = new Client(['base_uri' => $baseUri]);
        
        try {
            $res = $client->request('GET', $apiStr, ['headers' => $header]);
            
            \Log::info("=== APIHelper GET Response Debug ===");
            \Log::info("Response Status Code: " . $res->getStatusCode());
            \Log::info("Response Headers: " . json_encode($res->getHeaders(), JSON_PRETTY_PRINT));
            
            $statusCode = $res->getStatusCode();
            $header = $res->getHeader('content-type');
            $data = $res->getBody();
            
            \Log::info("Response Body: " . $data);
            \Log::info("=== End APIHelper GET Debug ===");
            
            return $data;
        } catch (\Exception $e) {
            \Log::error("=== APIHelper GET Error ===");
            \Log::error("Error Message: " . $e->getMessage());
            \Log::error("Error Code: " . $e->getCode());
            \Log::error("Request URL: " . $fullUrl);
            \Log::error("=== End APIHelper GET Error ===");
            throw $e;
        }
    }
    
    /**
     * Test endpoint connectivity
     * @param string $endpoint
     * @return array
     */
         public function testEndpoint($endpoint = null)
     {
         $baseUri = $endpoint ?: env('API_BASE_URL', 'http://sea.test/public/index.php/v2/1/');
        
        \Log::info("=== Testing Endpoint Connectivity ===");
        \Log::info("Testing URL: " . $baseUri);
        
        try {
            $client = new Client(['base_uri' => $baseUri, 'timeout' => 10]);
            
            // Test with a simple GET request to see if the endpoint is reachable
            $response = $client->request('GET', '', [
                'headers' => [
                    'Accept' => 'application/json',
                    'User-Agent' => 'Laravel-APIHelper-Test/1.0'
                ]
            ]);
            
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            
            \Log::info("Endpoint test successful - Status: " . $statusCode);
            \Log::info("Response body: " . substr($body, 0, 500) . (strlen($body) > 500 ? '...' : ''));
            
            return [
                'success' => true,
                'status_code' => $statusCode,
                'body' => $body,
                'endpoint' => $baseUri
            ];
            
        } catch (\Exception $e) {
            \Log::error("Endpoint test failed: " . $e->getMessage());
            \Log::error("Error code: " . $e->getCode());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'endpoint' => $baseUri
            ];
        }
    }
}