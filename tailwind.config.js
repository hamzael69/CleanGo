/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./templates/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        vertBase: '#3AA89E',
      },
      backgroundImage: {
        'hero': "url('../img/baniere.jpg')",
      },
    },
  },
  plugins: [],
}
