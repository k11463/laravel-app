<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailVerify extends Mailable
{
    use Queueable, SerializesModels;

    public $msg;

    public function __construct($message)
    {
        $this->msg = $message;
    }

    public function build()
    {
        return $this->view('welcome')->with(['msg' => $this->msg]);
    }
}
