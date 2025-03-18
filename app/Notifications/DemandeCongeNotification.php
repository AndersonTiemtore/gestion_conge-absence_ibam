<?php

namespace App\Notifications;

use App\Models\DemandeConge;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemandeCongeNotification extends Notification
{
    use Queueable;

    public DemandeConge $demande;
    public $statut;
    /**
     * Create a new notification instance.
     */
    public function __construct($demande, $statut = null)
    {
        $this->demande = $demande;
        $this->statut = $statut;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {

        if ($this->statut) {
            return [
                'title' => 'Demande de congé ' . $this->statut,
                'message' => "Votre demande de congé du " . Carbon::parse($this->demande->date_debut)->format('d/m/Y') .
                    " au " . Carbon::parse($this->demande->date_fin)->format('d/m/Y') . " a été $this->statut.",
            ];
        } else {
            return [
                'title' => 'Nouvelle demande de congé',
                'message' => "Une nouvelle demande de congé a été soumise pour la période du " .
                    Carbon::parse($this->demande->date_debut)->format('d/m/Y') .
                    " au " . Carbon::parse($this->demande->date_fin)->format('d/m/Y') . ".",
            ];
        }
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
