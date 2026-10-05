import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import plugin from "tailwindcss/plugin";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
        // ★ スクロールバー装飾用プラグインを追加
        plugin(function ({ addUtilities }) {
            addUtilities({
                ".custom-scrollbar::-webkit-scrollbar": {
                    width: "6px",
                },
                ".custom-scrollbar::-webkit-scrollbar-track": {
                    background: "#1a2332",
                    borderRadius: "8px",
                },
                ".custom-scrollbar::-webkit-scrollbar-thumb": {
                    background: "#f59e0b",
                    borderRadius: "8px",
                },
            });
        }),
    ],
};
