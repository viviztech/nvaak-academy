import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
                sans: ['Inter', 'system-ui', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'nvaak': {
                    'navy':        '#1E3A5F',
                    'navy-dark':   '#163050',
                    'navy-900':    '#0f2240',
                    'orange':      '#F97316',
                    'orange-dark': '#ea6c00',
                    'teal':        '#218091',
                    'teal-50':     '#f0f9fa',
                    'teal-100':    '#d0eef2',
                    'teal-500':    '#218091',
                    'teal-600':    '#1a6b7a',
                    'teal-700':    '#145663',
                    'teal-800':    '#0f4450',
                    'cream':       '#fcfcf9',
                    'cream-50':    '#fcfcf9',
                },
            },
        },
    },

    plugins: [forms, typography],
};
