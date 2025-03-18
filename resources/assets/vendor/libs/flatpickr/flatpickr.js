import flatpickr from "flatpickr/dist/flatpickr";
import { French } from "flatpickr/dist/l10n/fr"; // Importation de la localisation française

try {
    window.flatpickr = flatpickr;
    flatpickr.localize(French);
} catch (e) {
    console.error("Erreur lors de l'importation de Flatpickr :", e);
}

export { flatpickr };
