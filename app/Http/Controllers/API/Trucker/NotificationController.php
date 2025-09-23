<?php

namespace App\Http\Controllers\API\Trucker;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trucker\NotificationResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponse;


    /**
     * Get all notifications for the authenticated trucker
     */
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()
            ->latest()
            ->get();

        return $this->sendResponse(
            NotificationResource::collection($notifications),
            __('Trucker notifications retrieved successfully.')
        );
    }


    /**
     * Mark a specific notification as read
     */
    public function markAsRead(Request $request, $notificationId)
    {
        $notification = $request->user()->notifications()
            ->where('id', $notificationId)
            ->first();

        if (!$notification) {
            return $this->sendError(__('Notification not found.'));
        }

        $notification->markAsRead();

        return $this->sendResponse([], __('Notification marked as read'));
    }

}
