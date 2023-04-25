<?php

namespace Tests\Unit\Services;

use Log;
use Tests\TestCase;
use App\Models\GuestbookMessage;
use Illuminate\Http\UploadedFile;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Storage;
use App\Services\GuestbookMessageService;
use App\Http\Requests\GuestbookMessageRequest;
use Illuminate\Validation\ValidationException;
use App\Repositories\GuestbookMessageRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuestbookMessageServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $repository = new GuestbookMessageRepository();
        $this->service = new GuestbookMessageService($repository);
        Storage::fake('local');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Storage::disk('local')->deleteDirectory('public/guestbook_images');
    }

    public function testGetAllMessages()
    {
        // Create sample messages
        GuestbookMessage::factory()->count(3)->create();

        // Test
        $results = $this->service->getAllMessages('created_at', 'desc');

        // Assert count and type
        $this->assertCount(3, $results);
        foreach ($results as $result) {
            $this->assertInstanceOf(GuestbookMessage::class, $result);
        }
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

    public function testStoreMessage()
    {
        $requestData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello, this is a test message.',
            'ip_address' => '127.0.0.1',
            'image' => UploadedFile::fake()->image('test-image.jpg'),
        ];

        $request = $this->createGuestbookMessageRequest($requestData);
        $guestbookMessage = $this->service->storeMessage($request);

        $this->assertInstanceOf(GuestbookMessage::class, $guestbookMessage);
        $this->assertEquals('John Doe', $guestbookMessage->name);
        $this->assertEquals('john@example.com', $guestbookMessage->email);
        $this->assertEquals('Hello, this is a test message.', $guestbookMessage->message);
        $this->assertNotNull($guestbookMessage->ip_address);
        $this->assertNotNull($guestbookMessage->image_path);

        Storage::disk('local')->assertExists('public/guestbook_images/' . $guestbookMessage->image_filename);
    }

    private function createGuestbookMessageRequest(array $requestData)
    {
        $request = new GuestbookMessageRequest();
        $request->setMethod('POST');
        $request->headers->set('Content-Type', 'multipart/form-data');
        $request->files->set('image', $requestData['image']);
        $request->replace($requestData);

        // Set the container and IP address for the request
        $request->setContainer(Container::getInstance());
        $request->server->set('REMOTE_ADDR', $requestData['ip_address']);

        // Manually apply validation rules
        try {
            $request->validateResolved();
        } catch (ValidationException $e) {
            $this->fail('Validation failed: ' . $e->errors());
        }

        return $request;
    }

    public function testUpdateMessage()
    {

        // Create sample message
        $message = GuestbookMessage::factory()->create();
        Log::debug('Created message: ' . $message);

        // Create request data
        $requestData = [
            'name' => $message->name,
            'email' => $message->email,
            'message' => 'Updated message',
            'ip_address' => $message->ip_address,
            'image' => UploadedFile::fake()->image('test-image.jpg'),
        ];

        // Create request
        $request = $this->createGuestbookMessageRequest($requestData);

        // Test
        $updatedMessage = $this->service->updateMessage($message, $request);

        // updateMessage() doesn't return anuthing, it triggers the updateMessage() method on the repository, so we need to fetch the message again
        $updatedMessage = $this->service->getMessageById($message->id);

        // Assert
        $this->assertInstanceOf(GuestbookMessage::class, $updatedMessage);
        $this->assertEquals($message->id, $updatedMessage->id);
        $this->assertEquals($message->name, $updatedMessage->name);
        $this->assertEquals($message->email, $updatedMessage->email);
        $this->assertEquals('Updated message', $updatedMessage->message);
        $this->assertEquals($message->ip_address, $updatedMessage->ip_address);
        $this->assertNotNull($updatedMessage->image_path);

        Storage::disk('local')->assertExists('public/guestbook_images/' . $updatedMessage->image_filename);
    }

    public function testDeleteMessage()
    {
        // Create a message
        $guestbookMessage = GuestbookMessage::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello, this is a test message.',
            'ip_address' => '127.0.0.1',
        ]);

        // Delete the message
        $this->service->deleteMessage($guestbookMessage);

        // Assert the message was deleted
        $this->assertDatabaseMissing('guestbook_messages', [
            'id' => $guestbookMessage->id,
        ]);
    }
}
