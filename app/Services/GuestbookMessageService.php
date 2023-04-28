<?php

namespace App\Services;

use App\Http\Requests\GuestbookMessageRequest;
use App\Services\GuestbookMessageServiceInterface;
use App\Repositories\GuestbookMessageRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class GuestbookMessageService implements GuestbookMessageServiceInterface
{
    protected $repository;

    /**
     * Create a new service instance.
     *
     * @param  \App\Repositories\GuestbookMessageRepository  $repository
     * @return void
     */
    public function __construct(GuestbookMessageRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all guestbook messages.
     *
     * @param  string  $sortField
     * @param  string  $sortDirection
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllMessages($sortField, $sortDirection)
    {
        $messages = $this->repository->getAllMessages($sortField, $sortDirection);
        return $messages->appends(['sort' => $sortField, 'direction' => $sortDirection]);

    }

    /**
     * Get a guestbook message by id.
     *
     * @param  int  $id
     * @return \App\Models\GuestbookMessage
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getMessageById($id)
    {
        return $this->repository->getMessageById($id);
    }

    /**
     * Create a new guestbook message.
     *
     * @param \App\Http\Requests\GuestbookMessageRequest  $request
     * @return \App\Models\GuestbookMessage
     */
    public function storeMessage(GuestbookMessageRequest $request)
    {
        $data = $request->validated();
        $data['ip_address'] = $request->ip();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/guestbook_images');
            $data['image_path'] = Storage::url($path);
        }

        return $this->repository->createMessage($data);
    }

    /**
     * Update a guestbook message.
     *
     * @param  \App\Models\GuestbookMessage  $message
     * @param \App\Http\Requests\GuestbookMessageRequest  $request
     *      * @return void
     */
    public function updateMessage($message, GuestbookMessageRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/guestbook_images');
            $data['image_path'] = Storage::url($path);
        }

        $this->repository->updateMessage($message, $data);
    }

    /**
     * Delete a guestbook message.
     *
     * @param  \App\Models\GuestbookMessage  $message
     * @return void
     */
    public function deleteMessage($message)
    {
        $this->repository->deleteMessage($message);
    }
}
