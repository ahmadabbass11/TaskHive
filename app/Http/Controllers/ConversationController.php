<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function getConversationsAndChannels()
    {
        $userId = Auth::id();

        // Fetch private one-to-one conversations
        $privateConversations = User::select('users.id as user_id', 'users.name', 'users.email', 'users.profile_picture', 'conversations.id as conversation_id')
            ->join('user_conversation', 'users.id', '=', 'user_conversation.user_id')
            ->join('conversations', 'user_conversation.conversation_id', '=', 'conversations.id')
            ->where('conversations.type', 'private') // Only private conversations
            ->where('user_conversation.user_id', '!=', $userId) // Exclude the current user
            ->whereExists(function ($query) use ($userId) {
                $query->select('user_id')
                    ->from('user_conversation as uc2')
                    ->whereColumn('uc2.conversation_id', 'user_conversation.conversation_id')
                    ->where('uc2.user_id', $userId);
            })
            ->distinct()
            ->get();

        // Fetch group channels the user is part of (Public Conversations)
        $groupChannels = Conversation::select('conversations.id', 'conversations.name', 'conversations.type', 'conversations.description', 'conversations.created_at')
            ->join('user_conversation', 'conversations.id', '=', 'user_conversation.conversation_id')
            ->where('conversations.type', 'public') // Only public/group channels
            ->where('user_conversation.user_id', $userId)
            ->distinct()
            ->get();


        return view("main.homepage", [
            'private_conversations' => $privateConversations,
            'group_channels' => $groupChannels,
        ]);
    }

    public function createConversation(Request $request)
    {
        $partnerId = $request->input('partner_id');
        $currentUser = auth()->user();

        // 1) Check if user is trying to talk to themselves
        if ($partnerId == $currentUser->id) {
            return response()->json([
                'error' => 'You cannot start a conversation with yourself.'
            ], 400);
        }

        // 2) Check if a private conversation already exists with this user
        $existingConversation = Conversation::where('type', 'private')
            ->whereHas('users', function ($q) use ($currentUser) {
                $q->where('user_id', $currentUser->id);
            })
            ->whereHas('users', function ($q) use ($partnerId) {
                $q->where('user_id', $partnerId);
            })
            ->first();

        // If found, throw an error
        if ($existingConversation) {
            return response()->json([
                'error' => 'A conversation with this user already exists.'
            ], 400);
        }

        // If no conversation exists, create a new one
        $conversation = Conversation::create([
            'name' => 'Private Chat',  // or something more descriptive
            'type' => 'private',
            'description' => 'Private conversation between users',
        ]);

        // Attach both users to this conversation
        $conversation->users()->attach([$currentUser->id, $partnerId]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'conversation_name' => $conversation->name,
        ]);
    }

    // Get the participants of a conversation
    public function getParticipants($conversationId)
    {
        // Find the conversation by ID
        $conversation = Conversation::findOrFail($conversationId);

        // Get the participants
        $participants = $conversation->users;

        return response()->json(['participants' => $participants]);
    }




    public function getMessagesForChannel($conversationId)
    {
        $messages = Message::select(
            'messages.id',
            'messages.sender_id',
            'messages.receiver_id',
            'messages.conversation_id',
            'messages.message_content',
            'messages.message_type',
            'messages.file_path',
            'messages.mime_type',
            'messages.message_status',
            'messages.created_at',
            'messages.updated_at',
            'users.name as sender_name',
            'users.profile_picture'
        )
            ->join('users', 'users.id', '=', 'messages.sender_id')
            ->where('messages.conversation_id', $conversationId)
            ->orderBy('messages.created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function getUserDetails($userId)
    {
        //optional if it did not work discard
        $user = User::select('id', 'name', 'email', 'profile_picture', 'created_at')
            ->where('id', $userId)
            ->first();

        return response()->json($user);
    }


}
