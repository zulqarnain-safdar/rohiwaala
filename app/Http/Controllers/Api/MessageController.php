<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $conversationId = $request->get('conversation_id');
        $jobId = $request->get('job_id');

        $query = Message::with(['sender', 'receiver', 'job'])
            ->where(function($q) use ($user) {
                $q->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
            });

        if ($conversationId) {
            $otherUser = User::find($conversationId);
            if ($otherUser) {
                $query->where(function($q) use ($user, $otherUser) {
                    $q->where('sender_id', $user->id)
                      ->where('receiver_id', $otherUser->id);
                })->orWhere(function($q) use ($user, $otherUser) {
                    $q->where('sender_id', $otherUser->id)
                      ->where('receiver_id', $user->id);
                });
            }
        }

        if ($jobId) {
            $query->where('job_id', $jobId);
        }

        $messages = $query->orderBy('created_at', 'asc')->paginate(50);

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    public function conversations(Request $request)
    {
        $user = $request->user();
        
        $conversations = Message::with(['sender', 'receiver', 'job'])
            ->where(function($q) use ($user) {
                $q->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
            })
            ->selectRaw('
                CASE 
                    WHEN sender_id = ? THEN receiver_id 
                    ELSE sender_id 
                END as other_user_id,
                MAX(created_at) as last_message_at,
                COUNT(*) as message_count
            ', [$user->id])
            ->groupBy('other_user_id')
            ->orderBy('last_message_at', 'desc')
            ->get();

        $conversationList = [];
        foreach ($conversations as $conv) {
            $otherUser = User::find($conv->other_user_id);
            if ($otherUser) {
                $lastMessage = Message::where(function($q) use ($user, $otherUser) {
                    $q->where('sender_id', $user->id)
                      ->where('receiver_id', $otherUser->id);
                })->orWhere(function($q) use ($user, $otherUser) {
                    $q->where('sender_id', $otherUser->id)
                      ->where('receiver_id', $user->id);
                })->orderBy('created_at', 'desc')->first();

                $unreadCount = Message::where('sender_id', $otherUser->id)
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->count();

                $conversationList[] = [
                    'other_user' => $otherUser,
                    'last_message' => $lastMessage,
                    'message_count' => $conv->message_count,
                    'unread_count' => $unreadCount,
                    'last_message_at' => $conv->last_message_at,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'conversations' => $conversationList
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
            'message_type' => 'nullable|in:text,image,file,location',
            'attachment_url' => 'nullable|string',
            'job_id' => 'nullable|exists:technician_jobs,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $receiver = User::find($request->receiver_id);

        if (!$receiver) {
            return response()->json([
                'success' => false,
                'message' => 'Receiver not found'
            ], 404);
        }

        // Check if users can communicate (both should be farmers or technicians)
        if (!in_array($user->role, ['farmer', 'technician']) || 
            !in_array($receiver->role, ['farmer', 'technician'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid communication between user types'
            ], 400);
        }

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'job_id' => $request->job_id,
            'message' => $request->message,
            'message_type' => $request->message_type ?? 'text',
            'attachment_url' => $request->attachment_url,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message->load(['sender', 'receiver', 'job'])
        ], 201);
    }

    public function markAsRead(Request $request, Message $message)
    {
        if ($message->receiver_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $message->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read'
        ]);
    }

    public function markConversationAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sender_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $senderId = $request->sender_id;

        Message::where('sender_id', $senderId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Conversation marked as read'
        ]);
    }

    public function unreadCount(Request $request)
    {
        $count = Message::where('receiver_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $count
        ]);
    }

    public function destroy(Request $request, Message $message)
    {
        if ($message->sender_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }
}