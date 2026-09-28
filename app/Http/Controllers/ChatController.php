<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChatMessage;
use App\Models\Lead;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string|max:100',
            'message' => 'required|string|max:2000',
        ]);

        $sessionId = $request->input('session_id');
        $userMessage = trim($request->input('message'));

        ChatMessage::create([
            'session_id' => $sessionId,
            'role' => 'user',
            'content' => $userMessage,
        ]);

        $faqReply = $this->getFaqReply($userMessage);
        $aiReply = $faqReply ?? $this->generateAiReply($sessionId, $userMessage);

        ChatMessage::create([
            'session_id' => $sessionId,
            'role' => 'assistant',
            'content' => $aiReply,
        ]);

        return response()->json(['reply' => $aiReply]);
    }

    private function generateAiReply($sessionId, $userMessage)
    {
        $systemPrompt = "You are the friendly, conversational front-desk assistant for Infliction Gym in Magalang, Pampanga. Use only this verified business information: InflictionGym@gmail.com; open daily; base memberships start at PHP 2,650/month; FITPASS is PHP 483 per credit; no contracts; location is Magalang, Pampanga. Answer in a warm, natural, conversational tone. Keep replies brief but helpful, usually 2-4 sentences unless the user asks for more detail. You can give general fitness, workout, beginner, nutrition, motivation, and routine guidance as long as it stays safe and general. Never ask for passwords, full names, phone numbers, email addresses, or any other personal information in chat. If they ask for membership details, pricing, or privacy-sensitive information, politely direct them to the website or email InflictionGym@gmail.com instead of asking for private details. Do not invent prices, schedules, or policies. Do not mention that you are an AI. Keep your tone helpful and professional.";

        $recentMessages = ChatMessage::where('session_id', $sessionId)
            ->latest()
            ->take(6)
            ->get()
            ->reverse()
            ->map(function ($msg) {
                return ['role' => $msg->role, 'content' => $msg->content];
            })
            ->toArray();

        $messagesPayload = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $recentMessages
        );

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'openai/gpt-oss-20b',
                'messages' => $messagesPayload,
                'temperature' => 0.5,
                'max_tokens' => 220,
            ]);

            if ($response->failed()) {
                return 'System Error: Please check your GROQ_API_KEY in the .env file and restart the server.';
            }

            return (string) $response->json('choices.0.message.content');
        } catch (\Exception $e) {
            return 'Sorry, our system is updating. Feel free to email us at InflictionGym@gmail.com!';
        }
    }

    private function getFaqReply($message)
    {
        $text = strtolower(trim($message));

        if ($text === '' || strlen($text) < 2) {
            return null;
        }

        $generalGymConversation = preg_match('/\b(beginner|build muscle|gain muscle|lose fat|fat loss|weight loss|strength training|cardio|workout plan|meal plan|diet|nutrition|recovery|fitness goal|routine|motivation|personal training|training program|exercise)\b/', $text);
        if ($generalGymConversation) {
            return null;
        }

        if (preg_match('/\b(password|passcode|pin|secret|personal information|phone number|email address|your email|your phone|full name|name and email|tell me your password|what is your password)\b/', $text)) {
            return 'For privacy and security, I do not ask for passwords or personal details in chat. If you want to ask about memberships or class details, you can contact us at InflictionGym@gmail.com.';
        }

        if (preg_match('/\b(free trial|trial|1 day free|1-day free|try before joining|day pass)\b/', $text)) {
            return 'You can start with a 1-day free trial by signing up through our website or contacting us at InflictionGym@gmail.com. We would be happy to guide you through the next steps.';
        }

        if (preg_match('/\b(fitpass|credit|pay as you go|pay-as-you-go|flexible access|usage pass)\b/', $text)) {
            return 'FITPASS is PHP 483 per credit, and it is a flexible option for members who want quick, convenient access without a long-term contract.';
        }

        if (preg_match('/\b(price|pricing|cost|membership|plan|base membership|monthly fee|monthly plan|joining fee|how much)\b/', $text)) {
            return 'Our base memberships start at PHP 2,650/month. We also offer FITPASS, and there is no joining fee for the base membership option.';
        }

        if (preg_match('/\b(schedule|coach|coaches|class time|class times|timetable|training schedule|workout schedule|class schedule|coach availability)\b/', $text)) {
            return 'We do not have the daily coach schedule posted here, but you can check our website or email InflictionGym@gmail.com for the most up-to-date class times and coach availability.';
        }

        if (preg_match('/\b(hours|open|when are you open|timing|time|open daily|daily hours|at what time)\b/', $text)) {
            return 'We are open daily. If you want, I can help you choose the best membership plan for your schedule.';
        }

        if (preg_match('/\b(contact|email|phone|website|reach you|talk to someone|call|how to contact|message|where are you|location|address|magalang|pampanga)\b/', $text)) {
            return 'Infliction Gym is located in Magalang, Pampanga. You can reach us by email at InflictionGym@gmail.com, or check our website for updates and membership details.';
        }

        if (preg_match('/\b(benefits|what do you offer|services|equipment|locker|personal training|group classes|classes|hyrox|yoga|bodycombat|trainer|trainers|coaching|gym facilities)\b/', $text)) {
            return 'Infliction Gym offers equipment access, locker room access, group classes, personal training, and expert coaching support. We also provide flexible membership options for different fitness goals.';
        }

        if (preg_match('/\b(no contract|contract|long term|commitment|monthly|month|flexible membership|flexible options)\b/', $text)) {
            return 'We offer flexible options with no long-term contract requirement, and FITPASS is designed for members who want easy, convenient access.';
        }

        if (preg_match('/\b(what can you help with|help|gym|membership|start|join|interested|member|new member|register|sign up|sign up for membership)\b/', $text) && !preg_match('/\b(beginner|build muscle|gain muscle|lose fat|fat loss|weight loss|strength training|cardio|workout plan|meal plan|diet|nutrition|recovery|fitness goal|routine|motivation|personal training|training program|exercise)\b/', $text)) {
            return 'I can help with memberships, FITPASS, hours, contact details, class info, equipment access, free trial questions, and general gym information for Infliction Gym Magalang.';
        }

        if (preg_match('/\b(how are you|hello|hi|hey|good morning|good afternoon|good evening)\b/', $text) && !preg_match('/\b(fitpass|free trial|price|pricing|cost|membership|plan|schedule|coach|hours|contact|location|help|gym|equipment|classes|contract|join|member|trainer|register|sign up|beginner|build muscle|gain muscle|lose fat|fat loss|weight loss|strength training|cardio|workout plan|meal plan|diet|nutrition|recovery|fitness goal|routine|motivation|personal training|training program|exercise)\b/', $text)) {
            return 'I\'m good—thanks for asking! How can I help with your gym journey today?';
        }

        return null;
    }

    private function extractAndSaveLeadInfo($sessionId, $text)
    {
        // The chat bot never asks for personal information and should not collect passwords or private details.
        // We intentionally do not save email/phone information from chat messages.
        return;
    }
}