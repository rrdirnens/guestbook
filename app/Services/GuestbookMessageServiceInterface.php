<?php

namespace App\Services;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\GuestbookMessage
     */
    public function storeMessage(Request $request);

    /**
     * Update a guestbook message.
     *
     * @param  \App\Models\GuestbookMessage  $message
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function updateMessage($message, Request $request);

    /**
     * Delete a guestbook message.
     *
     * @param  \App\Models\GuestbookMessage  $message
     * @return void
     */
    public function deleteMessage($message);
}
