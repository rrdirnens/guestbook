<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Illuminate\Http\UploadedFile;
use App\Models\GuestbookMessage;
use Illuminate\Support\Str;
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

    public function test_store_new_guestbook_message()
    {
        // Arrange
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'This is a test message.',
        ];

        // Act
        $response = $this->post(route('guestbook.store'), $data);

        // Assert
        $response->assertRedirect(route('guestbook.index'));
        $response->assertSessionHas('success', 'Message added successfully!');

        $this->assertDatabaseHas('guestbook_messages', $data);
    }

    public function test_store_guestbook_message_validation()
    {
        // Arrange
        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'message' => '',
        ];

        // Act
        $response = $this->post(route('guestbook.store'), $data);

        // Assert
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'email' => 'The email field must be a valid email address.',
            'message' => 'The message field is required.',
        ]);
    }

    public function test_store_guestbook_message_with_image()
    {
        // Arrange
        Storage::fake('public');

        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'This is a test message.',
            'image' => UploadedFile::fake()->create('test-image.jpg', 1000),
        ];

        // Act
        $response = $this->post(route('guestbook.store'), $data);

        // Assert
        $response->assertRedirect(route('guestbook.index'));
        $response->assertSessionHas('success', 'Message added successfully!');

        $message = GuestbookMessage::latest('id')->first();

        Storage::disk('public')->assertExists(Str::replaceFirst('storage/', '', $message->image_path));
    }
}
