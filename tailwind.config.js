import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    DEFAULT: '#b83b3b', // Warna bata/burgundy dari hero
                    dark: '#9a2e2e',
                },
                accent: {
                    DEFAULT: '#f37b42', // Oranye/Peach untuk highlight/button
                    light: '#fce3d6', // Warna background section services
                }
            }
        },
    },

    plugins: [forms],
};
