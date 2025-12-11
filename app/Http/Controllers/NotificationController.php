<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            if (isset($notification->data['url']) && !empty($notification->data['url'])) {
            return redirect($notification->data['url']);
        }
        }

        return back();
    }

    public function delete($id)
    {
        $user = Auth::user();
        
        // Find and delete the notification from both read and unread
        $notification = $user->notifications->where('id', $id)->first() 
                     ?? $user->readNotifications->where('id', $id)->first();
        
        if ($notification) {
            $notification->delete();
            
            // Return JSON for AJAX requests
            if (request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => true, 'message' => 'Notification deleted']);
            }
        }

        return back();
    }
}
