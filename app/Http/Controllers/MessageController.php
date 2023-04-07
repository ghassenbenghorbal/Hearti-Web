<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|max:320',
            'sender' => 'required|exists:App\Models\User,id|different:receiver',
            'receiver' => 'required|exists:App\Models\User,id|different:sender',
            // 'attachement' => 'file',
        ]);

        $message = Message::create([
            'text' => $request->text,
            'sender' => $request->sender,
            'receiver' => $request->receiver,
            'attachement' => $request->attachement ? $request->attachement->store('attachements') : "attachement",
        ]);
        $message->sender = User::findOrFail($message->sender);
        $message->receiver = User::findOrFail($message->receiver);
        return $message;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($sender, $receiver)
    {
        $sender = User::findOrFail($sender);
        $receiver = User::findOrFail($receiver);

        $messages = Message::where('sender', $sender)->where('receiver', $receiver)->orderBy('created_at', 'desc')->get();

        return $messages;
    }

    public function getDiscussions($id)
    {
        $user = User::findOrFail($id);
        
        $patients = Message::where('sender', $id)
                            ->orWhere('receiver', $id)
                            ->select(\DB::raw("CASE 
                                            WHEN sender = ".$id." THEN receiver 
                                            WHEN receiver = ".$id." THEN sender 
                                            END AS user_id"), 'users.name as patient_name')
                            ->join('users', function($join) use($id) {
                                $join->on('users.id', '=', \DB::raw("IF(messages.sender = ".$id.", messages.receiver, messages.sender)"));
                            })
                            ->distinct()
                            ->get();
        //get last message with each patient
        foreach ($patients as $patient) {
            $patient->last_message = Message::where('sender', $id)
                                            ->where('receiver', $patient->user_id)
                                            ->orWhere('sender', $patient->user_id)
                                            ->where('receiver', $id)
                                            ->orderBy('created_at', 'desc')
                                            ->with('sender', 'receiver')
                                            ->first();
            $patient->connected = false;
            $patient->unread = 0;
            $patient->messages = [];
        }
        return $patients;
    }
    public function getMessages($sender, $receiver)
    {
        User::findOrFail($sender);
        User::findOrFail($receiver);

        $messages = Message::where(function ($query) use ($sender, $receiver) {
                                $query->where('sender', $sender)
                                    ->where('receiver', $receiver);
                            })
                            ->orWhere(function ($query) use ($sender, $receiver) {
                                $query->where('sender', $receiver)
                                    ->where('receiver', $sender);
                            })
                            ->orderBy('created_at', 'asc')
                            ->with('sender', 'receiver')
                            ->get();
        return $messages;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
