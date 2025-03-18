# test_creation_service.py
import time
from selenium import webdriver
from selenium.webdriver.chrome.service import Service as ChromeService
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import random
from config import baseurl, email, password

def login(driver):
    """Effectue la connexion à l'application"""
    try:
        # Accéder à la page
        driver.get(baseurl)
        print("Accès à la page réussi")
        
        # Vérifier si un lien de connexion est présent et cliquer dessus
        try:
            login_link = driver.find_element(By.LINK_TEXT, "Se connecter")
            login_link.click()
            print("Navigation vers la page de connexion")
        except:
            print("Déjà sur la page de connexion ou lien non trouvé")
        
        # Remplit les informations de connexion
        champ_email = driver.find_element(By.NAME, "email")
        champ_email.clear()
        champ_email.send_keys(email)
        
        champ_password = driver.find_element(By.NAME, "password")
        champ_password.clear()
        champ_password.send_keys(password)
        
        # Cocher la case "Se souvenir de moi" si nécessaire
        try:
            remember_me = driver.find_element(By.ID, "remember-me")
            if not remember_me.is_selected():
                remember_me.click()
        except:
            print("Case 'Se souvenir de moi' non trouvée ou déjà cochée")
        
        # Cliquer sur le bouton de connexion
        bouton = driver.find_element(By.CSS_SELECTOR, "button.btn-primary")
        bouton.click()
        print("Bouton de connexion cliqué")
        
        # Attendre que la page du dashboard se charge
        WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.TAG_NAME, "body"))
        )
        time.sleep(2)
        page_source = driver.page_source
        
        # Vérifier si la connexion a réussi
        if "Bienvenue" in page_source or "Dashboard" in page_source or "Tableau de bord" in page_source:
            print("Connexion réussie au dashboard")
            return True
        else:
            print("Échec de connexion au dashboard")
            return False
    except Exception as e:
        print(f"Erreur lors de la connexion: {str(e)}")
        return False

def creer_service(driver):
    """Crée un nouveau service après la connexion"""
    try:
        # Générer un nom unique pour le service
        nom_service = f"Service Test {random.randint(1000, 9999)}"
        
        # Naviguer vers la page de gestion des services
        try:
            # Rechercher un menu ou sous-menu services
            menu_services = WebDriverWait(driver, 10).until(
                EC.element_to_be_clickable((By.XPATH, "//a[contains(text(), 'Services') or contains(@href, 'service')]"))
            )
            menu_services.click()
            print("Navigation vers la page des services")
        except Exception as e:
            print(f"Navigation vers services manuelle: {str(e)}")
            # Si le menu n'est pas trouvé, essayer d'accéder directement à l'URL
            driver.get(f"{baseurl}/services")
            print("Accès direct à la page des services")
        
        time.sleep(2)
        
        # Cliquer sur le bouton qui ouvre l'offcanvas (panneau latéral)
        try:
            # Chercher un bouton qui déclenche l'affichage du panneau
            bouton_ajouter = WebDriverWait(driver, 10).until(
                EC.element_to_be_clickable((By.XPATH, 
                    "//button[contains(@data-bs-target, '#offcanvasAddUser') or contains(@data-bs-toggle, 'offcanvas')]"))
            )
            bouton_ajouter.click()
            print("Bouton d'ouverture du panneau d'ajout cliqué")
        except Exception as e:
            print(f"Bouton d'ouverture non trouvé, tentative alternative: {str(e)}")
            # Essayer d'autres sélecteurs possibles
            try:
                bouton_ajouter = WebDriverWait(driver, 10).until(
                    EC.element_to_be_clickable((By.XPATH, 
                        "//button[contains(text(), 'Ajouter')] | //a[contains(text(), 'Ajouter')]"))
                )
                bouton_ajouter.click()
                print("Bouton alternatif d'ajout cliqué")
            except Exception as e2:
                print(f"Aucun bouton d'ajout trouvé: {str(e2)}")
                return False, None
        
        # Attendre que l'offcanvas s'affiche
        WebDriverWait(driver, 10).until(
            EC.visibility_of_element_located((By.ID, "offcanvasAddUser"))
        )
        print("Panneau latéral affiché")
        
        # Attendre que le formulaire soit visible et interactif
        WebDriverWait(driver, 10).until(
            EC.visibility_of_element_located((By.ID, "addNewUserForm"))
        )
        
        # Remplir le formulaire
        champ_nom = driver.find_element(By.ID, "nom")
        champ_nom.clear()
        champ_nom.send_keys(nom_service)
        print(f"Nom du service saisi: {nom_service}")
        
        # Remplir la description
        champ_description = driver.find_element(By.ID, "description")
        champ_description.clear()
        champ_description.send_keys(f"Description pour {nom_service}")
        print("Description saisie")
        
        # Soumettre le formulaire
        bouton_soumettre = driver.find_element(By.XPATH, 
            "//button[@type='submit' and contains(@class, 'data-submit')]")
        bouton_soumettre.click()
        print("Formulaire soumis")
        
        # Attendre que le panneau se ferme
        time.sleep(3)
        
        # Vérifier si la création a réussi (le panneau doit être fermé)
        try:
            WebDriverWait(driver, 5).until(
                EC.invisibility_of_element_located((By.ID, "offcanvasAddUser"))
            )
            print("Panneau fermé, opération probablement réussie")
            
            # Recharger la page pour vérifier
            driver.refresh()
            time.sleep(2)
            
            # Vérifier si le nouveau service apparaît dans la liste
            page_source = driver.page_source
            if nom_service in page_source:
                print("Création du service confirmée")
                return True, nom_service
            else:
                print("Service créé mais non visible dans la liste")
                return False, nom_service
                
        except:
            print("Le panneau est resté ouvert, l'opération a probablement échoué")
            return False, nom_service
            
    except Exception as e:
        print(f"Erreur lors de la création du service: {str(e)}")
        return False, None

def test_creation_service():
    """Teste la fonctionnalité de création d'un service après connexion"""
    # Initialiser le driver
    service = ChromeService(ChromeDriverManager().install())
    driver = webdriver.Chrome(service=service)
    driver.maximize_window()
    
    try:
        # Se connecter d'abord
        if login(driver):
            print("Connexion réussie, tentative de création d'un service...")
            
            # Créer un service
            succes, nom_service = creer_service(driver)
            
            # Vérifier et afficher le résultat
            if succes:
                print(f"Test de création du service '{nom_service}' réussi !")
            else:
                print("Test de création du service échoué.")
        else:
            print("Test annulé : impossible de se connecter.")
    finally:
        # Prendre une capture d'écran finale
        try:
            driver.save_screenshot("resultat_creation_service.png")
            print("Capture d'écran enregistrée dans 'resultat_creation_service.png'")
        except:
            print("Impossible de prendre une capture d'écran")
        
        # Fermer le navigateur
        driver.quit()

if __name__ == "__main__":
    test_creation_service()