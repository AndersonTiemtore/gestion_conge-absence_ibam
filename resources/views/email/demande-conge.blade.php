<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Nouvelle Demande de Congé - IBAM</title>
    </head>

    <body
        style="font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 20px; background-color: #f0f2f5; text-align: center;">
        <table align="center" width="600"
            style="margin: 20px auto; background-color: #ffffff; border-radius: 16px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1); border: none;">
            <!-- En-tête avec effet dégradé -->
            <tr>
                <td
                    style="padding: 35px 20px; text-align: center; background: linear-gradient(135deg, #003366 0%, #004d99 100%); border-radius: 16px 16px 0 0;">
                    <h1 style="font-size: 26px; color: #ffffff; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                        🌟 Nouvelle Demande de Congé 🌟
                    </h1>
                </td>
            </tr>

            <!-- Introduction -->
            <tr>
                <td style="padding: 30px 30px 20px; text-align: left;">
                    <p style="font-size: 17px; color: #333; margin: 0; line-height: 1.6;">
                        Cher(e) {{ $demande->employe->user->role == 'responsable' ? 'grh' : 'Responsable' }} , 👋
                    </p>
                    <p style="font-size: 16px; color: #555; margin-top: 15px; line-height: 1.6;">
                        Une nouvelle demande de congé nécessite votre attention. Voici les détails :
                    </p>
                </td>
            </tr>

            <!-- Informations du demandeur -->
            <tr>
                <td style="padding: 0 30px;">
                    <div
                        style="background: linear-gradient(to right, #f8f9fa, #ffffff); border-left: 4px solid #003366; border-radius: 12px; padding: 20px; margin: 10px 0;">
                        <table width="100%" style="border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0;">
                                    <span style="font-size: 15px; color: #666;">Demandeur :</span>
                                    <strong style="font-size: 17px; color: #003366; margin-left: 10px;">
                                        {{ $user->nom . ' ' . $user->prenom }}
                                    </strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>

            <!-- Détails de la demande -->
            <tr>
                <td style="padding: 20px 30px;">
                    <div style="background-color: #f8f9fa; border-radius: 12px; padding: 20px; margin: 10px 0;">
                        <table width="100%" style="border-collapse: collapse;">
                            <tr>
                                <td colspan="2" style="padding-bottom: 15px;">
                                    <h3 style="margin: 0; color: #003366; font-size: 18px;">📅 Période demandée</h3>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0;">
                                    <span style="color: #666;">Du :</span>
                                    <strong
                                        style="color: #003366; margin-left: 10px;">{{ $demande->date_debut }}</strong>
                                </td>
                                <td style="padding: 8px 0;">
                                    <span style="color: #666;">Au :</span>
                                    <strong style="color: #003366; margin-left: 10px;">{{ $demande->date_fin }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 15px 0 8px;">
                                    <span style="color: #666;">Type de congé :</span>
                                    <strong
                                        style="color: #003366; margin-left: 10px;">{{ $demande->type_conge }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 8px 0;">
                                    <span style="color: #666;">Motif :</span>
                                    <strong style="color: #003366; margin-left: 10px;">{{ $demande->motif }}</strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>

            <!-- Boutons d'action -->
            <tr>
                <td style="padding: 20px 30px 30px;">
                    <p style="font-size: 16px; color: #555; margin-bottom: 20px; text-align: center;">
                        Veuillez traiter cette demande en cliquant sur l'un des boutons ci-dessous :
                    </p>
                    <div style="text-align: center;">
                        <a href="{{ route('demande.accepter', $demande) }}"
                            style="display: inline-block; margin: 10px 10px; padding: 12px 30px; background: linear-gradient(to right, #28a745, #34c759); color: #fff; text-decoration: none; border-radius: 8px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2);">
                            ✅ Accepter
                        </a>
                        <a href="{{ route('demande.refuser', $demande) }}"
                            style="display: inline-block; margin: 10px 10px; padding: 12px 30px; background: linear-gradient(to right, #dc3545, #ff4d4d); color: #fff; text-decoration: none; border-radius: 8px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(220, 53, 69, 0.2);">
                            ❌ Refuser
                        </a>
                    </div>
                </td>
            </tr>

            <!-- Pied de page -->
            <tr>
                <td
                    style="padding: 25px; text-align: center; background: linear-gradient(to bottom, #f8f9fa, #ffffff); border-radius: 0 0 16px 16px;">
                    <div style="margin-bottom: 15px;">
                        <p style="font-size: 16px; color: #003366; font-weight: bold; margin: 0;">
                            Institut Burkinabè des Arts et Métiers
                        </p>
                    </div>
                    <p style="font-size: 14px; color: #666; margin: 10px 0 0 0; font-style: italic;">
                        Ceci est un message automatique • Merci de ne pas y répondre
                    </p>
                </td>
            </tr>
        </table>
    </body>

</html>
