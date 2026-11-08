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
                sans: ['system-ui', '-apple-system', '"Segoe UI"', 'Roboto', '"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                amiri: ['"Amiri"', 'serif'],
            },
            colors: {
                biru: {
                    DEFAULT: '#1f3a6e',
                    tua: '#162a52',
                    muda: '#e8edf7',
                },
                emas: {
                    DEFAULT: '#c9951b',
                    tua: '#8a6410',
                    muda: '#fbf1d8',
                    terang: '#f0d48a',
                },
                krem: '#f7f3ea',
                garis: '#e2d9c6',
                teks: '#2b2a27',
                redup: '#6d675b',
                hijau: {
                    DEFAULT: '#2f7d4f',
                    muda: '#e1f1e7',
                },
                merah: {
                    DEFAULT: '#b3392f',
                    muda: '#f7e1de',
                },
            },
        },
    },

    plugins: [forms],
};
