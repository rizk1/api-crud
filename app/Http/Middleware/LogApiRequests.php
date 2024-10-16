<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogApiRequests
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $logData = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user' => Auth::user() ? Auth::user()->id : 'unauthenticated',
            'ip' => $request->ip(),
            'request' => $request->all(),
            'response' => $response->getContent()
        ];

        Log::channel('api')->info(json_encode($logData));
        echo json_encode($logData) . "\n";

        return $response;
    }
}
