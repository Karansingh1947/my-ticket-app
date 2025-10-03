<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Ticket::class => \App\Policies\TicketPolicy::class, // if you decide to make a policy later
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // 👇 Define Gates here

        Gate::define('view-ticket', fn(User $user, Ticket $ticket) =>
            $user->id === $ticket->created_by || $user->id === $ticket->assigned_to
        );

        Gate::define('update-ticket', fn(User $user, Ticket $ticket) =>
            $user->id === $ticket->created_by
        );

        Gate::define('assign-ticket', fn(User $user, Ticket $ticket) =>
            $user->id === $ticket->created_by
        );

        Gate::define('update-status', fn(User $user, Ticket $ticket) =>
            $user->id === $ticket->assigned_to
        );

        // Example for user listing (admin only)
        Gate::define('list-users', fn(User $user) =>
            $user->is_admin
        );
    }
}
