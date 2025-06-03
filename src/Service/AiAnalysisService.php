<?php

declare(strict_types=1);

namespace Rodoud\ProfilerAssistantBundle\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final readonly class AiAnalysisService
{
    private string $aiEndpoint;
    private string $fallbackEndpoint;
    private int $timeout;

    public function __construct(private HttpClientInterface $httpClient)
    {
        $this->aiEndpoint = 'https://api.rodoud.com/profiler/api';
        $this->fallbackEndpoint = 'http://102.211.210.138:2/profiler/api'; //Some environments return SSL certificate problem
        $this->timeout = 30;
    }

    public function analyzeContext(array $context): array
    {
        return $this->postToApi('/analyze/exception', ['json' => $context]);
    }

    public function sendChatMessage(string $message, array $context): array
    {
        return $this->postToApi('/chat', [
            'json' => [
                'message' => $message,
                'analysis_id' => $context['analysis_id'] ?? '',
            ]
        ]);
    }

    private function postToApi(string $path, array $options): array
    {
        $endpoints = [$this->aiEndpoint, $this->fallbackEndpoint];

        foreach ($endpoints as $endpoint) {
            try {
                $response = $this->httpClient->request('POST', $endpoint . $path, array_merge([
                    'timeout' => $this->timeout,
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'Rodoud-Profiler-Assistant/1.0',
                    ],
                ], $options));

                return $response->toArray();
            } catch (TransportExceptionInterface $e) {
                // Continue to try the next endpoint
                $lastException = $e;
            } catch (\Exception $e) {
                return [
                    'error' => true,
                    'message' => 'Request failed: ' . $e->getMessage(),
                    'response' => 'Something went wrong. Please try again later.',
                ];
            }
        }

        return [
            'error' => true,
            'message' => 'Unable to connect to both AI endpoints: ' . $lastException->getMessage(),
            'response' => 'AI service is currently unreachable. Please check your connection.',
        ];
    }
}
