<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class AgentWelcomeMail extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    public $title = '';
    public $agent;
    public $the_password;

    public function __construct($agent){
        $this->title = 'Welcome to ' . env('APP_NAME');
        $this->agent = $agent;
        $this->the_password = $agent->password_plain;
    }

    public function build() {
        return $this->subject($this->title)->view('mail.agent-welcome-mail');
    }
}
