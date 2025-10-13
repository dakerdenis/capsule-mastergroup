<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public ?string $reason = null) {}

    public function build()
    {
        return $this->subject('CAPSULE PPF — Access Update')
            ->view('emails.user_rejected')
            ->with([
                'user'   => $this->user,
                'reason' => $this->reason,
            ]);
    }
}
