import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { glob } from "glob";

/**
 * Récupère les fichiers d'un répertoire selon un pattern
 * @param {string} pattern - Le pattern de recherche glob
 * @returns {string[]} Un tableau de chemins de fichiers
 */
function getFiles(pattern) {
    return glob.sync(pattern);
}

// Configuration des chemins de fichiers
const paths = {
    js: {
        pages: getFiles("resources/assets/js/*.js"),
        vendor: getFiles("resources/assets/vendor/js/*.js"),
        libs: getFiles("resources/assets/vendor/libs/**/*.js"),
    },
    styles: {
        core: getFiles("resources/assets/vendor/scss/**/!(_)*.scss"),
        libs: {
            scss: getFiles("resources/assets/vendor/libs/**/!(_)*.scss"),
            css: getFiles("resources/assets/vendor/libs/**/*.css"),
        },
        fonts: getFiles("resources/assets/vendor/fonts/!(_)*.scss"),
    },
};

/**
 * Plugin pour gérer l'assignation des bibliothèques à window
 */
function libsWindowAssignment() {
    return {
        name: "libsWindowAssignment",
        transform(src, id) {
            const transforms = {
                "jkanban.js": [{ from: "this.jKanban", to: "window.jKanban" }],
                vfs_fonts: [{ from: "this.pdfMake", to: "window.pdfMake" }],
            };

            for (const [file, replacements] of Object.entries(transforms)) {
                if (id.includes(file)) {
                    return replacements.reduce(
                        (content, { from, to }) => content.replaceAll(from, to),
                        src,
                    );
                }
            }

            return src;
        },
    };
}

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/assets/css/demo.css",
                "resources/js/app.js",
                ...paths.js.pages,
                ...paths.js.vendor,
                ...paths.js.libs,
                ...paths.styles.core,
                ...paths.styles.libs.scss,
                ...paths.styles.libs.css,
                ...paths.styles.fonts,
            ],
            refresh: true,
        }),
        libsWindowAssignment(),
    ],
    // Ajout de la configuration de build pour optimisation
    build: {
        outDir: "public/build",
        assetsDir: "",
        manifest: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: [...paths.js.vendor, ...paths.js.libs],
                },
            },
        },
    },
});
