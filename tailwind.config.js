import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                telkomsel: {
                    50: '#fff1f1',
                    100: '#ffe1e1',
                    200: '#ffc7c7',
                    300: '#ffa0a0',
                    400: '#ff6969',
                    500: '#e60000', // Warna merah khas Telkomsel
                    600: '#c40000',
                    700: '#a10000',
                    800: '#810202',
                    900: '#6b0909',
                    950: '#3b0202',
                }
            }
        },
    },

    plugins: [forms],
};
