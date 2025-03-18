# test_login.py
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from login import login
from config import baseurl, email, password

def test_connexion():
    """Teste la fonctionnalité de connexion"""
    # Initialiser le driver
    service = Service(ChromeDriverManager().install())
    driver = webdriver.Chrome(service=service)
    
    try:
        # Appeler la fonction de connexion
        result = login(driver)
        
        # Vérifier le résultat
        if result:
            print("Test de connexion réussi !")
        else:
            print("Test de connexion échoué.")
    finally:
        # Fermer le navigateur
        driver.quit()

if __name__ == "__main__":
    test_connexion()