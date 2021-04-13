module.exports = {
    purge: [
        './templates/*.twig',
        './templates/*.css',
    ],
    darkMode: false,
    theme: {
        extend: {
            spacing: {
                '22': '5.5rem',
                '30': '7.5rem',
                '50': '12.5rem',
                '76': '19rem',
            }
        },
        screens: {
            'xs': '360px',
            'sm': '640px',
            'md': '768px',
            'lg': '1024px',
            'xl': '1280px',
            '2xl': '1536px',
        },
    },
    variants: {
        extend: {},
    },
    plugins: []
}