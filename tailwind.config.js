/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      backgroundImage:{
        "home": "url("+ "../../public/banner.jpeg" + ")"
      }
    },
  },
  plugins: [],
}

