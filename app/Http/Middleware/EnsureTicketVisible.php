<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class EnsureTicketVisible
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $ticketId = $request->route('ticket');
        if ($ticketId) return $next($request);

        $ticket = Ticket::find($ticketId);
        if (!Gate::allows('view-ticket', $ticket)) {
            abort(403, 'Unauthorized action.');
        }

        // attach ticket to request to avoid reloading
        $request->attributes->set('ticket', $ticket);
        return $next($request);

    }
}
