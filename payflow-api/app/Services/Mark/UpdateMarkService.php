<?php

namespace App\Services\Mark;

use App\Models\Mark;
use Illuminate\Support\Facades\DB;

class UpdateMarkService
{
    public function run(Mark $mark, array $data): Mark
    {
        $mark->update($data);

        return $mark;
    }
}