/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./templates/**/*.twig",
    "./assets/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      container: {
        center: true
      },
      transitionProperty: {
        'width': 'width'
    },
    letterSpacing:{
      'tightest': '-1rem'
    }
    },
  },
  darkMode: 'class',
}

