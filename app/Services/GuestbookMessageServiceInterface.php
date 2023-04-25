<?php

namespace App\Services;

use App\Http\Requests\GuestbookMessageRequest;
use App\Models\GuestbookMessage;
use Illuminate\Http\Request;

interface GuestbookMessageServiceInterface
{
    /**
     * Get all guestbook messages.
     *
     * @param  string  $sortField
     * @param  string  $sortDirection
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllMessages($sortField, $sortDirection);

    /**
     * Get a guestbook message by id.
     *
     * @param  int  $id
     * @return \App\Models\GuestbookMessage
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getMessageById($id);

    /**
     * Create a new guestbook message.
     *
     * @param  \App\Http\Requests\GuestbookMessageRequest $request
     * @return \App\Models\GuestbookMessage
     */
    public function storeMessage(GuestbookMessageRequest $request);

    /**
     * Update a guestbook message.
     *
     * @param  \App\Models\GuestbookMessage  $message
     * @param  \App\Http\Requests\GuestbookMessageRequest $request
     * @return void
     */
    public function updateMessage($message, GuestbookMessageRequest $request);

    /**
     * Delete a guestbook message.
     *
     * @param  \App\Models\GuestbookMessage  $message
     * @return void
     */
    public function deleteMessage($message);
}
