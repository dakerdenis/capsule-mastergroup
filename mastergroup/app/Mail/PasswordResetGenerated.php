<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetGenerated extends Mailable
{
    use Queueable, SerializesModels;

    public string $username;
    public string $plainPassword;

    public function __construct(string $username, string $plainPassword)
    {
        $this->username = $username;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        return $this->subject('Your new password')
            ->view('emails.password_generated')
            ->with([
                'username' => $this->username,
                'password' => $this->plainPassword,
            ]);
    }
}
