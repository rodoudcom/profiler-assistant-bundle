<?php

declare(strict_types=1);

namespace Rodoud\ProfilerAssistantBundle\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final readonly class AiAnalysisService
{
    private string $aiEndpoint;
    private int $timeout;

    public function __construct(private HttpClientInterface $httpClient)
    {
        $this->aiEndpoint = 'https://api.rodoud.com/profiler/api';
        $this->timeout = 30;
    }

    public function analyzeContext(array $context): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->aiEndpoint."/analyze/exception", [
                'json' => $context,
                'timeout' => $this->timeout,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Rodoud-Profiler-Assistant/1.0',
                ],
            ]);

            return $response->toArray();
        } catch (TransportExceptionInterface $e) {
            return [
                'error' => true,
                'message' => 'Unable to connect to AI service: ' . $e->getMessage(),
                'analysis' => 'AI analysis is currently unavailable. Please check your internet connection.',
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Analysis failed: ' . $e->getMessage(),
                'analysis' => 'Something went wrong while analyzing your code. Please try again later.',
            ];
        }
    }

    public function sendChatMessage(string $message, array $context): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->aiEndpoint . '/chat', [
                'json' => [
                    'message' => $message,
                    "analysis_id" => $context["analysis_id"]??"",
                ],
                'timeout' => $this->timeout,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Rodoud-Profiler-Assistant/1.0',
                ],
            ]);

            return $response->toArray();
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Chat service unavailable: ' . $e->getMessage(),
                'response' => 'I apologize, but I cannot respond right now. Please try again later.',
            ];
        }
    }
}