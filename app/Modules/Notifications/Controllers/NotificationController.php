<?php

namespace App\Modules\Notifications\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\Notifications\Models\CoreNotification; 

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = CoreNotification::orderByDesc('created_at')->get();
        return view('notifications::index', compact('notifications'));

    }

    // public function markAsChecked(int $id)
    // {
    //     $notif = CoreNotification::findOrFail($id);
    //     $notif->markChecked();
    //     return back()->with('success', 'Notificação marcada como lida.');
    // }

    // public function markAsIgnored(int $id)
    // {
    //     $notif = CoreNotification::findOrFail($id);
    //     $notif->markIgnored();

    //     return back()->with('success', 'Notificação ignorada.');
    // }

    // public function markAllAsChecked()
    // {
    //     CoreNotification::where('status', 'active')->update([
    //         'status'      => 'checked',
    //         'resolved_at' => now(),
    //     ]);

    //     return back()->with('success', 'Todas as notificações foram marcadas como lidas.');
    // }
}
