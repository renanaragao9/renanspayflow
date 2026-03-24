<?php

namespace App\Services\Message;

use App\Models\Message;

class StoreMessageService
{
    public function run(array $data): Message
    {
        return Message::create($data);
    }
}
