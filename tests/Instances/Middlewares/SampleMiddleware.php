<?php

namespace Hans\Valravn\Tests\Instances\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SampleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}