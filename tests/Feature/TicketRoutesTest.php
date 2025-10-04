<?php

namespace Tests\Feature;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketRoutesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
     use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_tickets_routes()
    {
        $ticket = Ticket::factory()->create();

        $this->get(route('tickets.index'))->assertRedirect('/login');
        $this->get(route('tickets.create'))->assertRedirect('/login');
        $this->post(route('tickets.store'))->assertRedirect('/login');
        $this->get(route('tickets.show', $ticket))->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_ticket_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('tickets.index'));
        $response->assertStatus(200); // should load Inertia page
    }

    /** @test */
    public function user_cannot_view_other_users_ticket()
    {
        $author = User::factory()->create();
        $other = User::factory()->create();
        $ticket = Ticket::factory()->create(['created_by' => $author->id]);

        $this->actingAs($other)
             ->get(route('tickets.show', $ticket))
             ->assertStatus(403);
    }

    /** @test */
    public function author_can_view_their_ticket()
    {
        $author = User::factory()->create();
        $ticket = Ticket::factory()->create(['created_by' => $author->id]);

        $this->actingAs($author)
             ->get(route('tickets.show', $ticket))
             ->assertStatus(200);
    }
}

