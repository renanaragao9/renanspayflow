<?php

namespace App\Services\Message;

use App\Models\Message;

class DeleteMessageService
{
    public function run(Message $message): void
    {
        $message->delete();
    }
}
