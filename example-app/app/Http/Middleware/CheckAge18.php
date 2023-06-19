<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CheckAge18
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $dataOfBirth = Carbon::createFromFormat('Y-m-d H:i:s', $user->dob);
        $now = Carbon::now();
        $age = $now->diffInYears($dataOfBirth);
        if ($age <= 18) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
