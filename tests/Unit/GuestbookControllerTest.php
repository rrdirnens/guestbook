<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use App\Models\GuestbookMessage;
use Tests\TestCase; // https://laracasts.com/discuss/channels/testing/default-user-factory-gives-invalidargumentexception-unknown-formatter-name 

class GuestbookControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_displays_guestbook_messages()
    {
        // Create some guestbook messages
        $messages = GuestbookMessage::factory()->count(3)->create();

        // Call the index method
        $response = $this->get(route('guestbook.index'));

        // Assert that the response has a successful status
        $response->assertStatus(200);

        // Assert that the messages are part of the response
        // we need to use assertSee() instead of assertViewHas() because the messages are not part of the view. Because we are using Inertia, the messages are part of the response, not the view.
        foreach ($messages as $message) {
            $response->assertSee($message->name);
            $response->assertSee($message->email);
            $response->assertSee($message->message);
        }
    }

    public function test_create_displays_the_create_form()
    {
        // Call the create method
        $response = $this->get(route('guestbook.create'));

        // Assert that the response has a successful status
        $response->assertStatus(200);

        // Assert that the form is part of the response
        $response->assertInertia(fn (AssertableInertia $page) => $page->component('Guestbook/Create'));
    }

}
