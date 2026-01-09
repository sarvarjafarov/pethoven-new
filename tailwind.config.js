import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './app/Filament/**/*.php',
        './vendor/filament/**/*.blade.php',
        './vendor/lunarphp/**/*.blade.php',
        // Explicitly EXCLUDE frontend views to prevent Bootstrap/Tailwind conflicts
        // Frontend uses Bootstrap from public/brancy/css/
        // './resources/views/frontend/**/*.blade.php', // EXCLUDED
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
};
