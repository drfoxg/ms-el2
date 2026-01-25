import dotenv from "dotenv";
import fs from "fs";
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

dotenv.config();

const appUrl = process.env.APP_URL;
let appHost = "localhost";

try {
    const url = new URL(appUrl);
    appHost = url.host;
} catch (error) {
    console.error(
        `Error parsing APP_URL: ${error.message}. Check your .ENV file.`
    );
}

export default defineConfig({
    base: "/",
    server: {
        host: "0.0.0.0",
        hmr: {
            host: appHost,
        },
        watch: {
            usePolling: true,
        },
        https: false,
        // https: {
        //     cert: fs.readFileSync('./letsencrypt/fullchain.pem'),
        //     key: fs.readFileSync('./letsencrypt/privkey.pem'),
        // },
    },
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
