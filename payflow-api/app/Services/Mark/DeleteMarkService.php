<?php

namespace App\Services\Mark;

use App\Models\Mark;
use Illuminate\Support\Facades\DB;

class DeleteMarkService
{
    public function run(Mark $mark): void
    {
        $mark->delete();
    }
}