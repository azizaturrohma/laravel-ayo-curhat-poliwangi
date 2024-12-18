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

        $chats = auth()->user()->getRoleNames()[0] == ['Tamu Satgas', 'Admin'] ? Counseling::where('sender_id', auth()->user()->id)->orWhere('receiver_id', auth()->user()->id)->orderBy('created_at', 'ASC')->get() : [];

        return view('counseling.index', compact('title', 'users', 'adminId', 'chats'));
    }

    public function sendMessage(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'errror' => 'user belum login'
            ], 401);
        }

        $message = Counseling::create([
            'sender_id' => Auth::id(),
            // 'receiver_id' => in_array(auth()->user()->getRoleNames()[0], ['Tamu Satgas', 'admin']) ? 1 : $request->receiver_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);


        $sendMessage = [
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'time' => $message->created_at->format('D, d/m/Y'),
            'message' => $message->message,
        ];

        //

        // CounselingMessages::dispatch($message);
        // return dd($sendMessage);
        // $sendMessage = $request->input('message');
       event(new CounselingMessages($sendMessage));
        // broadcast(new CounselingMessages($request->get('message')))->toOthers();
        // return view('sendMessage', ['message' => $request->get('message')]);
        // return redirect()->back()->with(['success'=>'Post telah berhasil']);
        return response()->json(['message' => 'Message Succesfully!', 'data' => $sendMessage ]);

    }

    public function getMessages($receiverId)
    {
        $messages = Counseling::where(function ($query) use ($receiverId) {
            $query->where('sender_id', Auth::id())
                ->orWhere('receiver_id', Auth::id());
        })->where(function ($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)
                ->orWhere('receiver_id', $receiverId);
            // })->orderBy('created_at', 'asc')
        })->get();

        // return response()->json($messages);
        // return view('getMessage', ['message' => $request->get('message')]);
        return view('getMessages', compact('messages'));
}
}
