<?php

declare(strict_types=1);

namespace Rodoud\ProfilerAssistantBundle\DataCollector;

use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\VarDumper\Cloner\Data;

final class AiAssistantDataCollector extends AbstractDataCollector
{
    public function __construct(private Profiler $profiler)
    {
    }

    public function collect(Request $request, Response $response, ?\Throwable $exception = null): void
    {

        try {
            if (!$exception)
                $exception = $this->profiler->get("exception")->getException();
        }catch (\Exception){

        }


        try{
            $this->data = [
                'request_data' => [
                    'method' => $request->getMethod(),
                    'uri' => $request->getUri(),
                    'route' => $request->attributes->get('_route'),
                    'controller' => $request->attributes->get('_controller'),
                    'parameters' => $request->request->all(),
                    'query' => $request->query->all(),
                    // 'headers' => $request->headers->all(),
                ],
                'response_data' => [
                    'status_code' => $response->getStatusCode(),
                    //'headers' => $response->headers->all(),
                ],
                'exception_data' => $exception ? [
                    'class' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'file_excerpt' => $this->getFileExcerpt($exception->getFile(), $exception->getLine()),
                    'trace' => $exception->getTraceAsString(),
                    'previous' => $exception->getPrevious() ? [
                        'class' => get_class($exception->getPrevious()),
                        'message' => $exception->getPrevious()->getMessage(),
                    ] : null,
                ] : null,
                'environment' => [
                    'php_version' => PHP_VERSION,
                    'symfony_version' => \Symfony\Component\HttpKernel\Kernel::VERSION,
                    'debug_mode' => $request->attributes->get('_debug', false),
                ],
                'timestamp' => time(),
                'analysis_id' => $response->headers->all()["x-debug-token"][0] ?? time()
            ];
        }catch (\Exception $e){
            // Handle any exceptions that may occur during data collection
            $this->data = [];
        }


    }

    public function getRequestData(): array
    {
        return $this->data['request_data'] ?? [];
    }

    public function getResponseData(): array
    {
        return $this->data['response_data'] ?? [];
    }

    public function getExceptionData(): ?array
    {
        return $this->data['exception_data'] ?? null;
    }

    public function getEnvironmentData(): array
    {
        return $this->data['environment'] ?? [];
    }

    public function hasException(): bool
    {
        return $this->data['exception_data'] !== null;
    }

    public function getContextForAi(): array
    {
        return [
            'request' => $this->getRequestData(),
            'response' => $this->getResponseData(),
            'exception' => $this->getExceptionData(),
            'environment' => $this->getEnvironmentData(),
            'timestamp' => $this->data['timestamp'],
            'analysis_id' => $this->data['analysis_id'],
        ];
    }

    public function getName(): string
    {
        return 'rodoud.ai_assistant';
    }

    public static function getTemplate(): ?string
    {
        return '@RodoudProfilerAssistant/data_collector/ai_assistant.html.twig';
    }

    private function getFileExcerpt(string $file, int $line): array
    {
        if (!file_exists($file)) {
            return [];
        }

        $content = file($file);
        $lines = [];

        $start = max($line - 5, 0);
        $end = min($line + 5, count($content));

        for ($i = $start; $i < $end; $i++) {
            $lines[$i + 1] = rtrim($content[$i]);
        }

        return $lines;
    }
}