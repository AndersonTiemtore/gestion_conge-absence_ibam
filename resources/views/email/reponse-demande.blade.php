<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse à votre demande de congé - IBAM</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 20px; background-color: #f0f2f5; text-align: center;">
    <table align="center" width="600" style="margin: 20px auto; background-color: #ffffff; border-radius: 16px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1); border: none;">
        <!-- En-tête avec effet dégradé -->
        <tr>
            <td style="padding: 40px 20px; text-align: center; background: linear-gradient(135deg, #003366 0%, #004d99 100%); border-radius: 16px 16px 0 0;">
                <h1 style="font-size: 28px; color: #ffffff; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">✨ Notification IBAM ✨</h1>
            </td>
        </tr>

        <!-- Message personnalisé -->
        <tr>
            <td style="padding: 40px 30px; text-align: left;">
                <p style="font-size: 18px; color: #333; margin-bottom: 25px; line-height: 1.6;">
                    Cher(e) {{ $user->nom . ' ' . $user->prenom }}, 👋
                </p>

                <!-- Boîte d'information stylée -->
                <div style="background: linear-gradient(to right, #f8f9fa, #ffffff); border-left: 4px solid #003366; border-radius: 12px; padding: 25px; margin: 20px 0; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                    <p style="font-size: 17px; color: #333; margin: 0 0 15px 0;">
                        Nous avons le plaisir de vous informer que votre demande de congé pour la période :
                    </p>
                    <p style="font-size: 20px; font-weight: bold; color: #003366; margin: 15px 0; text-align: center;">
                        Du {{ $demande->date_debut }} au {{ $demande->date_fin }}
                    </p>
                </div>

                <!-- Message de statut -->
                @if ($statut === 'acceptée')
                <div style="background-color: #f0fff4; padding: 20px; border-radius: 12px; margin: 20px 0; border-left: 4px solid #28a745;">
                    <p style="font-size: 18px; color: #28a745; margin: 0; font-weight: bold;">
                        🎉 Félicitations ! Votre demande a été acceptée !
                    </p>
                    <p style="font-size: 16px; color: #2f855a; margin: 10px 0 0 0;">
                        Nous vous souhaitons un excellent moment de repos. Profitez-en pleinement pour vous ressourcer !
                    </p>
                </div>
                @else
                <div style="background-color: #fff5f5; padding: 20px; border-radius: 12px; margin: 20px 0; border-left: 4px solid #dc3545;">
                    <p style="font-size: 18px; color: #dc3545; margin: 0; font-weight: bold;">
                        Nous sommes désolés 😔
                    </p>
                    <p style="font-size: 16px; color: #c53030; margin: 10px 0 0 0;">
                        Malheureusement, votre demande n'a pas pu être acceptée pour le moment. N'hésitez pas à échanger avec votre responsable pour en discuter.
                    </p>
                </div>
                @endif

                <!-- Message de conclusion -->
                <p style="font-size: 16px; color: #666; margin-top: 30px; line-height: 1.6;">
                    Notre équipe RH reste à votre disposition pour toute information complémentaire.
                </p>
            </td>
        </tr>

        <!-- Pied de page élégant -->
        <tr>
            <td style="padding: 30px 20px; text-align: center; background: linear-gradient(to bottom, #f8f9fa, #ffffff); border-radius: 0 0 16px 16px;">
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
