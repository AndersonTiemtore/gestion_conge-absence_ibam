<?php

namespace App\Notifications;

use App\Models\DemandeConge;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ModificationDemandeNotification extends Notification
{
    use Queueable;

    public DemandeConge $demande;
    public User $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($demande, $user)
    {
        $this->demande = $demande;
        $this->user = $user;
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

        return [
            'title' => 'Modification de demande de congé',
            'message' => "L'employé(e) " . $this->user->nom . " " . $this->user->prenom .
                " a demandé une modification de sa demande de congé pour la période du " .
                Carbon::parse($this->demande->date_debut)->format('d/m/Y') .
                " au " . Carbon::parse($this->demande->date_fin)->format('d/m/Y') . ".",
            'demande' => $this->demande->id,
        ];
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
