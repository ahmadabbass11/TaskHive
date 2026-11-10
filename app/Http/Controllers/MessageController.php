<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        Log::info('📩 Request received:', $request->all());

        if (!Auth::check()) {
            return response()->json(['error' => 'User is not authenticated'], 401);
        }

        // Validate input (either text or file required)
        $request->validate([
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:5120', // 5MB max
            'channel_id' => 'required|exists:conversations,id',
        ]);

        $conversation = Conversation::find($request->channel_id);
        if (!$conversation) {
            return response()->json(['error' => 'Conversation not found'], 404);
        }

        // Determine receiver (for private messages)
        $receiver = $conversation->users()
            ->wherePivot('user_id', '!=', Auth::id())
            ->first();

        // Initialize message properties
        $messageType = 'text';  // Default type is text
        $filePath = null;
        $mimeType = null;

        // **Ensure file is properly handled**
        if ($request->hasFile('file')) {
            Log::info('📂 File detected:', ['name' => $request->file('file')->getClientOriginalName()]);

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $mimeType = $file->getClientMimeType();
            $messageType = 'file';  // ✅ Ensure message type is set to "file"
            Log::info('📂 Type changed to file', ['name' => $request->file('file')->getClientOriginalName(), 'message_type' => $messageType]);
        }

        // **Ensure that "text" messages are correctly assigned**
        if (!$filePath && !$request->message) {
            return response()->json(['error' => 'Message cannot be empty'], 400);
        }

        Log::info('✅ Message saved:', ['message_type' => $messageType]);

        // **Create message in database**
        $message = Message::create([
            'conversation_id' => $request->channel_id,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver ? $receiver->id : null,
            'message_content' => $request->message ?? '',
            'message_type' => $messageType,  // ✅ Now it will correctly store "file" or "text"
            'file_path' => $filePath ? asset("storage/$filePath") : null,
            'mime_type' => $mimeType,
            'message_status' => 'sent',
        ]);

        Log::info('✅ Message 2 saved:', ['message_type' => $message->message_type]);

        // **Broadcast the message in real-time**
        try {
            broadcast(new MessageSent($message))->toOthers();
            Log::info('📡 Message broadcasted successfully');
        } catch (\Exception $e) {
            Log::error('❌ Error broadcasting message:', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'message' => 'Message sent successfully!',
            'data' => $message,
        ], 201);
    }



}
