<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $body,
        public ?string $ip = null,
        public ?string $ua = null,
    ) {}

    public function build()
    {
        $subject = 'New request from '.$this->user->name.' (ID '.$this->user->id.')';
        return $this->subject($subject)
            ->view('emails.contact_request')
            ->text('emails.contact_request_text');
    }
}
