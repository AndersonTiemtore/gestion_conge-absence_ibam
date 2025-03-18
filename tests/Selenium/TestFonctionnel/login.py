# login.py
import time
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