import sys
import os
import time
import csv
import concurrent.futures
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.action_chains import ActionChains
from selenium.common.exceptions import ElementClickInterceptedException, TimeoutException

# Configuration
baseurl = "http://localhost:8000"
users = [
    {"email": "andersontiemtore87@gmail.com", "password": "password"},
    {"email": "guy35115@gmail.com", "password": "password"},
    {"email": "kourastephane9@gmail.com", "password": "password"},
    {"email": "zakariakhalil@gmail.com", "password": "password"},
    {"email": "benjaminbaptiste@gmail.com", "password": "password"},
    {"email": "karimkhalil@gmail.com", "password": "password"},
    {"email": "richardrichard@gmail.com", "password": "password"},
    {"email": "victorvictor@gmail.com", "password": "password"},
    {"email": "zackzack@gmail.com", "password": "password"},
    {"email": "nicolasnicolas@gmail.com", "password": "password"},
    {"email": "mathieumathieu@gmail.com", "password": "password"},
    {"email": "olivierolivier@gmail.com", "password": "password"},
    {"email": "julianjulian@gmail.com", "password": "password"},
    {"email": "paulpaul@gmail.com", "password": "password"},
    {"email": "gabrielgabriel@gmail.com", "password": "password"},
    {"email": "louislouis@gmail.com", "password": "password"},
    {"email": "jeanjean@gmail.com", "password": "password"},
    {"email": "ibrahimibrahim@gmail.com", "password": "password"},
    {"email": "quentinquentin@gmail.com", "password": "password"},
    {"email": "paulpaul@gmail.com", "password": "password"},

]

def login(driver, email, password):
    """Effectue la connexion à l'application e-CongeIBAM"""
    try:
        # Accéder à la page
        driver.get(baseurl)
        print(f"Accès à la page réussi pour l'utilisateur {email}")
        
        # Attendre que la page se charge complètement
        WebDriverWait(driver, 5).until(
            EC.presence_of_element_located((By.ID, "formAuthentication"))
        )
        
        # Récupérer le token CSRF
        try:
            csrf_token = driver.find_element(By.CSS_SELECTOR, "input[name='_token']").get_attribute("value")
            print(f"Token CSRF récupéré: {csrf_token[:10]}...")
        except Exception as e:
            print(f"Impossible de récupérer le token CSRF: {e}")
        
        # Attendre que les champs soient cliquables
        WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable((By.ID, "email"))
        )
        
        # Remplit les informations de connexion
        champ_email = driver.find_element(By.ID, "email")
        champ_email.clear()
        champ_email.send_keys(email)
        
        champ_password = driver.find_element(By.ID, "password")
        champ_password.clear()
        champ_password.send_keys(password)
        
        # Cocher la case "Se souvenir de moi" si elle est présente
        try:
            remember_me = driver.find_element(By.ID, "remember-me")
            if not remember_me.is_selected():
                # Utiliser JavaScript pour cliquer sur l'élément
                driver.execute_script("arguments[0].click();", remember_me)
        except:
            print("Case 'Se souvenir de moi' non trouvée ou déjà cochée")
        
        # Attendre que le bouton soit cliquable
        bouton = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable((By.CSS_SELECTOR, "button.btn-primary"))
        )
        
        # Faire défiler jusqu'au bouton pour s'assurer qu'il est visible
        driver.execute_script("arguments[0].scrollIntoView(true);", bouton)
        time.sleep(1)  # Attente courte pour s'assurer que le défilement est terminé
        
        # Utiliser JavaScript pour cliquer sur le bouton
        driver.execute_script("arguments[0].click();", bouton)
        print(f"Bouton de connexion cliqué pour l'utilisateur {email}")
        
        # Attendre que la page se charge
        WebDriverWait(driver, 15).until(
            EC.presence_of_element_located((By.TAG_NAME, "body"))
        )
        time.sleep(2)
        page_source = driver.page_source
        
        # Vérifier si la connexion a réussi
        if "Dashboard" in page_source or "Tableau de bord" in page_source or "Bienvenue" in page_source:
            print(f"Connexion réussie au dashboard pour l'utilisateur {email}")
            return True
        else:
            # Vérifier s'il y a des messages d'erreur
            try:
                error_messages = driver.find_elements(By.CSS_SELECTOR, ".invalid-feedback")
                if error_messages:
                    for error in error_messages:
                        print(f"Message d'erreur trouvé: {error.text}")
            except:
                pass
            print(f"Échec de connexion au dashboard pour l'utilisateur {email}")
            return False
    except TimeoutException as te:
        print(f"Timeout lors de la connexion pour {email}: {str(te)}")
        return False
    except ElementClickInterceptedException as ecie:
        print(f"Élément cliquable intercepté pour {email}: {str(ecie)}")
        return False
    except Exception as e:
        print(f"Erreur lors de la connexion pour {email}: {str(e)}")
        return False

def executer_test_connexion(user_id, email, password):
    """Exécute un test de connexion pour un utilisateur simulé"""
    try:
        # Configuration du navigateur avec une taille d'écran définie
        chrome_options = Options()
        chrome_options.add_argument("--headless")
        chrome_options.add_argument("--disable-gpu")
        chrome_options.add_argument("--no-sandbox")
        chrome_options.add_argument("--disable-dev-shm-usage")
        chrome_options.add_argument("--window-size=1920,1080")  # Définir une taille d'écran plus grande
        
        # Utilisation de webdriver-manager pour gérer l'installation et la configuration du driver
        service = Service(ChromeDriverManager().install())
        driver = webdriver.Chrome(service=service, options=chrome_options)
        driver.implicitly_wait(10)
        
        # Exécuter la fonction de connexion
        start_time = time.time()
        resultat = login(driver, email, password)
        end_time = time.time()
        
        # Prendre une capture d'écran en cas d'échec
        if not resultat:
            screenshot_path = f"error_screenshot_{user_id}_{int(time.time())}.png"
            driver.save_screenshot(screenshot_path)
            print(f"Capture d'écran enregistrée: {screenshot_path}")
            
        driver.quit()
        
        temps_execution = end_time - start_time
        return {
            "user_id": user_id,
            "email": email,
            "status": "success" if resultat else "failure",
            "error": "",
            "execution_time": temps_execution
        }
    except Exception as e:
        return {
            "user_id": user_id,
            "email": email,
            "status": "failure",
            "error": str(e),
            "execution_time": 0
        }

def test_de_charge(users_list, max_workers=3):  # Réduit à 3 pour éviter les surcharges
    """Exécute un test de charge avec les utilisateurs spécifiés"""
    resultats = []
    nombre_utilisateurs = len(users_list)
    
    print(f"Démarrage du test de charge avec {nombre_utilisateurs} utilisateurs...")
    start_time = time.time()
    
    # Utilisation de ThreadPoolExecutor pour exécuter les tests en parallèle
    with concurrent.futures.ThreadPoolExecutor(max_workers=max_workers) as executor:
        futures = [executor.submit(executer_test_connexion, i, user["email"], user["password"]) 
                  for i, user in enumerate(users_list)]
        
        for future in concurrent.futures.as_completed(futures):
            resultat = future.result()
            resultats.append(resultat)
            print(f"Utilisateur {resultat['user_id']} ({resultat['email']}) - "
                  f"{resultat['status']} - Temps: {resultat.get('execution_time', 'N/A'):.2f}s")
    
    end_time = time.time()
    temps_total = end_time - start_time
    
    # Analyse des résultats
    succes = sum(1 for r in resultats if r['status'] == "success")
    echecs = nombre_utilisateurs - succes
    
    temps_moyen = sum(r.get('execution_time', 0) for r in resultats if r['status'] == "success") / max(succes, 1)
    
    print("\nRésultats du test de charge:")
    print(f"Temps total d'exécution: {temps_total:.2f} secondes")
    print(f"Nombre total d'utilisateurs: {nombre_utilisateurs}")
    print(f"Connexions réussies: {succes} ({(succes/nombre_utilisateurs)*100:.2f}%)")
    print(f"Connexions échouées: {echecs} ({(echecs/nombre_utilisateurs)*100:.2f}%)")
    print(f"Temps moyen par connexion réussie: {temps_moyen:.2f} secondes")
    
    return {
        "total_time": temps_total,
        "total_users": nombre_utilisateurs,
        "successful_connections": succes,
        "failed_connections": echecs,
        "average_time": temps_moyen,
        "detailed_results": resultats
    }

def save_results_to_csv(results, filename='test_results.csv'):
    """Enregistre les résultats dans un fichier CSV"""
    with open(filename, 'w', newline='', encoding='utf-8') as output_file:
        fieldnames = ['user_id', 'email', 'status', 'error', 'execution_time']
        writer = csv.DictWriter(output_file, fieldnames=fieldnames)
        writer.writeheader()
        for result in results:
            writer.writerow(result)
    print(f"\nRésultats enregistrés avec succès dans {filename}")

def generer_rapport(resultats, nom_fichier="rapport_test_charge.html"):
    """Génère un rapport HTML à partir des résultats du test de charge"""
    html = """
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Rapport de Test de Charge - e-CongeIBAM</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { color: #6B46C1; }
            .summary { 
                background-color: #f5f5f5; 
                padding: 15px; 
                border-radius: 16px; 
                margin-bottom: 20px; 
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { 
                background: linear-gradient(135deg, #6B46C1 0%, #3B82F6 100%);
                color: white; 
            }
            tr:nth-child(even) { background-color: #f2f2f2; }
            .success { color: green; }
            .failure { color: red; }
            .header {
                background: linear-gradient(135deg, #6B46C1 0%, #3B82F6 100%);
                color: white;
                padding: 20px;
                border-radius: 16px;
                margin-bottom: 20px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Rapport de Test de Charge - e-CongeIBAM</h1>
            <p>Test de performance de l'interface de connexion</p>
        </div>
        
        <div class="summary">
            <h2>Résumé</h2>
            <p><strong>Date du test:</strong> """ + time.strftime("%Y-%m-%d %H:%M:%S") + """</p>
            <p><strong>URL testée:</strong> """ + baseurl + """</p>
            <p><strong>Nombre total d'utilisateurs:</strong> """ + str(resultats["total_users"]) + """</p>
            <p><strong>Temps total d'exécution:</strong> """ + f"{resultats['total_time']:.2f}" + """ secondes</p>
            <p><strong>Connexions réussies:</strong> """ + str(resultats["successful_connections"]) + """ (""" + f"{(resultats['successful_connections']/resultats['total_users'])*100:.2f}" + """%)</p>
            <p><strong>Connexions échouées:</strong> """ + str(resultats["failed_connections"]) + """ (""" + f"{(resultats['failed_connections']/resultats['total_users'])*100:.2f}" + """%)</p>
            <p><strong>Temps moyen par connexion réussie:</strong> """ + f"{resultats['average_time']:.2f}" + """ secondes</p>
        </div>
        
        <h2>Détails des tests</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Statut</th>
                <th>Temps d'exécution (s)</th>
                <th>Détails d'erreur</th>
            </tr>
    """
    
    for detail in resultats["detailed_results"]:
        status_class = "success" if detail["status"] == "success" else "failure"
        
        html += f"""
            <tr>
                <td>{detail['user_id']}</td>
                <td>{detail['email']}</td>
                <td class="{status_class}">{detail['status']}</td>
                <td>{detail.get('execution_time', 'N/A'):.2f}</td>
                <td>{detail.get('error', '')}</td>
            </tr>
        """
    
    html += """
        </table>
        <div class="summary">
            <h2>Analyse et recommandations</h2>
            <ul>
                <li>Si les tests échouent, vérifiez que le serveur est correctement configuré et en cours d'exécution.</li>
                <li>Vérifiez que les utilisateurs de test existent dans la base de données avec les identifiants corrects.</li>
                <li>Assurez-vous que l'interface de connexion fonctionne correctement en mode manuel.</li>
                <li>Si vous obtenez des erreurs "element click intercepted", cela peut être dû à des éléments qui se chevauchent ou à des animations sur la page.</li>
            </ul>
        </div>
    </body>
    </html>
    """
    
    with open(nom_fichier, "w", encoding='utf-8') as f:
        f.write(html)
    
    print(f"\nRapport HTML généré avec succès: {nom_fichier}")

def tester_unique_utilisateur(email, password):
    """Teste la connexion pour un utilisateur unique en mode non-headless"""
    print(f"\nTest manuel pour un seul utilisateur: {email}")
    
    try:
        # Configuration du navigateur sans mode headless
        chrome_options = Options()
        chrome_options.add_argument("--start-maximized")
        
        service = Service(ChromeDriverManager().install())
        driver = webdriver.Chrome(service=service, options=chrome_options)
        driver.implicitly_wait(30)
        
        # Exécuter la fonction de connexion
        resultat = login(driver, email, password)
        
        if resultat:
            print(f"Test manuel réussi pour {email}")
        else:
            print(f"Test manuel échoué pour {email}")
            
        # Attendre 5 secondes pour observer le résultat avant de fermer
        time.sleep(5)
        driver.quit()
        
    except Exception as e:
        print(f"Erreur lors du test manuel: {str(e)}")

if __name__ == "__main__":
    # Paramètres configurables
    max_workers = 3  # Réduit pour éviter les surcharges
    
    try:
        # Vérifier si les packages nécessaires sont installés
        try:
            import webdriver_manager
        except ImportError:
            print("Le package webdriver-manager n'est pas installé.")
            print("Installation en cours avec pip...")
            os.system("pip install webdriver-manager")
            print("webdriver-manager installé avec succès.")
        
        # Mode de test
        mode = input("Choisissez le mode de test (1: Test complet, 2: Test unique): ")
        
        if mode == "2":
            # Test sur un seul utilisateur en mode visible
            email = input("Email de l'utilisateur à tester: ")
            password = input("Mot de passe: ")
            tester_unique_utilisateur(email, password)
        else:
            # Exécution du test de charge complet
            resultats = test_de_charge(users, max_workers)
            
            # Enregistrer les résultats dans un fichier CSV
            save_results_to_csv(resultats["detailed_results"])
            
            # Génération du rapport HTML
            generer_rapport(resultats)
            
            print("Tous les tests sont terminés et les résultats ont été enregistrés.")
    except Exception as e:
        print(f"Une erreur est survenue lors de l'exécution des tests: {str(e)}")
        sys.exit(1)