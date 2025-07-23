<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $password;
    public $email;
    public $numOfUsers;
    public $popularPokemon;

    /**
     * Create a new message instance.
     */
    public function __construct($username, $password, $email, $numOfUsers, $popularPokemon)
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->numOfUsers = $numOfUsers;
        $this->popularPokemon = $popularPokemon;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email',
            with: [
                'username' => $this->username,
                'password' => $this->password,
                'email' => $this->email,
                'numOfUsers' => $this->numOfUsers,
                'popularPokemon' => $this->popularPokemon,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
