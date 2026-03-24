<?php

namespace App\Services\Contact;

use App\Models\Contact;

class StoreContactService
{
    public function run(array $data): Contact
    {
        return Contact::create($data);
    }
}
