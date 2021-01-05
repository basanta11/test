<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.user-created')
            ->subject('Account Registration')
            ->with([
                'name' => $this->user['name'],
                'email' => $this->user['email'],
                'password' => $this->user['password'],
                'domain' => tenant()->domains[0]
            ])
            ->markdown('mails.user-created');
    }
}
