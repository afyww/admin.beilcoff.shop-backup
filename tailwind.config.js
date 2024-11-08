/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./**/*.blade.php",
    "./**/*.js",
    "./**/*.vue",    
  ],
  theme: {
    extend: {
      fontFamily: {
        poppins: ['Poppins'],
      },
    },
  },
  plugins: [],
}

