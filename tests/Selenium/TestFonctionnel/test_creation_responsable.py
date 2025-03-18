import time
from selenium import webdriver
from selenium.webdriver.chrome.service import Service as ChromeService
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import random
from datetime import datetime, timedelta
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


def creer_responsable(driver):
    """Crée un nouveau responsable après la connexion"""
    try:
        # Naviguer vers la page d'employés
        try:
            menu_employes = WebDriverWait(driver, 10).until(
                EC.element_to_be_clickable((By.XPATH, "//a[contains(text(), 'Employés') or contains(@href, 'employe')]"))
            )
            menu_employes.click()
            print("Navigation vers la page des employés")
        except Exception as e:
            print(f"Navigation vers employés manuelle: {str(e)}")
            # Si le menu n'est pas trouvé, essayer d'accéder directement à l'URL
            driver.get(f"{baseurl}/employes")
            print("Accès direct à la page des employés")
        
        time.sleep(2)
        
        # Cliquer sur le bouton "Ajouter un responsable"
        try:
            bouton_ajouter = WebDriverWait(driver, 10).until(
                EC.element_to_be_clickable((By.XPATH, 
                    "//button[contains(text(), 'Ajouter un responsable')]"))
            )
            bouton_ajouter.click()
            print("Bouton d'ajout de responsable cliqué")
        except Exception as e:
            print(f"Bouton d'ajout responsable non trouvé, tentative alternative: {str(e)}")
            try:
                bouton_ajouter = WebDriverWait(driver, 10).until(
                    EC.element_to_be_clickable((By.CSS_SELECTOR, "[data-bs-target='#offcanvasAddResponsable']"))
                )
                bouton_ajouter.click()
                print("Bouton alternatif d'ajout responsable cliqué")
            except Exception as e2:
                print(f"Aucun bouton d'ajout responsable trouvé: {str(e2)}")
                return False, None
        
        # Attendre que l'offcanvas s'affiche
        WebDriverWait(driver, 10).until(
            EC.visibility_of_element_located((By.ID, "offcanvasAddResponsable"))
        )
        print("Panneau latéral affiché")
        
        # Générer des données aléatoires pour le responsable
        matricule = f"RESP{random.randint(10000, 99999)}"
        nom = f"Test{random.randint(100, 999)}"
        prenom = f"Prenom{random.randint(100, 999)}"
        responsable_email = f"test.{nom.lower()}{random.randint(1, 999)}@example.com"
        adresse = f"Adresse test {random.randint(1, 100)}"
        telephone = f"+226{random.randint(10000000, 99999999)}"
        sexe = random.choice(["masculin", "feminin"])
        
        # Dates
        date_naissance = (datetime.now() - timedelta(days=random.randint(365*20, 365*50))).strftime("%Y-%m-%d")
        date_embauche = (datetime.now() - timedelta(days=random.randint(30, 365*5))).strftime("%Y-%m-%d")
        
        # Remplir le formulaire
        # Matricule
        champ_matricule = driver.find_element(By.ID, "add-user-contact")
        champ_matricule.clear()
        champ_matricule.send_keys(matricule)
        print("Matricule saisi")
        
        # Nom
        champ_nom = driver.find_element(By.ID, "nom")
        champ_nom.clear()
        champ_nom.send_keys(nom)
        print("Nom saisi")
        
        # Prénom
        champ_prenom = driver.find_element(By.ID, "prenom")
        champ_prenom.clear()
        champ_prenom.send_keys(prenom)
        print("Prénom saisi")
        
        # Email
        champ_email = driver.find_element(By.ID, "add-user-email")
        champ_email.clear()
        champ_email.send_keys(responsable_email)
        print("Email saisi")
        
        # Service (select2)
        try:
            # Ouvrir le dropdown du service
            driver.execute_script("""
                document.querySelector("#service-take-responsable").dispatchEvent(new Event('mousedown'));
            """)
            time.sleep(1)
            
            # Sélectionner la première option non vide
            service_options = driver.find_elements(By.CSS_SELECTOR, "#service-take-responsable option:not(:first-child)")
            if service_options:
                service_id = service_options[0].get_attribute('value')
                driver.execute_script(f"""
                    document.querySelector("#service-take-responsable").value = "{service_id}";
                    document.querySelector("#service-take-responsable").dispatchEvent(new Event('change'));
                """)
                print("Service sélectionné")
        except Exception as e:
            print(f"Erreur lors de la sélection du service: {str(e)}")
        
        # Fonction (select2)
        try:
            # Ouvrir le dropdown de la fonction
            driver.execute_script("""
                document.querySelector("#fonction-take-responsable").dispatchEvent(new Event('mousedown'));
            """)
            time.sleep(1)
            
            # Sélectionner la première option non vide
            fonction_options = driver.find_elements(By.CSS_SELECTOR, "#fonction-take-responsable option:not(:first-child)")
            if fonction_options:
                fonction_id = fonction_options[0].get_attribute('value')
                driver.execute_script(f"""
                    document.querySelector("#fonction-take-responsable").value = "{fonction_id}";
                    document.querySelector("#fonction-take-responsable").dispatchEvent(new Event('change'));
                """)
                print("Fonction sélectionnée")
        except Exception as e:
            print(f"Erreur lors de la sélection de la fonction: {str(e)}")
        
        # Adresse
        champ_adresse = driver.find_element(By.ID, "adresse")
        champ_adresse.clear()
        champ_adresse.send_keys(adresse)
        print("Adresse saisie")
        
        # Téléphone
        champ_telephone = driver.find_element(By.ID, "telephone")
        champ_telephone.clear()
        champ_telephone.send_keys(telephone)
        print("Téléphone saisi")
        
        # Sexe (boutons radio)
        if sexe == "masculin":
            driver.find_element(By.ID, "masc").click()
        else:
            driver.find_element(By.ID, "fem").click()
        print(f"Sexe '{sexe}' sélectionné")
        
        # Date de naissance
        champ_date_naissance = driver.find_element(By.ID, "flatpickr-date-debut")
        driver.execute_script(f"document.getElementById('flatpickr-date-debut').value = '{date_naissance}'")
        driver.execute_script("""
            document.getElementById('flatpickr-date-debut').dispatchEvent(new Event('input'));
            document.getElementById('flatpickr-date-debut').dispatchEvent(new Event('change'));
        """)
        print("Date de naissance saisie")
        
        # Date d'embauche
        champ_date_embauche = driver.find_element(By.ID, "flatpickr-date-fin")
        driver.execute_script(f"document.getElementById('flatpickr-date-fin').value = '{date_embauche}'")
        driver.execute_script("""
            document.getElementById('flatpickr-date-fin').dispatchEvent(new Event('input'));
            document.getElementById('flatpickr-date-fin').dispatchEvent(new Event('change'));
        """)
        print("Date d'embauche saisie")
        
        # Prendre une pause avant la soumission
        time.sleep(1)
        
        # Soumettre le formulaire
        try:
            bouton_soumettre = WebDriverWait(driver, 10).until(
                EC.element_to_be_clickable((By.CSS_SELECTOR, "button.btn-primary.data-submit"))
            )
            bouton_soumettre.click()
            print("Formulaire soumis")
        except Exception as e:
            print(f"Erreur lors de la soumission du formulaire: {str(e)}")
            return False, None
        
        # Attendre que la page se recharge ou qu'une confirmation apparaisse
        time.sleep(5)
        
        # Vérifier si le responsable a été créé avec succès (présence d'un message de succès)
        page_source = driver.page_source
        if "Succès" in page_source or nom in page_source or matricule in page_source:
            print(f"Création du responsable '{nom} {prenom}' réussie")
            return True, f"{nom} {prenom}"
        else:
            print(f"Impossible de vérifier la création du responsable '{nom} {prenom}'")
            # Vérifier s'il y a des messages d'erreur
            erreurs = driver.find_elements(By.CSS_SELECTOR, ".invalid-feedback, .text-danger")
            for erreur in erreurs:
                if erreur.is_displayed():
                    print(f"Erreur détectée: {erreur.text}")
            return False, f"{nom} {prenom}"
            
    except Exception as e:
        print(f"Erreur lors de la création du responsable: {str(e)}")
        return False, None


def main():
    """Fonction principale qui exécute le test complet"""
    print("Démarrage du test de création de responsable")
    
    # Initialiser le driver Chrome
    service = ChromeService(ChromeDriverManager().install())
    options = webdriver.ChromeOptions()
    # options.add_argument("--headless")  # Décommentez pour exécuter en mode headless
    driver = webdriver.Chrome(service=service, options=options)
    driver.maximize_window()
    
    try:
        # Étape 1: Connexion
        if login(driver):
            print("Connexion réussie, passage à la création de responsable")
            
            # Étape 2: Création de responsable
            success, nom_responsable = creer_responsable(driver)
            
            if success:
                print(f"Test réussi: Responsable '{nom_responsable}' créé avec succès")
            else:
                print(f"Test échoué: Impossible de créer le responsable")
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