<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Models\GuestbookMessage;
use App\Repositories\GuestbookMessageRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuestbookMessageRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GuestbookMessageRepository();
    }

    public function testGetAllMessages()
    {
        // Create sample messages
        $messages = GuestbookMessage::factory()->count(3)->create();

        // Test
        $results = $this->repository->getAllMessages('created_at', 'desc');

        $this->assertCount(3, $results);
    }

    public function testGetMessageById()
    {
        // Create sample message
        $message = GuestbookMessage::factory()->create();

        // Test
        $result = $this->repository->getMessageById($message->id);

        $this->assertInstanceOf(GuestbookMessage::class, $result);
        $this->assertEquals($message->id, $result->id);
    }

    public function testcreateMessage()
    {
        // Create sample message
        $message = GuestbookMessage::factory()->create();

        // Test that $message exists and is an instance of GuestbookMessage
        $this->assertDatabaseHas('guestbook_messages', [
            'name' => $message->name,
            'email' => $message->email,
            'message' => $message->message,
            'image_path' => $message->image_path,
            'ip_address' => $message->ip_address,
        ]);

        $this->assertInstanceOf(GuestbookMessage::class, $message);

    }
    
    public function testUpdateMessage()
    {
        // Create sample message
        $message = GuestbookMessage::factory()->create();

        // Test that $message was created
        $this->assertDatabaseHas('guestbook_messages', [
            'name' => $message->name,
            'email' => $message->email,
            'message' => $message->message,
            'image_path' => $message->image_path,
            'ip_address' => $message->ip_address,
        ]);

        // Update sample message
        $updatedMessage = [
            'name'=> $message->name,
            'email' => $message->email,
            'message' => 'Updated message',
            'image_path' => 'Updated image path',
            'ip_address' => $message->ip_address,
        ];

        $this->repository->updateMessage($message, $updatedMessage);
    }

    public function testDeleteMessage()
    {
        // Create sample message
        $message = GuestbookMessage::factory()->create();

        // Test that $message was created
        $this->assertDatabaseHas('guestbook_messages', [
            'name' => $message->name,
            'email' => $message->email,
            'message' => $message->message,
            'image_path' => $message->image_path,
            'ip_address' => $message->ip_address,
        ]);

        // Delete sample message
        $this->repository->deleteMessage($message);

        // Test that $message was deleted
        $this->assertDatabaseMissing('guestbook_messages', [
            'name' => $message->name,
            'email' => $message->email,
            'message' => $message->message,
            'image_path' => $message->image_path,
            'ip_address' => $message->ip_address,
        ]);
    }
}
