<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\UpdateTicketRequest;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTicketRequest;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $view = $request->query('view', 'mine'); // default: show "My Tickets"

        $query = Ticket::with(['author', 'assignee'])
        ->orderBy('created_at', 'desc');

    // 🧑 Normal users can only see their own tickets (created or assigned)
        if (!$user->is_admin) {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                ->orWhere('assigned_to', $user->id);
            });
        } else {
        // 👑 Admin can toggle between all or own
            if ($view === 'mine') {
                $query->where(function ($q) use ($user) {
                    $q->where('created_by', $user->id)
                    ->orWhere('assigned_to', $user->id);
            });
        }
        // else 'all' => show everything
        }

        $tickets = $query->paginate(10)->withQueryString();
        return Inertia::render('Tickets/Index', [
        'tickets' => $tickets,
        'view' => $view,
        'auth' => ['user' => $user],
    ]);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::select('id','name')->get();
        return Inertia::render('Tickets/Create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('tickets', 'public');
        }

        $ticket = Ticket::create($data);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket created');
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, Ticket $ticket)
    {
        $ticket->load(['author', 'assignee']);

        $users = [];
        if ($request->user()->is_admin) {
            $users = \App\Models\User::select('id', 'name')->get();
        }

        return \Inertia\Inertia::render('Tickets/Show', [
            'ticket' => $ticket,
            'users' => $users, // ✅ pass for reassign dropdown
            'auth' => ['user' => $request->user()],
        ]);
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Ticket $ticket)
    {
        if (!Gate::allows('update-ticket', $ticket)) {
            abort(403, 'Only author can edit ticket details');
        }
        $users = User::select('id','name')->get();
        return Inertia::render('Tickets/Edit', ['ticket' => $ticket, 'users' => $users]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)

    {
        $user = $request->user();
        $data = $request->validated();
         // Author can update everything

        if (Gate::allows('update-ticket', $ticket)) {
            if ($request->hasFile('image')) {
                $data['image_path'] = $request->file('image')->store('tickets','public');
            }
            // handle assigned_to and assigned_at when author sets assigned_to

            if (array_key_exists('assigned_to', $data)) {
                $data['assigned_at'] = $data['assigned_to'] ? now() : null;
            }
            if (isset($data['status']) && $data['status'] === 'completed') {
                $data['completed_at'] = now();
            }
            $ticket->update($data);
            return back()->with('success','Ticket updated');
        }

        // Assignee can only update status (inprogress/completed)
        if (Gate::allows('update-status', $ticket)) {
            $allowedStatus = ['inprogress','completed'];
            if (isset($data['status']) && in_array($data['status'], $allowedStatus)) {
                if ($data['status']==='completed') {
                    $ticket->completed_at = now();
                }
                $ticket->status = $data['status'];
                $ticket->save();
                return back()->with('success','Status updated');
            }
            abort(403, 'Assignee can only update status to inprogress or completed');
        }

        abort(403);
    }

    public function assign(Request $request, Ticket $ticket)
    {
        // only author can assign
        Gate::authorize('assign-ticket', $ticket);

        $request->validate(['assigned_to' => 'required|exists:users,id']);
        $ticket->assigned_to = $request->assigned_to;
        $ticket->assigned_at = now();
        $ticket->save();

        return back()->with('success','Ticket assigned');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Ticket $ticket)
    {
        Gate::authorize('update-ticket', $ticket); // only author can delete
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success','Ticket deleted');
    }
}
