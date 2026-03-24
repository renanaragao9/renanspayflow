<?php

namespace App\Http\V1\Controllers\Message;

use App\Http\V1\Controllers\Global\BaseController;
use App\Http\V1\Requests\Message\IndexMessageRequest;
use App\Http\V1\Requests\Message\StoreMessageRequest;
use App\Http\V1\Requests\Message\UpdateMessageRequest;
use App\Http\V1\Resources\Message\MessageResource;
use App\Models\Message;
use App\Services\Message\DeleteMessageService;
use App\Services\Message\IndexMessageService;
use App\Services\Message\StoreMessageService;
use App\Services\Message\UpdateMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MessageController extends BaseController
{
    public function index(
        IndexMessageRequest $indexMessageRequest,
        IndexMessageService $indexMessageService
    ): AnonymousResourceCollection {
        $data = $indexMessageRequest->validated();
        $messages = $indexMessageService->run($data);

        return MessageResource::collection($messages);
    }

    public function show(Message $message): JsonResponse
    {
        return $this->successResponse(
            new MessageResource($message),
            'Mensagem encontrada com sucesso.'
        );
    }

    public function store(
        StoreMessageRequest $storeMessageRequest,
        StoreMessageService $storeMessageService
    ): JsonResponse {
        $data = $storeMessageRequest->validated();
        $message = $storeMessageService->run($data);

        return $this->successResponse(
            new MessageResource($message),
            'Mensagem criada com sucesso.'
        );
    }

    public function update(
        UpdateMessageRequest $updateMessageRequest,
        UpdateMessageService $updateMessageService,
        Message $message
    ): JsonResponse {
        $data = $updateMessageRequest->validated();
        $message = $updateMessageService->run($message, $data);

        return $this->successResponse(
            new MessageResource($message),
            'Mensagem atualizada com sucesso.'
        );
    }

    public function destroy(
        Message $message,
        DeleteMessageService $deleteMessageService
    ): JsonResponse {
        $deleteMessageService->run($message);

        return $this->successResponse(
            null,
            'Mensagem removida com sucesso.'
        );
    }
}
