import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    server: {
        host: "localhost",
    },
    plugins: [
        laravel({
            input: [
                "resources/css/app.scss",
                "resources/js/app.js",
                "resources/js/plugins/flatpickr.js",
                "resources/js/custom/store.js",
                "resources/js/main.js",
            ],
            refresh: true,
        }),
    ],
});
