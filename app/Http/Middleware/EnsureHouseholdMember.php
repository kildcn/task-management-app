<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHouseholdMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->household_id) {
            // Check if the route is already a household creation/join route
            if (!in_array($request->route()->getName(), [
                'household.create',
                'household.store',
                'household.join',
                'household.join.post'
            ])) {
                return redirect()->route('household.create')
                    ->with('error', 'You must join a household first.');
            }
        }

        return $next($request);
    }
}
