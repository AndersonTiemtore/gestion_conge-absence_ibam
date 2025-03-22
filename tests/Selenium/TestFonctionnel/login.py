import time
import matplotlib.pyplot as plt
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from config import baseurl, email, password

def login(driver):
    """Effectue la connexion à l'application"""
    try:
        # Accéder à la page
        driver.get(baseurl)
        print("Accès à la page réussi")
        
        # Vérifier si un lien de connexion est présent et cliquer dessus
        try:
            # Utiliser présence au lieu de clickable pour éviter l'affichage des erreurs
            if driver.find_elements(By.LINK_TEXT, "Se connecter"):
                login_link = driver.find_element(By.LINK_TEXT, "Se connecter")
                driver.execute_script("arguments[0].click();", login_link)
                print("Navigation vers la page de connexion")
            else:
                print("Déjà sur la page de connexion")
        except:
            # Éviter d'imprimer l'erreur détaillée
            print("Déjà sur la page de connexion")
        
        # Attendre brièvement que la page se charge
        time.sleep(1)
        
        # Remplit les informations de connexion s'ils existent
        try:
            if driver.find_elements(By.NAME, "email"):
                champ_email = driver.find_element(By.NAME, "email")
                champ_email.clear()
                champ_email.send_keys(email)
                
                champ_password = driver.find_element(By.NAME, "password")
                champ_password.clear()
                champ_password.send_keys(password)
                print("Informations de connexion saisies")
            else:
                print("Champs de connexion non trouvés")
                return False
        except:
            print("Erreur lors de la saisie des informations")
            return False
        
        # Cocher la case "Se souvenir de moi" si nécessaire
        try:
            if driver.find_elements(By.ID, "remember-me"):
                remember_me = driver.find_element(By.ID, "remember-me")
                if not remember_me.is_selected():
                    driver.execute_script("arguments[0].click();", remember_me)
        except:
            print("Case 'Se souvenir de moi' non trouvée ou déjà cochée")
        
        # Cliquer sur le bouton de connexion
        try:
            if driver.find_elements(By.CSS_SELECTOR, "button.btn-primary"):
                bouton = driver.find_element(By.CSS_SELECTOR, "button.btn-primary")
                driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", bouton)
                time.sleep(1)
                driver.execute_script("arguments[0].click();", bouton)
                print("Bouton de connexion cliqué")
            else:
                print("Bouton de connexion non trouvé")
                return False
        except:
            print("Erreur lors du clic sur le bouton de connexion")
            return False
        
        # Attendre que la page se charge
        time.sleep(3)
        page_source = driver.page_source
        
        # Vérifier si la connexion a réussi
        if "Bienvenue" in page_source or "Dashboard" in page_source or "Tableau de bord" in page_source:
            print("Connexion réussie au dashboard")
            return True
        else:
            print("Échec de connexion au dashboard")
            return False
    except:
        print("Erreur lors de la connexion")
        return False

if __name__ == "__main__":
    from selenium import webdriver
    from webdriver_manager.chrome import ChromeDriverManager
    from selenium.webdriver.chrome.service import Service
    from selenium.webdriver.chrome.options import Options
    
    # Configuration du driver Chrome
    chrome_options = Options()
    chrome_options.add_argument("--no-sandbox")
    chrome_options.add_argument("--disable-dev-shm-usage")
    chrome_options.add_argument("--window-size=1920,1080")
    # Désactiver les messages de console du navigateur
    chrome_options.add_argument("--log-level=3")
    
    # Création du driver avec WebDriver Manager
    print("Initialisation du navigateur...")
    driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=chrome_options)
    
    try:
        result = login(driver)
        if result:
            print("Test de connexion réussi")
        else:
            print("Test de connexion échoué")
    finally:
        print("Fermeture du navigateur...")
        driver.quit()