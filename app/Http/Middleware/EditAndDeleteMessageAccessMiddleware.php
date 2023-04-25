<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\GuestbookMessageServiceInterface;

class EditAndDeleteMessageAccessMiddleware
{

    protected $service;

    /**
     * Create a new middleware instance.
     *
     * @param \App\Services\GuestbookMessageServiceInterface $service
     */
    public function __construct(GuestbookMessageServiceInterface $service)
    {
        $this->service = $service;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $message = $this->service->getMessageById($request->route('id'));

        if (!$this->isAuthorized($request, $message)) {
            return $this->unauthorizedResponse();
        }

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\GuestbookMessage $message
     * @return bool
     */
    private function isAuthorized(Request $request, $message): bool
    {
        return $this->isIpAddressMatching($request, $message) && $this->isWithinTimeLimit($message);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\GuestbookMessage $message
     * @return bool
     */
    private function isIpAddressMatching(Request $request, $message): bool
    {
        return $message->ip_address === $request->ip();
    }

    /**
     * @param \App\Models\GuestbookMessage $message
     * @return bool
     */
    private function isWithinTimeLimit($message): bool
    {
        return $message->created_at->diffInMinutes() <= 5;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    private function unauthorizedResponse(): Response
    {
        return redirect()
            ->route('guestbook.index')
            ->with('error', 'You are not authorized to update this message. Your IP address must match the message, and no more than 5 minutes should have passed since you added it.');
    }
}
