<?php

namespace App\Services\Message;

use App\Models\Message;

class UpdateMessageService
{
    public function run(Message $message, array $data): Message
    {
        $message->update($data);

        return $message;
    }
}
