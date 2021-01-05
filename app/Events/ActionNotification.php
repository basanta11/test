<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Notification;
use Str;

class ActionNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $from;
    private $to;
    // public $notification;
    private $model;
    private $model_id;
    private $message;
    private $model_link;
    private $notification;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    
    public function __construct($from,$to,$m,$mid, $model_link, $notification)
    {
        if(tenant()->plan!='large'){
            return false;
        }
        //
        if(tenant()->plan!='large'){
            return;
        }
        $this->from=$from;
        $this->to=$to;
        $this->model=$m;
        $this->model_id=$mid;
        $this->model_link=$model_link;
        $this->notification=$notification;
        $this->data=[
            'notification'=>$notification,
            'model_link'=>$this->model_link,
            'created_at'=>now(),
        ];

        Notification::create([
            'id'=>Str::uuid(),
            'type'=>'App\Events\ActionNotification',
            'notifiable_type'=>'App\User',
            'notifiable_id'=>$this->to->id,
            'data'=>json_encode([
                'from'=>$this->from->id,
                'notification'=>$this->notification,
                'model_link'=>$this->model_link,
                'model'=>$this->model,
                'model_id'=>$this->model_id,
            ]),
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if(tenant()->plan!='large'){
            return false;
        }
        return new Channel('notification.users.'.$this->to->id);
    }
  
}
