<?php

namespace App\Services\Contact;

use App\Models\Contact;

class UpdateContactService
{
    public function run(Contact $contact, array $data): Contact
    {
        $contact->update($data);

        return $contact;
    }
}
