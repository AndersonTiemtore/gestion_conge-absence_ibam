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

def creer_fonction(driver):
    """Crée une nouvelle fonction après la connexion"""
    try:
        # Générer un nom unique pour la fonction
        nom_fonction = f"Fonction Test {random.randint(1000, 9999)}"
        description = f"Description de la fonction test créée le {time.strftime('%d/%m/%Y à %H:%M:%S')}"
        
        # Naviguer vers la page de gestion des fonctions
        try:
            # Rechercher un menu ou sous-menu fonctions
            menu_fonctions = WebDriverWait(driver, 10).until(
                EC.element_to_be_clickable((By.XPATH, "//a[contains(text(), 'Fonctions') or contains(@href, 'fonction')]"))
            )
            menu_fonctions.click()
            print("Navigation vers la page des fonctions")
        except Exception as e:
            print(f"Navigation vers fonctions manuelle: {str(e)}")
            # Si le menu n'est pas trouvé, essayer d'accéder directement à l'URL
            driver.get(f"{baseurl}/fonctions")
            print("Accès direct à la page des fonctions")
        
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
        champ_nom.send_keys(nom_fonction)
        
        # Remplir la description si le champ existe
        try:
            champ_description = driver.find_element(By.ID, "description")
            champ_description.clear()
            champ_description.send_keys(description)
            print("Description remplie")
        except:
            print("Champ description non trouvé")
        
        # Gérer les autres champs potentiels du formulaire
        # (code, statut, etc. selon la structure de votre application)
        try:
            # Sélectionner un statut si un select existe
            select_statut = driver.find_element(By.ID, "statut")
            from selenium.webdriver.support.ui import Select
            select = Select(select_statut)
            select.select_by_index(1)  # Sélectionne la première option après l'option par défaut
            print("Statut sélectionné")
        except:
            print("Sélection de statut non disponible")
        
        # Soumettre le formulaire
        try:
            bouton_soumettre = driver.find_element(By.XPATH, "//button[@type='submit']")
            bouton_soumettre.click()
            print("Formulaire soumis")
        except Exception as e:
            print(f"Erreur lors de la soumission du formulaire: {str(e)}")
            return False, nom_fonction
        
        # Attendre que la page se recharge ou qu'une confirmation apparaisse
        time.sleep(3)
        
        # Vérifier si la fonction a été créée avec succès
        page_source = driver.page_source
        if nom_fonction in page_source:
            print(f"Création de la fonction '{nom_fonction}' réussie")
            return True, nom_fonction
        else:
            print(f"Impossible de vérifier la création de la fonction '{nom_fonction}'")
            return False, nom_fonction
            
    except Exception as e:
        print(f"Erreur lors de la création de la fonction: {str(e)}")
        return False, None

def main():
    """Fonction principale qui exécute le test complet"""
    print("Démarrage du test de création de fonction")
    
    # Initialiser le driver Chrome
    service = ChromeService(ChromeDriverManager().install())
    options = webdriver.ChromeOptions()
    # options.add_argument("--headless")  # Décommentez pour exécuter en mode headless
    driver = webdriver.Chrome(service=service, options=options)
    driver.maximize_window()
    
    try:
        # Étape 1: Connexion
        if login(driver):
            print("Connexion réussie, passage à la création de fonction")
            
            # Étape 2: Création de fonction
            success, nom_fonction = creer_fonction(driver)
            
            if success:
                print(f"Test réussi: Fonction '{nom_fonction}' créée avec succès")
            else:
                print(f"Test échoué: Impossible de créer la fonction")
        else:
            print("Test échoué: Échec de la connexion")
    
    except Exception as e:
        print(f"Erreur durant l'exécution du test: {str(e)}")
    
    finally:
        # Fermer le navigateur
        time.sleep(2)  # Attendre un peu pour voir le résultat
        driver.quit()
        print("Test terminé, navigateur fermé")

if __name__ == "__main__":
    main()