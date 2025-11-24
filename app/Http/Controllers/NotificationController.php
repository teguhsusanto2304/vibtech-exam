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
}
