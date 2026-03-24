<?php

namespace App\Http\V1\Controllers\Contact;

use App\Http\V1\Controllers\Global\BaseController;
use App\Http\V1\Requests\Contact\IndexContactRequest;
use App\Http\V1\Requests\Contact\StoreContactRequest;
use App\Http\V1\Requests\Contact\UpdateContactRequest;
use App\Http\V1\Resources\Contact\ContactResource;
use App\Models\Contact;
use App\Services\Contact\DeleteContactService;
use App\Services\Contact\IndexContactService;
use App\Services\Contact\StoreContactService;
use App\Services\Contact\UpdateContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContactController extends BaseController
{
    public function index(
        IndexContactRequest $indexContactRequest,
        IndexContactService $indexContactService
    ): AnonymousResourceCollection {
        $data = $indexContactRequest->validated();
        $contacts = $indexContactService->run($data);

        return ContactResource::collection($contacts);
    }

    public function show(Contact $contact): JsonResponse
    {
        return $this->successResponse(
            new ContactResource($contact),
            'Contato encontrado com sucesso.'
        );
    }

    public function store(
        StoreContactRequest $storeContactRequest,
        StoreContactService $storeContactService
    ): JsonResponse {
        $data = $storeContactRequest->validated();
        $contact = $storeContactService->run($data);

        return $this->successResponse(
            new ContactResource($contact),
            'Contato criado com sucesso.'
        );
    }

    public function update(
        UpdateContactRequest $updateContactRequest,
        UpdateContactService $updateContactService,
        Contact $contact
    ): JsonResponse {
        $data = $updateContactRequest->validated();
        $contact = $updateContactService->run($contact, $data);

        return $this->successResponse(
            new ContactResource($contact),
            'Contato atualizado com sucesso.'
        );
    }

    public function destroy(
        Contact $contact,
        DeleteContactService $deleteContactService
    ): JsonResponse {
        $deleteContactService->run($contact);

        return $this->successResponse(
            null,
            'Contato removido com sucesso.'
        );
    }
}
