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

def test_service_form_performance(iterations=10):
    """Mesure les performances des formulaires d'ajout de service"""
    # Configuration pour stocker les données de performance
    results = []
    timestamps = []
    
    # Options Chrome pour améliorer la performance des tests
    chrome_options = Options()
    chrome_options.add_argument("--headless")  # Mode headless pour test de performance
    chrome_options.add_argument("--disable-gpu")
    chrome_options.add_argument("--no-sandbox")
    chrome_options.add_argument("--disable-dev-shm-usage")
    chrome_options.add_argument("--window-size=1920,1080")
    
    # Déterminer les éléments à tester pour le formulaire de service
    form_name = "Service"
    add_button_text = "Ajouter un service"
    modal_title = "Ajouter un service"
    form_fields = {"nom": f"Test Service {datetime.now().strftime('%Y%m%d_%H%M%S')}", 
                    "description": "Description de test pour le service"}
    
    print(f"Démarrage du test de performance pour {form_name} - {iterations} itérations")
    
    for i in range(iterations):
        # Initialiser un nouveau driver pour chaque test (isolation)
        try:
            service = Service(ChromeDriverManager().install())
            driver = webdriver.Chrome(service=service, options=chrome_options)
            driver.set_page_load_timeout(30)
            
            print(f"Itération {i+1}/{iterations}")
            
            # Se connecter d'abord
            driver.get(baseurl)
            wait = WebDriverWait(driver, 10)
            
            # Authentification
            email_input = wait.until(EC.presence_of_element_located((By.ID, "email")))
            password_input = driver.find_element(By.ID, "password")
            email_input.clear()
            password_input.clear()
            email_input.send_keys(email)
            password_input.send_keys(password)
            
            login_button = driver.find_element(By.CSS_SELECTOR, "button.btn.btn-primary")
            login_button.click()
            
            # Attendre que le dashboard soit chargé
            wait.until(EC.url_contains("/dashboard"))
            
            # Navigation vers la page des services
            driver.get(f"{baseurl}/services")
            
            wait.until(EC.presence_of_element_located((By.TAG_NAME, "body")))
            
            # Mesurer la performance du formulaire
            timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
            
            # Trouver le bouton pour ouvrir le modal
            try:
                # Chercher par texte ou par CSS selon la structure HTML
                modal_button = driver.find_element(By.XPATH, f"//button[contains(text(), '{add_button_text}')]")
            except:
                try:
                    modal_button = driver.find_element(By.CSS_SELECTOR, ".btn-primary, .add-button")
                except Exception as e:
                    print(f"Impossible de trouver le bouton d'ajout: {str(e)}")
                    driver.save_screenshot(f"error_button_service_{i+1}.png")
                    driver.quit()
                    continue  # Passer à l'itération suivante
            
            # Mesurer le temps d'ouverture du modal
            start_modal_time = time.time()
            modal_button.click()
            
            # Attendre que le modal soit visible
            try:
                wait.until(EC.visibility_of_element_located((By.XPATH, f"//h5[contains(text(), '{modal_title}')]")))
                modal_load_time = time.time() - start_modal_time
            except:
                try:
                    wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR, ".modal.show")))
                    modal_load_time = time.time() - start_modal_time
                except Exception as e:
                    print(f"Modal non trouvé: {str(e)}")
                    driver.save_screenshot(f"error_modal_service_{i+1}.png")
                    driver.quit()
                    continue
            
            # Remplir le formulaire
            start_form_fill = time.time()
            
            # Remplir chaque champ
            for field_name, field_value in form_fields.items():
                try:
                    input_field = driver.find_element(By.ID, field_name)
                except:
                    try:
                        input_field = driver.find_element(By.NAME, field_name)
                    except:
                        try:
                            # Chercher par label
                            input_field = driver.find_element(By.XPATH, f"//label[contains(text(), '{field_name.capitalize()}')]/following-sibling::input")
                        except Exception as e:
                            print(f"Champ non trouvé {field_name}: {str(e)}")
                            driver.save_screenshot(f"error_field_service_{field_name}_{i+1}.png")
                            continue
                
                input_field.clear()
                input_field.send_keys(field_value)
            
            form_fill_time = time.time() - start_form_fill
            
            # Soumettre le formulaire
            try:
                submit_button = driver.find_element(By.XPATH, "//button[contains(text(), 'Ajouter')]")
            except:
                try:
                    submit_button = driver.find_element(By.CSS_SELECTOR, "form button[type='submit'], .modal-footer button.btn-primary")
                except Exception as e:
                    print(f"Bouton de soumission non trouvé: {str(e)}")
                    driver.save_screenshot(f"error_submit_service_{i+1}.png")
                    driver.quit()
                    continue
            
            start_submit_time = time.time()
            submit_button.click()
            
            # Attendre la confirmation de soumission
            try:
                # Attendre soit la fermeture du modal, soit un message de succès
                wait.until(lambda d: not d.find_elements(By.CSS_SELECTOR, ".modal.show") or 
                                    d.find_elements(By.CSS_SELECTOR, ".alert-success"))
                submit_time = time.time() - start_submit_time
                
                # Vérifier si l'élément a été ajouté (via un message success ou la présence dans la liste)
                try:
                    success = driver.find_element(By.CSS_SELECTOR, ".alert-success, .toast-success")
                    submission_success = True
                except:
                    # Vérifier si le nouvel élément est visible dans la liste
                    try:
                        wait.until(EC.visibility_of_element_located(
                            (By.XPATH, f"//td[contains(text(), '{form_fields['nom']}')]")))
                        submission_success = True
                    except:
                        submission_success = False
                        print(f"Impossible de confirmer l'ajout du {form_name.lower()}")
                        driver.save_screenshot(f"submission_verification_service_{i+1}.png")
            except Exception as e:
                print(f"Erreur lors de la soumission: {str(e)}")
                submission_success = False
                submit_time = 0
                driver.save_screenshot(f"error_waiting_service_{i+1}.png")
            
            # Calcul du temps total
            total_time = modal_load_time + form_fill_time + submit_time
            
            # Stocker les résultats
            result = {
                "iteration": i+1,
                "timestamp": timestamp,
                "modal_load_time": round(modal_load_time, 3),
                "form_fill_time": round(form_fill_time, 3),
                "submit_time": round(submit_time, 3),
                "total_time": round(total_time, 3),
                "success": submission_success
            }
            
            results.append(result)
            timestamps.append(timestamp)
            
            print(f"  Modal: {result['modal_load_time']}s, Remplissage: {result['form_fill_time']}s, Soumission: {result['submit_time']}s, Total: {result['total_time']}s, Succès: {result['success']}")
            
            # Pause entre les tests
            time.sleep(2)
            
        except Exception as e:
            print(f"Erreur lors de l'itération {i+1}: {str(e)}")
        finally:
            try:
                driver.quit()
            except:
                pass
    
    return results, timestamps

def analyze_form_results(results):
    """Analyse les résultats des tests de performance des formulaires"""
    if not results:
        print("Aucun résultat à analyser")
        return {
            "modal_load": {"min": 0, "max": 0, "avg": 0, "median": 0, "stdev": 0},
            "form_fill": {"min": 0, "max": 0, "avg": 0, "median": 0, "stdev": 0},
            "submit": {"min": 0, "max": 0, "avg": 0, "median": 0, "stdev": 0},
            "total": {"min": 0, "max": 0, "avg": 0, "median": 0, "stdev": 0},
            "success_rate": 0
        }
    
    # Extraire les valeurs pour l'analyse
    modal_load_times = [r["modal_load_time"] for r in results]
    form_fill_times = [r["form_fill_time"] for r in results]
    submit_times = [r["submit_time"] for r in results]
    total_times = [r["total_time"] for r in results]
    success_count = sum(1 for r in results if r["success"])
    
    # Identifier les valeurs aberrantes potentielles
    def identify_outliers(data):
        if len(data) < 4:
            return data
            
        q1 = statistics.quantiles(sorted(data), n=4)[0]
        q3 = statistics.quantiles(sorted(data), n=4)[2]
        iqr = q3 - q1
        lower_bound = q1 - (1.5 * iqr)
        upper_bound = q3 + (1.5 * iqr)
        
        return [x for x in data if lower_bound <= x <= upper_bound]
    
    # Filtrer les valeurs aberrantes
    filtered_modal = identify_outliers(modal_load_times)
    filtered_form = identify_outliers(form_fill_times)
    filtered_submit = identify_outliers(submit_times)
    filtered_total = identify_outliers(total_times)
    
    # Calcul des statistiques
    stats = {
        "modal_load": {
            "min": min(modal_load_times) if modal_load_times else 0,
            "max": max(modal_load_times) if modal_load_times else 0,
            "avg": round(statistics.mean(filtered_modal), 3) if filtered_modal else 0,
            "median": round(statistics.median(modal_load_times), 3) if modal_load_times else 0,
            "stdev": round(statistics.stdev(modal_load_times), 3) if len(modal_load_times) > 1 else 0,
            "outliers_removed": len(modal_load_times) - len(filtered_modal)
        },
        "form_fill": {
            "min": min(form_fill_times) if form_fill_times else 0,
            "max": max(form_fill_times) if form_fill_times else 0,
            "avg": round(statistics.mean(filtered_form), 3) if filtered_form else 0,
            "median": round(statistics.median(form_fill_times), 3) if form_fill_times else 0,
            "stdev": round(statistics.stdev(form_fill_times), 3) if len(form_fill_times) > 1 else 0,
            "outliers_removed": len(form_fill_times) - len(filtered_form)
        },
        "submit": {
            "min": min(submit_times) if submit_times else 0,
            "max": max(submit_times) if submit_times else 0,
            "avg": round(statistics.mean(filtered_submit), 3) if filtered_submit else 0,
            "median": round(statistics.median(submit_times), 3) if submit_times else 0,
            "stdev": round(statistics.stdev(submit_times), 3) if len(submit_times) > 1 else 0,
            "outliers_removed": len(submit_times) - len(filtered_submit)
        },
        "total": {
            "min": min(total_times) if total_times else 0,
            "max": max(total_times) if total_times else 0,
            "avg": round(statistics.mean(filtered_total), 3) if filtered_total else 0,
            "median": round(statistics.median(total_times), 3) if total_times else 0,
            "stdev": round(statistics.stdev(total_times), 3) if len(total_times) > 1 else 0,
            "outliers_removed": len(total_times) - len(filtered_total)
        },
        "success_rate": round(success_count / len(results) * 100, 1) if results else 0
    }
    
    return stats

def save_service_results_to_csv(results, filename=None):
    """Sauvegarde les résultats dans un fichier CSV"""
    if not results:
        print("Aucun résultat à sauvegarder")
        return
        
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    if filename is None:
        filename = f"service_form_performance_{timestamp}.csv"
        
    with open(filename, mode='w', newline='', encoding='utf-8') as file:
        fieldnames = ["iteration", "timestamp", "modal_load_time", "form_fill_time", "submit_time", "total_time", "success"]
        writer = csv.DictWriter(file, fieldnames=fieldnames)
        
        writer.writeheader()
        for result in results:
            writer.writerow(result)
    
    print(f"Résultats sauvegardés dans {filename}")
    return filename

def generate_service_performance_graphs(results, timestamps, filename=None):
    """Génère des graphiques de performance pour les formulaires de service"""
    if not results:
        print("Aucun résultat à visualiser")
        return
    
    if filename is None:
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        filename = f"service_form_performance_graph_{timestamp}.png"
        
    # Extraction des données
    iterations = [r["iteration"] for r in results]
    modal_times = [r["modal_load_time"] for r in results]
    form_fill_times = [r["form_fill_time"] for r in results]
    submit_times = [r["submit_time"] for r in results]
    total_times = [r["total_time"] for r in results]
    success_status = [r["success"] for r in results]
    
    # Création de la figure
    plt.figure(figsize=(14, 10))
    plt.style.use('ggplot')
    
    # Graphique des temps par itération
    plt.subplot(2, 1, 1)
    plt.plot(iterations, modal_times, 'o-', color='#3B82F6', linewidth=2, label="Chargement du modal")
    plt.plot(iterations, form_fill_times, 'o-', color='#6B46C1', linewidth=2, label="Remplissage du formulaire")
    plt.plot(iterations, submit_times, 'o-', color='#10B981', linewidth=2, label="Soumission")
    plt.plot(iterations, total_times, 'o-', color='#F59E0B', linewidth=2, label="Temps total")
    
    # Marquer les échecs
    for i, (iter_num, success) in enumerate(zip(iterations, success_status)):
        if not success:
            plt.plot(iter_num, total_times[i], 'rx', markersize=10)
    
    plt.title("Performance du formulaire 'service' par itération", fontsize=14, fontweight='bold')
    plt.xlabel("Itération", fontsize=12)
    plt.ylabel("Temps (secondes)", fontsize=12)
    plt.legend(fontsize=10)
    plt.grid(True, alpha=0.3)
    
    # Graphique des temps par composant
    plt.subplot(2, 1, 2)
    
    # Calculer la moyenne des différentes phases
    avg_modal = statistics.mean(modal_times)
    avg_form = statistics.mean(form_fill_times)
    avg_submit = statistics.mean(submit_times)
    
    labels = ['Chargement Modal', 'Remplissage', 'Soumission']
    sizes = [avg_modal, avg_form, avg_submit]
    colors = ['#3B82F6', '#6B46C1', '#10B981']
    
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

def run_service_performance_test(iterations=10):
    """Exécute le test de performance complet pour un formulaire de service"""
    form_name = "Service"
    
    print(f"=== DÉMARRAGE DU TEST DE PERFORMANCE DU FORMULAIRE {form_name.upper()} ===")
    print(f"Application testée: e-CongeIBAM")
    print(f"URL de base: {baseurl}")
    print(f"Date et heure du test: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    print(f"Nombre d'itérations planifiées: {iterations}")
    print("---------------------------------------------------")
    
    # Mesure des performances
    start_test = time.time()
    results, timestamps = test_service_form_performance(iterations)
    test_duration = time.time() - start_test
    
    # Analyse des résultats
    stats = analyze_form_results(results)
    
    # Affichage des statistiques
    print(f"\n=== RÉSULTATS DU TEST DE PERFORMANCE DU FORMULAIRE {form_name.upper()} ===")
    print(f"Nombre d'itérations réussies: {len(results)}/{iterations}")
    print(f"Taux de succès: {stats['success_rate']}%")
    print(f"Durée totale du test: {round(test_duration, 2)} secondes")
    
    print("\nTEMPS DE CHARGEMENT DU MODAL:")
    print(f"  Min: {stats['modal_load']['min']}s")
    print(f"  Max: {stats['modal_load']['max']}s")
    print(f"  Moyenne: {stats['modal_load']['avg']}s (après suppression de {stats['modal_load']['outliers_removed']} valeurs aberrantes)")
    print(f"  Médiane: {stats['modal_load']['median']}s")
    print(f"  Écart-type: {stats['modal_load']['stdev']}s")
    
    print("\nTEMPS DE REMPLISSAGE DU FORMULAIRE:")
    print(f"  Min: {stats['form_fill']['min']}s")
    print(f"  Max: {stats['form_fill']['max']}s")
    print(f"  Moyenne: {stats['form_fill']['avg']}s (après suppression de {stats['form_fill']['outliers_removed']} valeurs aberrantes)")
    print(f"  Médiane: {stats['form_fill']['median']}s")
    print(f"  Écart-type: {stats['form_fill']['stdev']}s")
    
    print("\nTEMPS DE SOUMISSION:")
    print(f"  Min: {stats['submit']['min']}s")
    print(f"  Max: {stats['submit']['max']}s")
    print(f"  Moyenne: {stats['submit']['avg']}s (après suppression de {stats['submit']['outliers_removed']} valeurs aberrantes)")
    print(f"  Médiane: {stats['submit']['median']}s")
    print(f"  Écart-type: {stats['submit']['stdev']}s")
    
    print("\nTEMPS TOTAL:")
    print(f"  Min: {stats['total']['min']}s")
    print(f"  Max: {stats['total']['max']}s")
    print(f"  Moyenne: {stats['total']['avg']}s (après suppression de {stats['total']['outliers_removed']} valeurs aberrantes)")
    print(f"  Médiane: {stats['total']['median']}s")
    print(f"  Écart-type: {stats['total']['stdev']}s")
    
    # Sauvegarde des résultats si des tests ont réussi
    if results:
        csv_file = save_service_results_to_csv(results)
        graph_file = generate_service_performance_graphs(results, timestamps)
        
        print(f"\nRésultats sauvegardés dans: {csv_file}")
        print(f"Graphiques sauvegardés dans: {graph_file}")
    
    # Recommandations basées sur les résultats
    print("\n=== RECOMMANDATIONS ===")
    
    # Vérifier le taux de succès
    if stats['success_rate'] < 90:
        print(f"⚠️ Le taux de succès des soumissions est faible (<90%). Considérez:")
        print("   - Vérifier la validation côté client et serveur")
        print("   - Examiner les logs d'erreur pour identifier les problèmes")
    else:
        print("✓ Le taux de succès est acceptable.")
        
    # Vérifier le temps de chargement du modal
    if stats['modal_load']['avg'] > 1:
        print("⚠️ Le temps de chargement du modal est élevé (>1s). Considérez:")
        print("   - Optimiser le chargement du modal")
        print("   - Vérifier les éventuels appels AJAX lors de l'ouverture")
    else:
        print("✓ Le temps de chargement du modal est acceptable.")
        
    # Vérifier le temps de soumission
    if stats['submit']['avg'] > 2:
        print("⚠️ Le temps de soumission est élevé (>2s). Considérez:")
        print("   - Optimiser le traitement côté serveur")
        print("   - Vérifier les validations et requêtes associées")
    else:
        print("✓ Le temps de soumission est acceptable.")
    
    # Vérifier la cohérence des résultats
    if stats['total']['stdev'] > stats['total']['avg'] * 0.5:
        print("⚠️ Forte variation dans les temps de performance. Considérez:")
        print("   - Vérifier la stabilité du serveur")
        print("   - Examiner les différences entre les itérations réussies et échouées")
    else:
        print("✓ La performance est consistante à travers toutes les itérations.")
    
    print(f"\n=== TEST DE PERFORMANCE DU FORMULAIRE {form_name.upper()} TERMINÉ ===")
    
    return {
        "success_rate": stats['success_rate'],
        "avg_total_time": stats['total']['avg'],
        "median_total_time": stats['total']['median']
    }

if __name__ == "__main__":
    # Exécuter le test pour le formulaire de service
    print("\n\n=== TEST DE FORMULAIRE SERVICE ===\n")
    service_results = run_service_performance_test(iterations=10)
    
    # Résumé final
    print("\n\n=== RÉSUMÉ DU TEST DE FORMULAIRE SERVICE ===")
    print(f"Test Service: {service_results['success_rate']}% succès, Temps moyen: {service_results['avg_total_time']}s")