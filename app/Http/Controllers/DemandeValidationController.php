<?php

namespace App\Http\Controllers;

use App\Mail\ResponseDemandeMail;
use App\Models\DemandeConge;
use App\Models\StatutDemande;
use App\Notifications\DemandeCongeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class DemandeValidationController extends Controller
{
    //

    public function accepter(DemandeConge $demande)
    {
        $statutAccepter = StatutDemande::where('statut',  'accepter')->first();

        $demande->update(['statut_demande_id' => $statutAccepter->id]);
        try {
            Mail::to($demande->employe->user->email)->send(new ResponseDemandeMail($demande->employe->user, $demande, 'acceptée'));
            Notification::send($demande->employe->user, new DemandeCongeNotification($demande, 'acceptée'));
            return redirect()->route('dashboard')->with('message', 'Demande acceptée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Une erreur est survenue lors de l\'envoi du mail : ' . $e->getMessage());
        }
    }
    public function refuser(DemandeConge $demande)
    {
        $statutRefuser = StatutDemande::where('statut',  'refuser')->first();

        $demande->update(['statut_demande_id' => $statutRefuser->id]);
        try {
            Mail::to($demande->employe->user->email)->send(new ResponseDemandeMail($demande->employe->user, $demande, 'refusée'));
            Notification::send($demande->employe->user, new DemandeCongeNotification($demande, 'refusée'));
            return redirect()->route('dashboard')->with('message', 'Demande refusée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Une erreur est survenue lors de l\'envoi du mail : ' . $e->getMessage());
        }
    }
}
