<?php

declare(strict_types=1);

namespace Rodoud\ProfilerAssistantBundle\Controller;

use Rodoud\ProfilerAssistantBundle\Service\AiAnalysisService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ai-assistant', name: 'rodoud_ai_assistant_')]
final class ProfilerAssistantController extends AbstractController
{
    public function __construct(
        private readonly AiAnalysisService $aiAnalysisService
    ) {}

    #[Route('/analyze', name: 'analyze', methods: ['POST'])]
    public function analyze(Request $request): JsonResponse
    {
        $context = $request->getPayload()->all();

        if (empty($context)) {
            return $this->json([
                'error' => true,
                'message' => 'No context provided for analysis'
            ], 400);
        }

        $analysis = $this->aiAnalysisService->analyzeContext($context);
        return $this->json($analysis);
    }

    #[Route('/chat', name: 'chat', methods: ['POST'])]
    public function chat(Request $request): JsonResponse
    {
        $payload = $request->getPayload()->all();

        $message = $payload['message'] ?? '';
        $context = $payload['context'] ?? [];

        if (empty($message)) {
            return $this->json([
                'error' => true,
                'message' => 'No message provided'
            ], 400);
        }

        $response = $this->aiAnalysisService->sendChatMessage($message, $context);

        return $this->json($response);
    }

    #[Route('/status', name: 'status', methods: ['GET'])]
    public function status(): JsonResponse
    {
        return $this->json([
            'status' => 'active',
            'bundle' => 'Rodoud AI Assistant Bundle',
            'version' => '1.0.0',
            'endpoints' => [
                'analyze' => '/rodoud/ai-assistant/analyze',
                'chat' => '/rodoud/ai-assistant/chat',
            ],
            'timestamp' => time(),
        ]);
    }
}