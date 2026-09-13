<?php
namespace App\Application;

use GuzzleHttp\Client;

class ProcessAudioStreamUseCase {
    public function execute($audioFilePath) {
        $client = new Client();
        
        try {
            // Forward audio processing to the Go Microservice
            $response = $client->post('http://localhost:9000/api/process', [
                'json' => [
                    'audio_data' => base64_encode(file_get_contents($audioFilePath))
                ]
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Go Engine Error: ' . $e->getMessage()
            ];
        }
    }
}
