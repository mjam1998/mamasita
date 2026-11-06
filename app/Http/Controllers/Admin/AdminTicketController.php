<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class AdminTicketController extends Controller
{
    public function index()
    {
        return view('admin.ticket.index');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['messages.user', 'user', 'mama']);

        return view('admin.ticket.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate(['message' => 'required', 'attachment' => 'nullable|file|max:5120']);

        $attachmentName = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $attachmentName = 'ticket_'.$ticket->id.'_'.time().'.'.$extension;

            // اگر تصویر است فشرده‌سازی شود، در غیر این صورت معمولی ذخیره شود
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                $imageManager = new ImageManager(new Driver);
                $eduDecoded = $imageManager->decode($request->file('attachment'));
                $eduEncoded = $eduDecoded->encode(new WebpEncoder(quality: 80));
                Storage::disk('local')->put('tickets/attachments/'.$attachmentName, (string) $eduEncoded);
            } else {
                $file->storeAs('tickets/attachments', $attachmentName, 'local');
            }
        }

        $ticket->messages()->create([
//            'user_id' => auth()->id(),
            'user_id' => 1,
            'message' => $request->message,
            'attachment' => $attachmentName,
        ]);



        return back()->with('success', 'پاسخ ارسال شد.');
    }

    public function toggleStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,closed',
        ]);

        if ($request->status === 'closed') {
            $ticket->close(); // استفاده از متد داخلی پکیج
        } else {
            $ticket->reopen(); // استفاده از متد داخلی پکیج
        }

        return back()->with('success', 'وضعیت تیکت بروزرسانی شد.');
    }
}
