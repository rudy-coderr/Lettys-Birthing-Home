<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class StaffAccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;      // available sa Blade
    public $email;     // optional, for convenience
    public $password;  // temporary password

    public function __construct(User $user, string $password)
    {
        $this->user     = $user;
        $this->email    = $user->email;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject("Your Staff Account")
                    ->view('emails.staff_account_created'); // Blade file mo
    }
}
