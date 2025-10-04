<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTicketVisible
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // thanks to route model binding, this is already a Ticket model
        $ticket = $request->route('ticket');

        if ($ticket && auth()->check()) {
            $user = $request->user();

            if ($user->is_admin) {
                return $next($request);
            }

            if ($ticket->created_by !== $user->id && $ticket->assigned_to !== $user->id) {
                abort(403, 'Unauthorized action.');
            }
        }

        return $next($request);
    }
}
