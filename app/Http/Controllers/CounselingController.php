<?php

namespace App\Http\Controllers;

use App\Models\Counseling;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\CounselingMessages;

class CounselingController extends Controller
{
    public function index()
    {
        $title = 'Konseling';

        $users = User::role('Tamu Satgas')->get();
        $adminId = User::role('Admin')->first()->id;

        $chats = auth()->user()->getRoleNames()[0] == 'Tamu Satgas' ? Counseling::where('sender_id', auth()->user()->id)->orWhere('receiver_id', auth()->user()->id)->orderBy('created_at', 'ASC')->get() : [];

        return view('counseling.index', compact('title', 'users', 'adminId', 'chats'));
    }

    public function sendMessage(Request $request)
    {
        $message = Counseling::create([
            'sender_id' => Auth::id(),
            'receiver_id' => auth()->user()->getRoleNames()[0] == 'Tamu Satgas' ? 1 : $request->receiver_id,
            'message' => $request->message,
        ]);
        // CounselingMessages::dispatch($message);
        // event(new CounselingMessages($message));
        return back();
    }

    public function getMessages($receiverId)
    {
        $messages = Counseling::where(function ($query) use ($receiverId) {
            $query->where('sender_id', Auth::id())
                ->orWhere('receiver_id', Auth::id());
        })->where(function ($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)
                ->orWhere('receiver_id', $receiverId);
        })->get();

        return response()->json($messages);
    }
}
