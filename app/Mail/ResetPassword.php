<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class ResetPassword extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    public $title = 'Password Reset Request';
    public $new_password;
    public $user;

    public function __construct($user, $new_password){
        $this->user = $user;
        $this->new_password = $new_password;
    }


    public function build() {
        return $this->subject($this->title)->view('mail.password-reset');
    }
}
