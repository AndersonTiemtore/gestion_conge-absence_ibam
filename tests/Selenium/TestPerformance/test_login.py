import time
import statistics
from datetime import datetime
import csv
import matplotlib.pyplot as plt
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from config import baseurl, email, password

def measure_login_performance(iterations=10):
    """Mesure les performances de connexion sur plusieurs itérations"""
    # Configuration pour stocker les données de performance
    results = []
    timestamps = []
    
    # Options Chrome pour améliorer la performance des tests
    chrome_options = Options()
    chrome_options.add_argument("--headless")  # Mode headless pour test de performance
    chrome_options.add_argument("--disable-gpu")
    chrome_options.add_argument("--no-sandbox")
    chrome_options.add_argument("--disable-dev-shm-usage")
    chrome_options.add_argument("--window-size=1920,1080")  # Définir une taille d'écran standard
    
    print(f"Démarrage du test de performance - {iterations} itérations")
    
    # Lancer les itérations de test
    for i in range(iterations):
        # Initialiser un nouveau driver pour chaque test (isolation)
        try:
            service = Service(ChromeDriverManager().install())
            driver = webdriver.Chrome(service=service, options=chrome_options)
            driver.set_page_load_timeout(30)  # Définir un timeout pour le chargement de page
            
            print(f"Itération {i+1}/{iterations}")
            # Mesurer le temps de connexion
            start_time = time.time()
            timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
            
            # Accéder à l'URL
            driver.get(baseurl)
            
            # Attendre que la page de connexion soit chargée
            wait = WebDriverWait(driver, 10)
            
            # Utiliser une logique plus robuste pour détecter les éléments, basée sur l'analyse du HTML
            try:
                # Attendre que l'élément email soit présent
                email_input = wait.until(EC.presence_of_element_located((By.ID, "email")))
                
                # Mesurer le temps de chargement initial
                page_load_time = time.time() - start_time
                
                # Effectuer la connexion
                password_input = driver.find_element(By.ID, "password")
                email_input.clear()  # S'assurer que le champ est vide
                password_input.clear()
                email_input.send_keys(email)
                password_input.send_keys(password)
                
                # Localiser le bouton de connexion en utilisant les sélecteurs appropriés basés sur le HTML
                try:
                    # D'après le HTML, le bouton a une classe "btn btn-primary" et contient un icône et "Se connecter"
                    login_button = driver.find_element(By.CSS_SELECTOR, "button.btn.btn-primary")
                except:
                    try:
                        login_button = driver.find_element(By.XPATH, "//button[contains(text(), 'Se connecter')]")
                    except:
                        try:
                            # Chercher le bouton dans le formulaire avec l'ID "formAuthentication"
                            login_button = driver.find_element(By.CSS_SELECTOR, "#formAuthentication button[type='submit']")
                        except:
                            try:
                                # Dernier recours - chercher n'importe quel bouton dans le formulaire
                                login_button = driver.find_element(By.CSS_SELECTOR, "form button")
                            except Exception as btn_error:
                                # Prendre une capture d'écran pour le débogage
                                driver.save_screenshot(f"login_failure_{i+1}.png")
                                raise Exception(f"Impossible de trouver le bouton de connexion: {str(btn_error)}")
                
                # Capter le moment précis où le bouton est cliqué
                click_time = time.time()
                login_button.click()
                
                # Attendre que la redirection vers le dashboard soit complète
                # D'après le code HTML, la redirection semble être vers /dashboard
                wait.until(EC.url_contains("/dashboard"))
                
                # Vérifier si l'authentification a réussi en cherchant un élément spécifique au dashboard
                # Par exemple, chercher un élément qui n'apparaît que sur le dashboard
                wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, ".dashboard-content, .content-wrapper")))
                
                # Calculer les métriques de performance
                total_time = time.time() - start_time
                auth_time = time.time() - click_time
                
                # Stocker les résultats
                result = {
                    "iteration": i+1,
                    "timestamp": timestamp,
                    "page_load_time": round(page_load_time, 3),
                    "auth_time": round(auth_time, 3),
                    "total_time": round(total_time, 3)
                }
                
                results.append(result)
                timestamps.append(timestamp)
                
                print(f"  Page: {result['page_load_time']}s, Auth: {result['auth_time']}s, Total: {result['total_time']}s")
                
            except Exception as e:
                print(f"Erreur lors de l'interaction avec la page: {str(e)}")
                driver.save_screenshot(f"error_page_{i+1}.png")
            
            # Pause entre les tests pour éviter de surcharger le serveur
            time.sleep(2)
            
        except Exception as e:
            print(f"Erreur lors de l'itération {i+1}: {str(e)}")
        finally:
            # Assurer la fermeture du navigateur même en cas d'erreur
            try:
                driver.quit()
            except:
                pass
    
    return results, timestamps

def analyze_results(results):
    """Analyse les résultats des tests de performance"""
    if not results:
        print("Aucun résultat à analyser")
        return {
            "page_load": {"min": 0, "max": 0, "avg": 0, "median": 0, "stdev": 0},
            "auth": {"min": 0, "max": 0, "avg": 0, "median": 0, "stdev": 0},
            "total": {"min": 0, "max": 0, "avg": 0, "median": 0, "stdev": 0}
        }
    
    # Extraire les valeurs pour l'analyse
    page_load_times = [r["page_load_time"] for r in results]
    auth_times = [r["auth_time"] for r in results]
    total_times = [r["total_time"] for r in results]
    
    # Identifier les valeurs aberrantes potentielles
    def identify_outliers(data):
        if len(data) < 4:  # Besoin d'un minimum de données pour identifier les aberrations
            return data
            
        q1 = statistics.quantiles(sorted(data), n=4)[0]
        q3 = statistics.quantiles(sorted(data), n=4)[2]
        iqr = q3 - q1
        lower_bound = q1 - (1.5 * iqr)
        upper_bound = q3 + (1.5 * iqr)
        
        return [x for x in data if lower_bound <= x <= upper_bound]
    
    # Filtrer les valeurs aberrantes pour une analyse plus précise
    filtered_page_load = identify_outliers(page_load_times)
    filtered_auth = identify_outliers(auth_times)
    filtered_total = identify_outliers(total_times)
    
    # Calcul des statistiques
    stats = {
        "page_load": {
            "min": min(page_load_times) if page_load_times else 0,
            "max": max(page_load_times) if page_load_times else 0,
            "avg": round(statistics.mean(filtered_page_load), 3) if filtered_page_load else 0,
            "median": round(statistics.median(page_load_times), 3) if page_load_times else 0,
            "stdev": round(statistics.stdev(page_load_times), 3) if len(page_load_times) > 1 else 0,
            "outliers_removed": len(page_load_times) - len(filtered_page_load)
        },
        "auth": {
            "min": min(auth_times) if auth_times else 0,
            "max": max(auth_times) if auth_times else 0,
            "avg": round(statistics.mean(filtered_auth), 3) if filtered_auth else 0,
            "median": round(statistics.median(auth_times), 3) if auth_times else 0,
            "stdev": round(statistics.stdev(auth_times), 3) if len(auth_times) > 1 else 0,
            "outliers_removed": len(auth_times) - len(filtered_auth)
        },
        "total": {
            "min": min(total_times) if total_times else 0,
            "max": max(total_times) if total_times else 0,
            "avg": round(statistics.mean(filtered_total), 3) if filtered_total else 0,
            "median": round(statistics.median(total_times), 3) if total_times else 0,
            "stdev": round(statistics.stdev(total_times), 3) if len(total_times) > 1 else 0,
            "outliers_removed": len(total_times) - len(filtered_total)
        }
    }
    
    return stats

def save_results_to_csv(results, filename="login_performance_results.csv"):
    """Sauvegarde les résultats dans un fichier CSV"""
    if not results:
        print("Aucun résultat à sauvegarder")
        return
        
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    filename = f"login_performance_results_{timestamp}.csv"
        
    with open(filename, mode='w', newline='', encoding='utf-8') as file:
        fieldnames = ["iteration", "timestamp", "page_load_time", "auth_time", "total_time"]
        writer = csv.DictWriter(file, fieldnames=fieldnames)
        
        writer.writeheader()
        for result in results:
            writer.writerow(result)
    
    print(f"Résultats sauvegardés dans {filename}")
    return filename

def generate_performance_graphs(results, timestamps, filename=None):
    """Génère des graphiques de performance"""
    if not results:
        print("Aucun résultat à visualiser")
        return
    
    if filename is None:
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        filename = f"login_performance_graph_{timestamp}.png"
        
    # Extraction des données
    iterations = [r["iteration"] for r in results]
    page_load_times = [r["page_load_time"] for r in results]
    auth_times = [r["auth_time"] for r in results]
    total_times = [r["total_time"] for r in results]
    
    # Création de la figure avec un style moderne
    plt.figure(figsize=(14, 10))
    plt.style.use('ggplot')
    
    # Graphique des temps de chargement
    plt.subplot(2, 1, 1)
    plt.plot(iterations, page_load_times, 'o-', color='#3B82F6', linewidth=2, label="Chargement de la page")
    plt.plot(iterations, auth_times, 'o-', color='#6B46C1', linewidth=2, label="Authentification")
    plt.plot(iterations, total_times, 'o-', color='#10B981', linewidth=2, label="Temps total")
    plt.title("Performance de connexion par itération", fontsize=14, fontweight='bold')
    plt.xlabel("Itération", fontsize=12)
    plt.ylabel("Temps (secondes)", fontsize=12)
    plt.legend(fontsize=10)
    plt.grid(True, alpha=0.3)
    
    # Graphique des temps par composant (empilé)
    plt.subplot(2, 1, 2)
    
    # Calculer la moyenne des différentes phases
    avg_page_load = statistics.mean(page_load_times)
    avg_auth = statistics.mean(auth_times)
    
    labels = ['Chargement Page', 'Authentification']
    sizes = [avg_page_load, avg_auth]
    colors = ['#3B82F6', '#6B46C1']
    
    plt.bar(labels, sizes, color=colors)
    plt.title("Temps moyen par composant", fontsize=14, fontweight='bold')
    plt.ylabel("Temps (secondes)", fontsize=12)
    plt.grid(True, alpha=0.3, axis='y')
    
    # Ajouter les valeurs sur les barres
    for i, v in enumerate(sizes):
        plt.text(i, v/2, f"{v:.2f}s", ha='center', fontsize=10, fontweight='bold', color='white')
    
    plt.tight_layout()
    plt.savefig(filename, dpi=300, bbox_inches='tight')
    print(f"Graphique sauvegardé dans {filename}")
    return filename

def run_performance_test(iterations=10):
    """Exécute le test de performance complet"""
    print("=== DÉMARRAGE DU TEST DE PERFORMANCE DE CONNEXION ===")
    print(f"Application testée: e-CongeIBAM")
    print(f"URL de base: {baseurl}")
    print(f"Date et heure du test: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    print(f"Nombre d'itérations planifiées: {iterations}")
    print("---------------------------------------------------")
    
    # Mesure des performances
    start_test = time.time()
    results, timestamps = measure_login_performance(iterations)
    test_duration = time.time() - start_test
    
    # Analyse des résultats
    stats = analyze_results(results)
    
    # Affichage des statistiques
    print("\n=== RÉSULTATS DU TEST DE PERFORMANCE ===")
    print(f"Nombre d'itérations réussies: {len(results)}/{iterations}")
    print(f"Durée totale du test: {round(test_duration, 2)} secondes")
    
    print("\nTEMPS DE CHARGEMENT DE PAGE:")
    print(f"  Min: {stats['page_load']['min']}s")
    print(f"  Max: {stats['page_load']['max']}s")
    print(f"  Moyenne: {stats['page_load']['avg']}s (après suppression de {stats['page_load']['outliers_removed']} valeurs aberrantes)")
    print(f"  Médiane: {stats['page_load']['median']}s")
    print(f"  Écart-type: {stats['page_load']['stdev']}s")
    
    print("\nTEMPS D'AUTHENTIFICATION:")
    print(f"  Min: {stats['auth']['min']}s")
    print(f"  Max: {stats['auth']['max']}s")
    print(f"  Moyenne: {stats['auth']['avg']}s (après suppression de {stats['auth']['outliers_removed']} valeurs aberrantes)")
    print(f"  Médiane: {stats['auth']['median']}s")
    print(f"  Écart-type: {stats['auth']['stdev']}s")
    
    print("\nTEMPS TOTAL:")
    print(f"  Min: {stats['total']['min']}s")
    print(f"  Max: {stats['total']['max']}s")
    print(f"  Moyenne: {stats['total']['avg']}s (après suppression de {stats['total']['outliers_removed']} valeurs aberrantes)")
    print(f"  Médiane: {stats['total']['median']}s")
    print(f"  Écart-type: {stats['total']['stdev']}s")
    
    # Sauvegarde des résultats si des tests ont réussi
    if results:
        csv_file = save_results_to_csv(results)
        graph_file = generate_performance_graphs(results, timestamps)
        
        print(f"\nRésultats sauvegardés dans: {csv_file}")
        print(f"Graphiques sauvegardés dans: {graph_file}")
    
    # Recommandations basées sur les résultats
    print("\n=== RECOMMANDATIONS ===")
    
    # Vérifier si le temps de chargement est acceptable
    if stats['page_load']['avg'] > 3:
        print("⚠️ Le temps de chargement de la page est élevé (> 3s). Considérez:")
        print("   - Optimiser les ressources de la page de connexion")
        print("   - Vérifier la compression des ressources statiques")
        print("   - Implémenter du lazy loading pour les images")
    else:
        print("✓ Le temps de chargement de la page est acceptable.")
        
    # Vérifier si le temps d'authentification est acceptable
    if stats['auth']['avg'] > 2:
        print("⚠️ Le temps d'authentification est élevé (> 2s). Considérez:")
        print("   - Vérifier l'efficacité de l'authentification côté serveur")
        print("   - Optimiser les requêtes d'authentification")
    else:
        print("✓ Le temps d'authentification est acceptable.")
    
    # Vérifier la cohérence des résultats
    if stats['total']['stdev'] > stats['total']['avg'] * 0.5:
        print("⚠️ Forte variation dans les temps de performance. Considérez:")
        print("   - Vérifier la stabilité du serveur et des ressources")
        print("   - Réexécuter les tests à différents moments de la journée")
    else:
        print("✓ La performance est consistante à travers toutes les itérations.")
    
    print("\n=== TEST DE PERFORMANCE TERMINÉ ===")
    
    return {
        "success_rate": len(results)/iterations,
        "avg_total_time": stats['total']['avg'],
        "median_total_time": stats['total']['median']
    }

if __name__ == "__main__":
    # Nombre d'itérations pour le test (peut être modifié)
    run_performance_test(iterations=15)