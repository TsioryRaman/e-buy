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
      }
    },
  },
  plugins: [],
}

