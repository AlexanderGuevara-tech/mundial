<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Carbon;

class PollaSettings
{
    public function all(): array
    {
        return [
            'points_exact' => (int) $this->get('points_exact', '3'),
            'points_result' => (int) $this->get('points_result', '1'),
            'deadline' => $this->get('deadline', ''),
        ];
    }

    public function get(string $key, string $default = ''): string
    {
        return Setting::query()->whereKey($key)->value('value') ?? $default;
    }

    public function upsert(string $key, mixed $value): void
    {
        Setting::query()->updateOrCreate(['key' => $key], ['value' => (string) $value]);
    }

    public function deadlinePassed(): bool
    {
        $deadline = $this->get('deadline');

        return $deadline !== '' && Carbon::now()->greaterThan(Carbon::parse($deadline));
    }
}
