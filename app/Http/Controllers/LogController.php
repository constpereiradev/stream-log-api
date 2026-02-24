<?php

namespace App\Http\Controllers;

use App\Events\LogReceived;
use App\Http\Requests\LogRequest;
use App\Models\Log;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function __construct(private readonly LogService $logService) {}

    public function store(LogRequest $logRequest)
    {

        $log = Log::create([
            'level' => $logRequest['level'] ?? 'info',
            'message' => $logRequest['message'] ?? '',
            'context' => $logRequest['context'] ?? [],
            'user_id' => Auth::user()->id ?? null,
            'api_token_id' => $logRequest->user()->currentAccessToken()->id ?? null,
            'ip_address' => $logRequest->ip(),
            'method' => $logRequest->method(),
            'route' => $logRequest->path(),
            'status_code' => $logRequest['status_code'] ?? null,
        ]);

        broadcast(new LogReceived($log));

        return response()->json(['log' => $log], 201);
    }
}
