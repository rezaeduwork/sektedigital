/** @type {import('tailwindcss').Config} */
export default {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
    ],
    theme: {
      extend: {
        colors: {
          primary: "#ff2a2a"
        },
        keyframes: {
          chatenter: {
            '0%': { width: '0px', height: '0px', opacity: 0 },
            '100%': { width: '640px', height: '502px', opacity: 1 },
          }
        },
        animation: {
          chatenter: 'chatenter .2s ease-out forwards'
        }
      },
    },
    plugins: [
      require('flowbite/plugin')
    ]
}
