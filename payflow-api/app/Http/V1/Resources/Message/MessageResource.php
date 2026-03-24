<?php

namespace App\Http\V1\Resources\Message;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'type' => $this->type,
            'channel' => $this->channel,
            'message' => $this->message,
            'read_at' => $this->read_at,
            'user_id' => $this->user_id,
            'contact_id' => $this->contact_id,
            'expense_installment_id' => $this->expense_installment_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
