<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\GuestbookMessage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Inertia\Testing\AssertableInertia;
use Illuminate\Support\Facades\Storage;
use App\Services\GuestbookMessageService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuestbookControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $guestbookMessageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guestbookMessageService = $this->app->make(GuestbookMessageService::class);
    }

    public function test_index_displays_guestbook_messages()
    {
        // Create some guestbook messages
        $messages = GuestbookMessage::factory()->count(3)->create();

        // Call the index method
        $response = $this->get(route('guestbook.index'));

        // Assert that the response has a successful status
        $response->assertStatus(200);

        // Assert that the messages are part of the response
        foreach ($messages as $message) {
            $response->assertSee($message->name);
            $response->assertSee($message->email);
            $response->assertSee($message->message);
        }
    }

    /**
     * @dataProvider sortingScenarios
     */
    public function test_index_displays_sorted_guestbook_messages($sortField, $sortDirection)
    {
        // Create some guestbook messages
        $messages = GuestbookMessage::factory()->count(5)->create();

        // Call the index method with sorting parameters
        $response = $this->get(route('guestbook.index', [
            'sort' => $sortField,
            'direction' => $sortDirection,
        ]));

        // Assert that the response has a successful status
        $response->assertStatus(200);

        // Assert that the messages are part of the response
        $sortedMessages = $messages->sortBy([$sortField => $sortDirection])->values();
        for ($i = 0; $i < $sortedMessages->count(); $i++) {
            $response->assertSeeInOrder([
                $sortedMessages[$i]->name,
                $sortedMessages[$i]->email,
                $sortedMessages[$i]->message,
            ]);
        }
    }
    
    public function sortingScenarios()
    {
        return [
            ['name', 'asc'],
            ['name', 'desc'],
            ['created_at', 'asc'],
            ['created_at', 'desc'],
        ];
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

        Log::info('Full fake file path: ' . $message->image_path);

        // Ensure the image path is valid and the file exists in the fake storage
        $this->assertNotNull($message->image_path);
        Storage::assertExists('public/guestbook_images/' . basename($message->image_path));
    }
}
