<?php

namespace App\Repositories;

interface GuestbookMessageRepositoryInterface
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
     * @param  array  $data
     * @return \App\Models\GuestbookMessage
     */
    public function createMessage($data);

    /**
     * Update a guestbook message.
     *
     * @param  \App\Models\GuestbookMessage  $message
     * @param  array  $data
     * @return void
     */
    public function updateMessage($message, $data);

    /**
     * Delete a guestbook message.
     *
     * @param  \App\Models\GuestbookMessage  $message
     * @return void
     */
    public function deleteMessage($message);
}
