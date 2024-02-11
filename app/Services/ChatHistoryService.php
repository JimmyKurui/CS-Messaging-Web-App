<?php 

namespace App\Services;
use Illuminate\Support\Facades\Redis;

class ChatHistoryService
{
    public static function appendMessageToChatHistory($userId, $message)
    {
        // Append message to the user's chat history hash
        Redis::hset("chat_history:{$userId}", now()->timestamp, $message);
    }

    public static function getUserChatHistory($userId)
    {
        // Retrieve the entire chat history hash for the user
        return Redis::hgetall("chat_history:{$userId}");
    }

    public static function storeChatHistoryForDay($userId, $chatHistory)
    {
        // Store chat history with TTL for a day
        Redis::hset("chat_history:{$userId}", $chatHistory);
        Redis::expire("chat_history:{$userId}", 86400); // 24 hours in seconhads
    }
}
