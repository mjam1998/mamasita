<?php

namespace App\Http\Controllers;

use Coderflex\LaravelTicket\Models\Ticket;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentController extends Controller
{
    public function viewAttachment($ticketId, $filename)
    {
        $ticket = Ticket::findOrFail($ticketId);
//        $user = auth()->user();

//        // بررسی دسترسی: صاحب تیکت (user/mama) یا سوپر ادمین
//        $isOwner = ($user->id === $ticket->user_id || ($ticket->mama_id && $user->id === $ticket->mama_id));
//        $isAdmin = $user->hasRole('super-admin');
//
//        if (! $isOwner && ! $isAdmin) {
//            abort(403, 'دسترسی غیرمجاز');
//        }

        $path = 'tickets/attachments/'.$filename;
//        if (! Storage::disk('local')->exists($path)) {
//            abort(404, 'فایل یافت نشد');
//        }

        return Storage::disk('local')->response($path);
    }
}
