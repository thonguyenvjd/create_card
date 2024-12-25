<?php

namespace App\Services;

use Http;
use Illuminate\Http\Client\Response;
use Exception;
use InvalidArgumentException;
use Illuminate\Http\Client\RequestException;
use voku\helper\HtmlDomParser;

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

        $dom = new HtmlDomParser($html);
        $bodyElement = $dom->findOne('body');
        $width = 800;

        if ($bodyElement) {
            $style = $bodyElement->getAttribute('style');
            if (preg_match('/width:\s*(\d+)px/', $style, $matches)) {
                $width = (int) $matches[1];
            }
        }

        $backgroundElement = $dom->findOne('.background-special');
        if ($backgroundElement) {
            $style = $backgroundElement->getAttribute('style');
            if (preg_match('/width:\s*(\d+)%/', $style, $matches)) {
                $width = $width * (int)$matches[1] / 100;
            } elseif (preg_match('/width:\s*(\d+)px/', $style, $matches)) {
                $width = (int)$matches[1];
            }
        }

        $defaultOptions = [
            'device_scale' => 1,
            'selector' => '.background-special',
            'full_page' => true,
            'wait_until' => 'networkidle0',
            'wait_for' => 1000,
            'ms_delay' => 1000,
            'css_media_type' => 'screen',
            'transparent' => false,
            'ignore_ssl_errors' => true
        ];
        
        $options = array_merge($defaultOptions, $options);

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