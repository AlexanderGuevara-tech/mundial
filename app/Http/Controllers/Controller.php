<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

abstract class Controller
{
    protected function logActivity(Request $request, string $action, ?string $details = null): void
    {
        ActivityLog::query()->create([
            'user_id' => $request->user()->id,
            'action' => $action,
            'details' => $details,
            'ip_address' => $request->ip(),
        ]);
    }
}
