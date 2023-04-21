<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\GuestbookMessage;
use App\Services\GuestbookMessageService;
use App\Repositories\GuestbookMessageRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class GuestbookMessageServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $repository = new GuestbookMessageRepository();
        $this->service = new GuestbookMessageService($repository);
    }

    public function testGetAllMessages()
    {
        // Create sample messages
        $messages = GuestbookMessage::factory()->count(3)->create();

        // Test
        $results = $this->service->getAllMessages('created_at', 'desc');

        $this->assertCount(3, $results);
    }

    public function testGetMessageById()
    {
        // Create sample message
        $message = GuestbookMessage::factory()->create();

        // Test
        $result = $this->service->getMessageById($message->id);

        $this->assertInstanceOf(GuestbookMessage::class, $result);
        $this->assertEquals($message->id, $result->id);
    }

    // TODO: storeMessage, updateMessage, and deleteMessage
}
