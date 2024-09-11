<?php

namespace App\Livewire;

use App\Models\User;
use App\Events\MessageSendEvent;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;

class ChatComponent extends Component
{
    public $user;
    public $sender_id;
    public $receiver_id;
    public $message = '';
    public $messages = []; // Initialize messages array

    public function mount($user_id){

        $this->sender_id = auth()->user()->id;
        $this->receiver_id = $user_id;

        $messages = Message::where(function($query){
            // sender is sending message to reciever
            $query->where('sender_id',$this->sender_id)
                  ->where('receiver_id',$this->receiver_id);

        })->orWhere(function($query){
            // receiver is sending message to sender
             $query->where('sender_id',$this->receiver_id)
                  ->where('receiver_id',$this->sender_id);

        })->with('sender:id,name','receiver:id,name')
        ->get();

        foreach($messages as $message){
            $this->appendChatMessage($message);
        }
        $this->user = User::whereId($user_id)->first();
    }

    public function sendMessage()
    {
        $chatMessage = new Message();
        $chatMessage->sender_id = $this->sender_id;
        $chatMessage->receiver_id = $this->receiver_id;
        $chatMessage->message = $this->message;
        $chatMessage->save();

        $this->appendChatMessage($chatMessage);
        broadcast(new MessageSendEvent($chatMessage))->toOthers();

        $this->message = '';
    }

    // listener for broadcast
    #[On('echo-private:chat-channel.{sender_id},MessageSendEvent')]
    public function listenForMessage($event){
        $chatMessage = Message::whereId($event['message']['id'])
        ->with('sender:id,name','receiver:id,name')
        ->first();
        $this->appendChatMessage($chatMessage);
    }

    public function appendChatMessage($message)
    {
      $this->messages[] = [
        'id' => $message->id,
        'message' => $message->message,
        'sender' => $message->sender->name,
        'receiver' => $message->receiver->name,
      ];
    }

    public function render()
    {
        return view('livewire.chat-component');
    }

}
