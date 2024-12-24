<?php

namespace App\Services;

use Http;
use Illuminate\Http\Client\Response;
use Exception;
use InvalidArgumentException;
use Illuminate\Http\Client\RequestException;

class HtmlToImageService
{
    private const API_URL = 'https://hcti.io/v1';
    private const TIMEOUT = 30; // seconds

    private string $userId;
    private string $apiKey;

    public function __construct()
    {
        $this->userId = config('services.htmlcsstoimage.user_id');
        $this->apiKey = config('services.htmlcsstoimage.api_key');

        if (empty($this->userId) || empty($this->apiKey)) {
            throw new InvalidArgumentException('HTML to Image service credentials are not properly configured.');
        }
    }

    /**
     * Generate an image from HTML and optional CSS
     *
     * @param string $html The HTML content
     * @param string $css Optional CSS styles
     * @param array $options Optional additional API parameters
     * @return array{success: bool, url?: string, message?: string}
     */
    public function generateImage(string $html, string $css = '', array $options = []): array
    {
        if (empty($html)) {
            return [
                'success' => false,
                'message' => 'HTML content cannot be empty'
            ];
        }

        try {
            $response = $this->makeApiRequest($html, $css, $options);

            if (!$response->successful()) {
                throw new RequestException($response);
            }

            $imageUrl = $response->json('url');
            
            if (empty($imageUrl)) {
                throw new Exception('Image URL not found in API response');
            }

            return [
                'image' => $imageUrl,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to generate image: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Make the API request to generate the image
     *
     * @param string $html
     * @param string $css
     * @param array $options
     * @return Response
     * @throws RequestException
     */
    private function makeApiRequest(string $html, string $css, array $options = []): Response
    {
        $payload = array_merge(
            compact('html', 'css'),
            $options
        );

        return Http::withBasicAuth($this->userId, $this->apiKey)
            ->timeout(self::TIMEOUT)
            ->retry(3, 100)
            ->post(self::API_URL . '/image', $payload);
    }
}