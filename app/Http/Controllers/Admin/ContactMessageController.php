<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('topic', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(15)->withQueryString();

        $stats = ContactMessage::query()
            ->selectRaw('COUNT(*) as total_messages')
            ->selectRaw("SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_messages")
            ->selectRaw("SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as read_messages")
            ->selectRaw("SUM(CASE WHEN status = 'replied' THEN 1 ELSE 0 END) as replied_messages")
            ->first();

        $totalMessages = (int) ($stats->total_messages ?? 0);
        $newMessages = (int) ($stats->new_messages ?? 0);
        $readMessages = (int) ($stats->read_messages ?? 0);
        $repliedMessages = (int) ($stats->replied_messages ?? 0);

        return view('admin.contact-messages.index', compact(
            'messages',
            'totalMessages',
            'newMessages',
            'readMessages',
            'repliedMessages'
        ));
    }

    public function show(ContactMessage $contactMessage)
    {
        if ($contactMessage->status === 'new') {
            $contactMessage->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function markReplied(ContactMessage $contactMessage)
    {
        $contactMessage->update([
            'status' => 'replied',
            'read_at' => $contactMessage->read_at ?? now(),
        ]);

        return back()->with('success', 'Pesan ditandai sebagai sudah dibalas.');
    }
}
