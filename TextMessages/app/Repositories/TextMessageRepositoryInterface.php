<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface TextMessageRepositoryInterface
{
    public function getLatest(int $limit = 50): Collection;

    public function getTodaysFailed(): Collection;
}
