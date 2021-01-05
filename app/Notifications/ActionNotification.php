<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

use App\User;

class ActionNotification extends Notification
{
    use Queueable;


    public $from;
    public $to;
    // public $notification;
    public $model;
    public $model_id;
    public $message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($from,$to,$m,$mid)
    {
        //
        $this->from=$from;
        $this->to=$to;
        $this->model=$m;
        $this->model_id=$mid;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    public function toBroadcast($notifiable)
    {
        // dd($notifiable);
        return new BroadcastMessage([
            'notification'=>$this->makeNotification()[0],
            'model_link'=>$this->makeNotification()[1],
            'created_at'=>now(),
        ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'from'=>$this->from->id,
            'notification'=>$this->makeNotification()[0],
            'model_link'=>$this->makeNotification()[1],
            'model'=>$this->model,
            'model_id'=>$this->model_id,
        ];
    }


    public function makeNotification()
    {
        switch ($this->to->role_id){
            // outer case
            case 1:
                return [null,null];
                break;
            case 2:
                return [null,null];
                break;
            case 3:
                return $this->notificationStudent();
                break;
            case 4:
                return [null,null];
                break;
            case 5:
                return $this->notificationStudent();
                break;
            default:
                return [null,null];
                break;

        }
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function notificationStudent()
    {
        switch ($this->model) {
            case 'App\Homework':
                $notification= ['<a href="/teachers/'.$this->from->id.'">'.$this->from->name.'</a> has added <a href="/my-homeowrk/'.$this->model_id.'">homework</a>','/my-homeowrk/'.$this->model_id];
                break;
            
            default:
                # code...
                $notification= ['<a href="/software/my-club/members/'.$this->from->id.'">'.$this->from->name.'</a> has done something.','#'];
                break;
        }
        return $notification;
    }
}
