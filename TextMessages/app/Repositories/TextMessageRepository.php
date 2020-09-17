<?php

namespace App\Repositories;

use App\Models\TextMessage;
use Illuminate\Support\Collection;

class TextMessageRepository implements TextMessageRepositoryInterface
{
    public function getLatest(int $limit = 50): Collection
    {
        return TextMessage::take($limit)
            ->orderByDesc('updated_at')
            ->get();
    }

    public function getTodaysFailed(): Collection
    {
        $yesterday = date('Y-m-d H:i:s', strtotime('-24 hours'));

        return TextMessage::where('status', '<>', TextMessage::STATUS_DELIVERED)
            ->where('created_at', '>=', $yesterday)
            ->orderByDesc('updated_at')
            ->get();
    }
}
