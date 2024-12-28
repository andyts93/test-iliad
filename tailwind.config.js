import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                "brutal": "4px 4px 0px",
                "brutal-sm": "3px 3px 0px",
                "brutal-md": "6px 6px 0px",
                "brutal-lg": "8px 8px 0px"
            },
            animation: {
                'spin-slow': 'spin 2s linear infinite',
            }
        },
    },
    plugins: [],
};
