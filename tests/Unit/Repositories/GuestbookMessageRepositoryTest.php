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

    // TODO: createMessage, updateMessage, and deleteMessage
}
