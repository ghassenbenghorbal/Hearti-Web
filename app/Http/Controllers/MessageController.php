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
            'attachement' => $request->attachement,
        ]);
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
        $messages = Message::where('sender', $id)
                            ->orWhere('receiver', $id)
                            ->distinct()
                            ->select(\DB::raw("CASE 
                                                WHEN sender = ".$id." THEN receiver 
                                                WHEN receiver = ".$id." THEN sender 
                                            END AS user_id"),
                                     'text',
                                     'sender',
                                     'created_at',)
                            ->with('sender')
                            ->get();
        foreach($messages as $message) {
            $message->user = User::findOrFail($message->user_id);
        }
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
