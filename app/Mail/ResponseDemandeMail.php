<?php

namespace App\Mail;

use App\Models\DemandeConge;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResponseDemandeMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public DemandeConge $demande;
    public $statut;
    /**
     * Create a new message instance.
     */
    public function __construct($user, $demande, $statut)
    {
        $this->user = $user;
        $this->demande = $demande;
        $this->statut = $statut;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Response à votre demande de congé',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.reponse-demande',
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
